<!DOCTYPE html>
<html>

<head>
    <title>LMS.com</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>

<body>
    <h3>{{ $details['title'] }}</h3>
    {{-- <p>{{ json_encode($details['formdata']) }}</p> --}}
    <table>
        <tr>
            <th>From Description</th>
            <th>Data</th>
        </tr>
        @if (!empty($details['formdata']))
            @foreach ($details['formdata'] as $fkey => $item)
            @php
                $formattedKey = ucfirst(str_replace('_', ' ', $fkey));
            @endphp
                <tr>
                    <td>{{ $formattedKey  }}</td>
                    <td>{{ $item }}</td>
                </tr>
            @endforeach
        @endif
    </table>


    <p>Thank you</p>
</body>

</html>
