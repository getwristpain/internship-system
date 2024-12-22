<?php

use Livewire\Volt\Component;
use App\Services\AuthService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use App\Livewire\Forms\RegisterForm;
use Illuminate\Validation\Rules\Password;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $accountType = 'student';

    public function rules()
    {
        return [
            'accountType' => 'required|in:student,teacher',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string', 'min:8', 'confirmed', Password::defaults()],
        ];
    }

    public function register(string $accountType)
    {
        $this->accountType = $accountType ?? $this->accountType;

        $this->validate();
        $authService = new AuthService([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'accountType' => $this->accountType,
        ]);

        $authService->register();

        if (Auth::check()) {
            return $this->redirect(route('dashboard'), navigate: true);
        }
    }
}; ?>

<div class="flex flex-col items-center max-w-md mx-auto space-y-12">
    <div class="text-center">
        <h1 class="text-2xl font-heading">Daftar Akun</h1>
        <p>Buat akun baru dan daftar magang pertamamu.</p>
    </div>
    <form wire:submit.prevent="register('student')" class="flex flex-col w-full space-y-12">
        <div class="space-y-4">
            <x-input-form type="text" name="name" model="name" placeholder="Nama" />
            <x-input-form type="email" name="email" model="email" placeholder="Email" />
            <x-input-form type="password" name="password" model="password" placeholder="Password" />
            <x-input-form type="password" name="password_confirmation" model="password_confirmation"
                placeholder="Konfirmasi Password" />
        </div>
        <div class="flex items-center justify-center gap-4">
            <x-button-secondary label="Daftar Sebagai Guru" action="register('teacher')"></x-button-secondary>
            <x-button-submit label="Daftar Sebagai Siswa"></x-button-submit>
        </div>
        <div class="text-center">
            <span>Sudah punya akun? <a href="{{ route('login') }}" wire:navigate
                    class="font-medium text-blue-500">Login</a></span>
        </div>
    </form>
</div>
