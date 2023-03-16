<li>
    <h3>{{ $key }}</h3> ({{ gettype($value) }}) @if (!is_array($value)) {{ $value }} @endif
    @if (is_array($value))
        <button onclick="toggleList(this)">-</button>
        <ul style="display: none;">
            @foreach ($value as $subKey => $subValue)
                @include('record.nested', ['key' => $subKey, 'value' => $subValue])
            @endforeach
        </ul>
    @endif
</li>
