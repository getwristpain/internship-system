<div>
    @if (session()->has('status'))
        <p class="text-green-600">{{ session('status') }}</p>
    @elseif (session()->has('message.success'))
        <p class="text-green-600">{{ session('message.success') }}</p>
    @elseif (session()->has('message.info'))
        <p class="text-blue-600">{{ session('message.info') }}</p>
    @elseif (session()->has('message.error'))
        <p class="text-red-600">{{ session('message.error') }}</p>
    @elseif (session()->has('message.warning'))
        <p class="text-yellow-600">{{ session('message.warning') }}</p>
    @endif
</div>
