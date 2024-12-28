@props([
    'type' => 'button',
    'label' => '',
    'icon' => '',
    'action' => '',
    'hint' => '',
    'disabled' => '',
    'class' =>
        'bg-yellow-400 text-neutral-900 hover:bg-yellow-500 hover:shadow-lg hover:scale-105 disabled:bg-yellow-500 disabled:text-neutral-900',
])

<x-button :$type :$label :$icon :$action :$hint :$disabled :$class></x-button>
