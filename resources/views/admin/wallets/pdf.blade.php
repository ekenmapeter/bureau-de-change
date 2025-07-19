<!DOCTYPE html>
<html>
<head>
    <title>Wallet Balance History</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Wallet Balance History</h1>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Currency</th>
                <th>Closing Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($balances as $balance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($balance->date)->format('d M, Y') }}</td>
                    <td>{{ $balance->wallet->currency->name }} ({{ $balance->wallet->currency->code }})</td>
                    <td>{{ number_format($balance->balance, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No historical data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>