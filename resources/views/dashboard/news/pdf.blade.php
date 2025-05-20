<!DOCTYPE html>
<html>
<head>
    <title>News Report</title>
</head>
<body>
    <h1>News Report</h1>
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Slug</th>
            <th>Created At</th>
            <th>Updated At</th>
        </tr>
        @foreach($news as $n)
            <tr>
                <td>{{ $n->id }}</td>
                <td>{{ $n->title }}</td>
                <td>{{ $n->slug }}</td>
                <td>{{ $n->created_at }}</td>
                <td>{{ $n->updated_at }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
