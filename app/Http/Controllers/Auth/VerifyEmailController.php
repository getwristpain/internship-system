<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use App\Models\Status; // Import the Status model
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Check if the email is already verified
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        // Mark the email as verified and fire the Verified event
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            // Update the user's status_id to the "active" status
            $activeStatus = Status::firstOrCreate(['name' => 'active']);

            if ($activeStatus) {
                $request->user()->update(['status_id' => $activeStatus->id]);
            }
        }

        // Redirect to the intended route after verification
        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
