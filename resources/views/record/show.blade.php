<ul>
    @foreach ($data as $key => $value)
        @include('record.nested', ['key' => $key, 'value' => $value])
    @endforeach
</ul>

<script>
    function toggleList(button) {
        let ul = button.parentNode.querySelector('ul');
        if (ul.style.display === 'none') {
            ul.style.display = 'block';
            button.textContent = '-';
        } else {
            ul.style.display = 'none';
            button.textContent = '+';
        }
    }
</script>
