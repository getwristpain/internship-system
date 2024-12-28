<?php

use Illuminate\Http\Request;
use Livewire\Volt\Component;
use App\Services\AuthService;
use Livewire\Attributes\Layout;

new #[Layout('layouts.guest')] class extends Component {
    public string $accessKey = '';

    /**
     * Initialize the access key from the request.
     */
    public function mount(Request $request): void
    {
        $this->accessKey = $request->input('accessKey', '');
    }

    /**
     * Perform login using the provided access key.
     */
    public function login(): bool
    {
        $authService = new AuthService(['accessKey' => $this->accessKey]);

        $authService->loginWithKey();

        if (Auth::check()) {
            $this->redirect(route('dashboard'), navigate: true);
            return true;
        }

        flash()->error(__('auth.login_error'));
        return false;
    }

    /**
     * Redirect to login page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToLogin()
    {
        return $this->redirect(route('login'), navigate: true);
    }
}; ?>

<div class="flex flex-col items-center justify-center max-w-sm mx-auto space-y-12">
    <!-- Login Heading -->
    <div class="flex flex-col w-full gap-2 my-5 text-center">
        <h1 class="text-xl font-heading">Selamat Datang, Supervisor!</h1>
        <p>Anda butuh <span class="font-medium bg-gray-300 rounded-md">kunci akses</span> untuk masuk.</p>
    </div>

    <!-- Login Form -->
    <form wire:submit.prevent="login" class="flex flex-col w-full space-y-12">
        <!-- Form Input -->
        <div class="space-y-4">
            <x-session-flash-status></x-session-flash-status>
            <x-input-form type="text" name="accessKey" model="accessKey" placeholder="Kunci Akses" custom="password"
                required />
            <x-input-checkbox name="remember" model="form.remember" label="Ingat saya" />
        </div>

        <!-- Form Action -->
        <div class="flex justify-end space-x-4">
            <x-button label="Kembali" action="redirectToLogin"></x-button>
            <x-button-submit label="Masuk"></x-button-submit>
        </div>
    </form>
</div>
