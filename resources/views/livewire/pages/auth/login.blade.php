<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    public string $background;

    public array $features = [['heading' => 'Pengelolaan Data Magang Terpadu', 'body' => 'Semua data PKL dalam satu sistem untuk akses mudah dan cepat. Pastikan setiap informasi tersimpan rapi dan aman.', 'icon' => 'mage:chart-fill'], ['heading' => 'Pelacakan Progres Siswa', 'body' => 'Pantau perkembangan siswa secara real-time selama PKL. Dapatkan laporan lengkap untuk setiap tahap kegiatan.', 'icon' => 'mage:id-card-fill'], ['heading' => 'Kolaborasi Mudah', 'body' => 'Fasilitasi komunikasi antara siswa, guru pembimbing dan supervisi. Bagikan informasi dengan feedback yang cepat dan efisien.', 'icon' => 'mage:message-conversation-fill']];

    public function mount()
    {
        $this->background = asset('img/background.png');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full h-full flex justify-center items-center bg-cover bg-fixed bg-no-repeat"
    style="background-image: url('{{ $background }}')">
    <div class="w-1/2 h-full hidden lg:flex flex-col">
        <div class="p-4 grow w-full">
            <div
                class="flex flex-col justify-center items-center w-full h-full bg-white/20 backdrop-blur-xl p-16 rounded-xl">
                <div class="flex flex-col gap-4">
                    @foreach ($features as $feature)
                        <div class="flex items-center gap-8">
                            <iconify-icon class="p-2 text-5xl bg-gray-100/10 text-gray-950 backdrop-blur-md rounded-xl"
                                icon="{{ $feature['icon'] }}"></iconify-icon>
                            <div class="flex flex-col gap-2">
                                <span class="font-heading text-base">{{ $feature['heading'] }}</span>
                                <p>{{ $feature['body'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="flex justify-center items-center bg-white w-full p-8">
            <x-button-tertiary href="#">
                <span class="text-red-600">Hubungi kami</span>&nbsp;untuk informasi lebih lanjut atau bantuan teknis.
            </x-button-tertiary>
        </div>
    </div>

    <div class="flex flex-col justify-center items-center w-full lg:w-1/2 h-full bg-white">
        <div class="grow flex justify-center items-center w-full max-w-md">
            <div class="flex flex-col gap-16">
                <!-- Session Status -->
                <x-auth-session-status class="w-full" :status="session('status')" />

                <!-- Login Heading -->
                <div class="flex flex-col gap-2 text-center w-full">
                    <h1 class="font-heading text-xl">Hello, again!</h1>
                    <p>Masuk untuk melanjutkan perjalanan magangmu.</p>
                </div>

                <!-- Login Form -->
                <form wire:submit="login" class="flex flex-col gap-16 w-full">
                    <div class="flex flex-col gap-4 w-full">
                        <!-- Email Address -->
                        <div>
                            <x-input-text type="email" name="email" model="form.email" label="Email"
                                icon="mdi:email" required autofocus />
                        </div>

                        <!-- Password -->
                        <div class="">
                            <x-input-text type="password" name="password" model="form.password" label="Password"
                                icon="mdi:password" required />
                        </div>

                        <!-- Remember Me -->
                        <div class="pt-4">
                            <x-input-checkbox name="remember" model="form.remember" label="Ingat saya" />
                        </div>
                    </div>

                    <div class="flex justify-end items-center w-full">
                        @if (Route::has('password.request'))
                            <x-button-tertiary href="{{ route('password.request') }}">
                                {{ __('Lupa password?') }}
                            </x-button-tertiary>
                        @endif
                        <x-button-primary>
                            {{ __('Masuk') }}
                        </x-button-primary>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-gray-200 w-full p-8">
            <x-button-tertiary href="{{ route('login.company') }}">
                Masuk untuk&nbsp;<span class="text-red-600">Mitra Perusahaan</span>&nbsp;-->
            </x-button-tertiary>
        </div>
    </div>
</div>
