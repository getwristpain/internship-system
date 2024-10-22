<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;
    public string $accessKey = '';

    // Method to handle the access key from the request
    public function mount(Request $request)
    {
        $this->accessKey = $request->input('accessKey', '');
    }

    public function login()
    {
        $this->form->attemptLoginWithKey($this->accessKey);
    }
};

?>

<div class="flex flex-col items-center justify-center max-w-sm mx-auto space-y-12">
    <!-- Login Heading -->
    <div class="flex flex-col w-full gap-2 my-5 text-center">
        <h1 class="text-xl font-heading">Selamat Datang, Supervisor!</h1>
        <p>Anda butuh <span class="font-medium bg-gray-300 rounded-md">kunci akses</span> untuk masuk.</p>
    </div>

    {{-- Display Error Message --}}
    @if (session()->has('error'))
        <div class="p-4 text-center text-red-700 bg-red-100 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    {{-- Login Form --}}
    <form wire:submit.prevent="login" class="flex flex-col w-full space-y-12">
        {{-- Form Input --}}
        <div class="space-y-4">
            <x-input-form type="text" name="accessKey" model="accessKey" placeholder="Kunci Akses" custom="password"
                required />
            <x-input-checkbox name="remember" model="form.remember" label="Ingat saya" />
        </div>

        {{-- Form Action --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('login') }}" class="btn btn-outline btn-neutral" wire:navigate>
                {{ __('Kembali') }}
            </a>
            <button type="submit" class="btn btn-neutral">
                {{ __('Masuk') }}
            </button>
        </div>
    </form>
</div>
