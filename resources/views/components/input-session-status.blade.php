<div>
    @if (session()->has('status'))
        <p class="text-success">{{ session('status') }}</p>
    @endif
</div>
