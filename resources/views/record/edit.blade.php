<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1920, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create record</title>
</head>
<body>

<div class="container">
    <form id="my-form">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="code">Code:</label>
            <textarea class="form-control" name="code" id="code" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label for="method-select">Method:</label>
            <select class="form-control" id="method-select" name="method">
                <option value="GET">GET</option>
                <option value="POST">POST</option>
            </select>
        </div>
        <div class="form-group">
            <label for="token">Token:</label>
            <input class="form-control" type="text" name="token" id="token">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div id="response-container"></div>
</div>

<script>
    // Listen for the form submission
    document.getElementById('my-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Get the form data
        const method = document.getElementById('method-select').value;
        const token = document.getElementById('token').value;

        const formData = new FormData(event.target);
        const queryString = new URLSearchParams(formData).toString();
        const getEndpoint = new URL('{{ route('records.update.get', ['record' => $record])}}');


        if(method === 'GET') {
            fetch(getEndpoint + '?' + queryString, {
                headers: {
                    AuthToken: token
                }
            }).then(
                response => {
                    return response.text();
                }
            ).then(
                text => {
                    document.getElementById('response-container')
                        .innerHTML = text; // запишем ответ сервера
                }
            );
        }

        if(method === 'POST') {
            fetch('{{ route('records.update', ['record' => $record])}}', {
                headers: {
                    AuthToken: token
                },
                method: 'POST',
                body: formData
            }).then(
                response => {
                    return response.text();
                }
            ).then(
                text => {
                    document.getElementById('response-container')
                        .innerHTML = text; // запишем ответ сервера
                }
            );
        }
    });

    // Listen for changes to the method select input
    document.getElementById('method-select').addEventListener('change', function(event) {
        const form = document.getElementById('my-form');
        form.setAttribute('method', event.target.value);
    });
</script>

</body>
</html>