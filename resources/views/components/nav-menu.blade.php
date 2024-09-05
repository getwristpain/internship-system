<ul {{ $attributes->merge(['class' => 'menu']) }}>
    <li><a>Item 1</a></li>
    <li>
        <details>
            <summary>Parent</summary>
            <ul class="p-2">
                <li><a>Submenu 1</a></li>
                <li><a>Submenu 2</a></li>
            </ul>
        </details>
    </li>
    <li><a>Item 3</a></li>
</ul>
