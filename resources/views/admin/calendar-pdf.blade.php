<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>{{ $user->name }} - {{ $month }} Takvimi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 13px;
        }

        th {
            background-color: #f0f0f0;
        }

        h2 {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <h2>{{ $user->name }} - {{ $month }} Etkinlik Listesi</h2>

    <table>
        <thead>
            <tr>
                <th>Başlık</th>
                <th>Açıklama</th>
                <th>Başlangıç</th>
                <th>Bitiş</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
            <tr>
                <td>{{ $event->title }}</td>
                <td>{{ $event->description }}</td>
                <td>{{ \Carbon\Carbon::parse($event->start)->format('d.m.Y H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($event->end)->format('d.m.Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">Bu ay için etkinlik bulunamadı.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>