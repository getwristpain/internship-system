<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public int $countdown = 0;
    public bool $emailSended = false;
    public bool $showAlert = true;

    public function mount()
    {
        $this->initializeCountdown();
    }

    /**
     * Initialize countdown from session data.
     */
    private function initializeCountdown(): void
    {
        $timestamp = session()->get('emailVerLastSentTime');

        if ($timestamp) {
            $timePassed = now()->timestamp - $timestamp;
            $remainingTime = 30 - $timePassed;

            if ($remainingTime > 0) {
                $this->countdown = $remainingTime;
                $this->emailSended = true;
                $this->dispatch('start-countdown');
            }
        }
    }

    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return;
        }

        Auth::user()->sendEmailVerificationNotification();
        flash()->success('Link verifikasi email berhasil terkirim.');

        // Start countdown and store the timestamp
        $this->startCountdown();
    }

    /**
     * Start countdown and save the timestamp in session.
     */
    private function startCountdown(): void
    {
        $this->countdown = 30;
        $this->emailSended = true;
        session()->put('emailVerLastSentTime', now()->timestamp);
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
        <div class="rounded-md alert alert-warning bg-yellow-100 text-yellow-800 border border-yellow-300">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="w-6 h-6 stroke-current shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <div class="flex flex-wrap items-center flex-1 gap-2">
                <div class="flex flex-col gap-2">
                    <p>{{ __('Anda belum memverifikasi email. Mohon verifikasi untuk mengakses semua fitur.') }}</p>
                </div>

                <div>
                    <button class="btn btn-warning bg-yellow-200 text-yellow-800 border-yellow-900 btn-sm"
                        wire:click="sendVerification" wire:loading.attr="disabled" wire:target="sendVerification"
                        @disabled($countdown > 0)>
                        <span>
                            @if ($countdown > 0)
                                {{ __('Kirim Ulang (') }} {{ $countdown }} {{ __(' detik)') }}
                            @elseif ($emailSended)
                                {{ __('Kirim Ulang') }}
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
        let countdownInterval = null;

        // Initialize countdown from localStorage if it exists
        document.addEventListener('DOMContentLoaded', function() {
            const lastSentTime = localStorage.getItem('emailVerLastSentTime');
            if (lastSentTime) {
                const timePassed = Math.floor((Date.now() - parseInt(lastSentTime)) / 1000);
                const remainingTime = 30 - timePassed;

                if (remainingTime > 0) {
                    $wire.set('countdown', remainingTime);
                    $wire.set('emailSended', true);
                    startCountdown();
                } else {
                    localStorage.removeItem('emailVerLastSentTime');
                }
            }
        });

        // Listen for Livewire event to start countdown
        $wire.on('start-countdown', () => {
            const timestamp = Date.now().toString();
            localStorage.setItem('emailVerLastSentTime', timestamp);
            startCountdown();
        });

        // Function to handle the countdown timer
        function startCountdown() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }

            countdownInterval = setInterval(() => {
                $wire.decreaseCountdown();

                const countdown = $wire.get('countdown');
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    localStorage.removeItem('emailVerLastSentTime');
                }
            }, 1000);
        }
    </script>
@endscript
