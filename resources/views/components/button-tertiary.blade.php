@props([
    'type' => 'button',
    'label' => '',
    'icon' => '',
    'action' => '',
    'hint' => '',
    'disabled' => false,
    'class' =>
        'bg-inherit text-inherit shadow-none border-0 rounded-0 hover:underline hover:opacity-80 hover:bg-inherit hover:text-inherit disabled:bg-inherit disabled:text-inherit',
])

<x-button :$type :$label :icon :$action :$hint :$disabled :$class></x-button>
