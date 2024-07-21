<div class="grid grid-cols-12 gap-4">
    <x-card class="col-span-12 flex flex-col lg:items-center lg:flex-row gap-2 lg:divide-x">
        <div class="flex justify-center w-full lg:w-fit px-4">
            <img class="square h-24 lg:h-16 rounded-full" src="{{ $user->profile->avatar }}" alt="Avatar">
        </div>

        <div class="grow flex flex-col gap-y-2 min-w-2.5 px-4">
            <div class="flex flex-wrap justify-between">
                <span class="font-medium">Nama</span>
                <span>{{ $user->name }}</span>
            </div>
            <div class="flex flex-wrap justify-between">
                <span class="font-medium">Email</span>
                <span>{{ $user->email }}</span>
            </div>
        </div>

        <div class="grow flex flex-col gap-y-2 justify-between min-w-2.5 px-4">
            <div class="flex justify-between">
                <span class="font-medium">Nilai</span>
                <span class="font-medium"> - </span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium">Status</span>
                <span class="font-medium"> - </span>
            </div>
        </div>

        <div class="grow min-w-2.5 px-4">
            <div class="flex lg:flex-col gap-y-2 justify-between">
                <span class="font-medium">Guru Pembimbing</span>
                <span> - </span>
            </div>
        </div>

        <div class="grow flex items-center gap-4 pt-4 lg:pt-0 px-4">
            <x-secondary-button class="w-full" href="" wire:navigate>
                Rapor
            </x-secondary-button>
            <x-secondary-button class="w-full" :disabled="true" href="" wire:navigate>
                Sertifikat
            </x-secondary-button>
        </div>
    </x-card>

    <x-card class="col-span-12 lg:col-span-4">
        <x-slot name="heading">Program Magang</x-slot>

        <div class="flex w-full justify-center items-center">
            <div class="flex flex-col gap-4 justify-center items-center bg-slate-100 p-4 w-full min-h-40 rounded-xl">
                <p>Kamu belum ikut program magang apapun.</p>
                <x-secondary-button :href="route('registration')" wire:navigate>
                    Daftar Magang
                </x-secondary-button>
            </div>
        </div>
    </x-card>
</div>
