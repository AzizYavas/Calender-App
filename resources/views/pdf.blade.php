<!DOCTYPE html>
<html>
<head>
    <title>Etkinlik Raporu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Etkinlik Raporu</h1>
    <table>
        <thead>
            <tr>
                <th>Baslik</th>
                <th>Konum</th>
                <th>Baslangic Tarihi</th>
                <th>Bitis Tarihi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $event)
            <tr>
                <td>{{ $event->event_title }}</td>
                <td>{{ $event->event_location }}</td>
                <td>{{ $event->event_start }}</td>
                <td>{{ $event->event_end }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
