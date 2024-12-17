@props([
    'type' => 'button',
    'label' => '',
    'icon' => '',
    'action' => '',
    'hint' => '',
    'disabled' => false,
    'className' =>
        'bg-neutral-800 text-neutral-100 hover:bg-neutral-900 hover:shadow-lg hover:scale-105 disabled:bg-neutral-800 disabled:text-neutral-100',
])

<x-button :$type :$label :icon :$action :$hint :$disabled :$className></x-button>
