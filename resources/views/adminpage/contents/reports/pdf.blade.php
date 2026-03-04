<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $data['title'] ?? 'Report' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        h1 { margin: 0 0 4px 0; font-size: 18px; }
        .meta { color: #6B7280; margin-bottom: 14px; }
        .cards { margin: 10px 0 14px 0; }
        .card { display: inline-block; border: 1px solid #E5E7EB; padding: 8px 10px; margin-right: 8px; margin-bottom: 8px; border-radius: 6px; }
        .label { font-size: 11px; color: #6B7280; margin-bottom: 2px; }
        .value { font-size: 14px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #E5E7EB; padding: 6px; text-align: left; }
        th { background: #F9FAFB; font-weight: 700; }
    </style>
</head>
<body>

    <h1>{{ $data['title'] ?? 'Report' }}</h1>

    <div class="meta">
        @if(isset($data['filters']['from'], $data['filters']['to']))
            Date Range:
            {{ \Carbon\Carbon::parse($data['filters']['from'])->format('M d, Y') }}
            —
            {{ \Carbon\Carbon::parse($data['filters']['to'])->format('M d, Y') }}
        @endif
    </div>

    @if(!empty($data['summary']))
        <div class="cards">
            @foreach($data['summary'] as $card)
                <div class="card">
                    <div class="label">{{ $card['label'] }}</div>
                    <div class="value">{{ $card['value'] }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <table>
        <thead>
        <tr>
            @foreach(($data['columns'] ?? []) as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @forelse(($data['rows'] ?? []) as $row)
            <tr>
                @foreach($row as $cell)
                    <td>{{ $cell }}</td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($data['columns'] ?? []) }}">No data found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</body>
</html>