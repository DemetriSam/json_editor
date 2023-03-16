@foreach ($records as $record)
    @php
        $data = json_decode($record->data, JSON_OBJECT_AS_ARRAY);
    @endphp

    @include('record.show', ['data' => $data])

    <form action="{{ route('records.destroy', ['record' => $record->id]) }}" method="POST">
        @csrf
        @method('DELETE');
        <button type="submit">Удалить</button>
    </form>
@endforeach