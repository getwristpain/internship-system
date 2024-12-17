<div>
    @if (session()->has('success'))
        <p class="text-green-600">{{ session('success') }}</p>
    @elseif (session()->has('info'))
        <p class="text-blue-600">{{ session('info') }}</p>
    @elseif (session()->has('error'))
        <p class="text-red-600">{{ session('error') }}</p>
    @elseif (session()->has('warning'))
        <p class="text-yellow-600">{{ session('warning') }}</p>
    @elseif (session()->has('status'))
        <p class="text-green-600">{{ session('status') }}</p>
    @endif
</div>
