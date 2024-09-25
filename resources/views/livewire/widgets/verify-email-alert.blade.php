<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Component;

new class extends Component {
    public int $countdown = 0;
    public bool $showAlert = true;

    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return;
        }

        Auth::user()->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
        $this->countdown = 60;

        // Start countdown
        $this->dispatch('start-countdown');
    }

    // Decrease countdown every second
    public function decreaseCountdown(): void
    {
        if ($this->countdown > 0) {
            $this->countdown--;
        }
    }

    // Hide the alert
    public function closeAlert(): void
    {
        $this->showAlert = false;
    }
};
?>

<div>
    @if ($showAlert && !Auth::user()->hasVerifiedEmail())
        <div class="alert alert-warning rounded-md">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="w-6 h-6 stroke-current shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <div class="flex-1 flex flex-wrap items-center gap-2">
                <div class="flex flex-col gap-2">
                    <p>{{ __('Anda belum memverifikasi email. Mohon verifikasi untuk mengakses semua fitur.') }}</p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success">
                            {{ __('Tautan verifikasi telah dikirim ke email Anda.') }}
                        </p>
                    @endif
                </div>

                <div>
                    <button class="btn btn-neutral btn-outline btn-sm" wire:click="sendVerification"
                        wire:loading.attr="disabled" wire:target="sendVerification" @disabled($countdown > 0)">
                        <span>
                            @if ($countdown > 0)
                                {{ __('Kirim Ulang Email Verifikasi (') }} {{ $countdown }} {{ __(' detik)') }}
                            @else
                                {{ __('Kirim Email Verifikasi') }}
                            @endif
                        </span>
                    </button>
                </div>
            </div>

            <div>
                <button type="button" wire:click="closeAlert" class="text-gray-500 scale-125 hover:text-gray-700">
                    <iconify-icon icon="mdi:close" />
                </button>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        $wire.on('start-countdown', () => {
            const interval = setInterval(() => {
                // Emit the decreaseCountdown method
                $wire.decreaseCountdown();

                // Stop the interval when countdown reaches 0
                if ($wire.countdown <= 0) {
                    clearInterval(interval);
                }
            }, 1000); // Run every second
        });
    </script>
@endscript
