<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInternetConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $this->isConnected() ? $next($request) : response()->view('defaults.no-connection');
    }

    /**
     * Check internet connection by trying to open a socket connection.
     */
    protected function isConnected(): bool
    {
        try {
            // Mencoba membuka koneksi ke server DNS (1.1.1.1) untuk cek koneksi
            $connected = fsockopen('1.1.1.1', 53, $errno, $errstr, 2);
            if ($connected) {
                fclose($connected);
                return true;
            }

            // Jika gagal, log pesan kesalahan dan return false
            Logger::handle('error', "Failed to connect: $errstr ($errno)");

            return false;
        } catch (\Throwable $th) {
            // Tangkap exception dan log jika terjadi error saat pengecekan koneksi
            Logger::handle('error', 'Failed when checking internet connection.', $th);
            return false;
        }
    }
}
