@props([
    'options' => [],
    'disabled' => false,
    'required' => false,
    'autofocus' => false,
    'placeholder' => 'Select or create an option...',
    'name' => '',
    'model' => '',
    'label' => '',
    'allowCreate' => false,
    'searchbar' => false,
])

<div class="flex flex-col gap-1 w-full font-medium {{ $disabled ? 'opacity-80 cursor-not-allowed' : '' }}">
    <div class="flex gap-4 w-full">
        @if (!empty($label))
            <div class="flex justify-between w-full items-center">
                <label class="w-24" for="{{ $name }}">{{ $label }}</label>
                <span class="px-1">:</span>
            </div>
        @endif
        <div class="relative w-full" x-data="{
            open: false,
            search: '',
            selected: @entangle($model).live,
            options: @js($options),
            allowCreate: @js($allowCreate),
            showSearch: @js($searchbar),
            filteredOptions() {
                return this.options.filter(opt => opt.text.toLowerCase().includes(this.search.toLowerCase()));
            },
            isCreatingNew() {
                return this.allowCreate && this.search.trim() !== '' && this.filteredOptions().length === 0;
            },
            addOption() {
                if (this.isCreatingNew()) {
                    this.options.push({ value: this.search, text: this.search });
                    this.selected = this.search;
                    this.open = false;
                    this.search = '';
                }
            }
        }">
            <div @click="if (!{{ $disabled ? 'true' : 'false' }}) { open = !open }"
                class="flex w-full items-center justify-between cursor-pointer border-b px-4 py-2 text-sm {{ $disabled ? 'opacity-80 cursor-not-allowed' : '' }}">
                <span x-text="options.find(option => option.value === selected)?.text || '{{ $placeholder }}'"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline ml-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            <div x-show="open" @click.away="open = false" class="absolute bg-white border mt-1 rounded w-full z-50">
                <!-- Search Input -->
                <template x-if="showSearch">
                    <input type="text" x-model="search" placeholder="{{ $placeholder }}"
                        class="w-full border p-2 rounded-t" {{ $disabled ? 'disabled' : '' }}
                        {{ $required ? 'required' : '' }} {{ $autofocus ? 'autofocus' : '' }}>
                </template>

                <!-- Options List -->
                <template x-for="option in filteredOptions()" :key="option.value">
                    <div @click="if (!{{ $disabled ? 'true' : 'false' }}) { selected = option.value; open = false }"
                        class="p-2 hover:bg-gray-100 cursor-pointer">
                        <span x-text="option.text"></span>
                    </div>
                </template>

                <!-- Create New Option -->
                <template x-if="isCreatingNew()">
                    <div @click="addOption()" class="p-2 hover:bg-gray-100 cursor-pointer">
                        <span x-text="'Create new: ' + search"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
    <div class="w-full sm:w-auto">
        <x-input-error :messages="$errors->get($model)" class="mt-2" />
    </div>
</div>
