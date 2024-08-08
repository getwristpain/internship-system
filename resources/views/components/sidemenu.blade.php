<div class="sticky top-0 py-4">
    <div class="flex flex-col space-y-2 items-end w-full text-right">
        @role('Author')
            <span class="text-base"><b>Informasi Sekolah</b></span>
            <div class="flex flex-col space-y-2 py-2 w-full border-t">
                <x-sidemenu-item href="#schoolData">Data Sekolah</x-sidemenu-item>
                <x-sidemenu-item href="#department">Jurusan</x-sidemenu-item>
            </div>
        @endrole
    </div>

</div>
