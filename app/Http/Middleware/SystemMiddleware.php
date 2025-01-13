<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Exception;
use Illuminate\Http\Request;
use App\Services\SystemService;
use Symfony\Component\HttpFoundation\Response;

class SystemMiddleware
{
    /**
     * Tangani request yang masuk.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // 1. Periksa apakah sistem sudah terinstal
            if (SystemService::isInstalled()) {
                // 2. Jika sistem sudah terinstal, blokir akses ke rute '/install*'
                if ($request->is('install*')) {
                    return redirect(route('dashboard'));
                }
            } else {
                // 3. Jika sistem belum terinstal, arahkan ke rute '/install'
                if (!$request->is('install*')) {
                    return redirect(route('install'));
                }
            }

            // 4. Lanjutkan ke request berikutnya jika tidak ada masalah
            return $next($request);
        } catch (\Throwable $th) {
            // 5. Tangani kesalahan yang terjadi
            $message = Exception::handle(__('system.error.message', ['context' => 'server']), $th);

            // 6. Kembalikan respon kesalahan server ke klien
            return response()->json([
                'message' => $message,
            ], 500);
        }
    }
}
