<!DOCTYPE html>
<html>
<head>
    <title>Student List Report</title>
</head>
<body>
    <h1>Student List Report</h1>
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Updated At</th>
        </tr>
        @foreach($user as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->created_at }}</td>
                <td>{{ $u->updated_at }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
