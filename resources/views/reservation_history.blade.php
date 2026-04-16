<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation History · Chit-Chat Café</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f0f7ee;
            font-family: 'Segoe UI', 'Poppins', system-ui, -apple-system, 'Inter', sans-serif;
            color: #1e2a1c;
            padding: 40px 24px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .history-card {
            max-width: 1000px;
            width: 100%;
            background-color: #ffffff;
            border-radius: 40px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05), 0 4px 8px rgba(0, 0, 0, 0.02);
            padding: 36px 32px 48px;
            border: 1px solid #ddebe0;
            transition: all 0.25s ease;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            margin-bottom: 28px;
            border-bottom: 2px solid #e9f5e3;
            padding-bottom: 16px;
        }

        .header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #4c9f2f 0%, #7ac74f 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
        }

        .logout-link {
            background-color: #4c9f2f;
            color: white;
            text-decoration: none;
            padding: 8px 20px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            display: inline-block;
        }

        .logout-link:hover {
            background-color: #3b7e24;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(76, 159, 47, 0.2);
        }

        .table-wrapper {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        th {
            background-color: #e9f5e3;
            color: #2c5e1e;
            font-weight: 700;
            padding: 14px 12px;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e2ecd9;
            color: #2a3a26;
            font-weight: 500;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: #f9fff7;
        }

        .empty-message {
            text-align: center;
            padding: 48px 20px;
            color: #6b7c68;
            font-size: 1rem;
            background: #fafdf8;
            border-radius: 28px;
            margin-top: 20px;
        }

        @media (max-width: 650px) {
            .history-card {
                padding: 24px 20px 36px;
            }
            .header h2 {
                font-size: 1.5rem;
                margin-bottom: 12px;
            }
            .header {
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }
            .logout-link {
                text-align: center;
            }
            th, td {
                padding: 10px 8px;
                font-size: 0.85rem;
            }
        }

        .history-card {
            animation: fadeSlideUp 0.4s ease-out;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="history-card">
        <div class="header">
            <h2>My Reservation History</h2>
            <a href="/logout" class="logout-link">Logout</a>
        </div>

        <div class="table-wrapper">
            @if(isset($history) && count($history) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Guests</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $item)
                        <tr>
                            <td>{{ $item->reservation_id }}</td>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->num_guests }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination-wrapper" style="margin-top: 20px; text-align: center;">
                    {{ $history->links() }}
                </div>

            @else
                <div class="empty-message">
                    No reservations yet. <a href="/reserve" style="color:#4c9f2f;">Make your first reservation</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>