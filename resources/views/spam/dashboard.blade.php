<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Honeypot Security Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            color: #1f2937;
        }

        .header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 25px 40px;
        }

        .header h1 {
            margin: 0 0 5px;
            font-size: 28px;
        }

        .header p {
            margin: 0;
            opacity: 0.9;
        }

        .container {
            max-width: 1250px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .alert {
            padding: 13px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.08);
        }

        .card-title {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 30px;
            font-weight: 700;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.08);
        }

        .section h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-form input {
            flex: 1;
            padding: 11px 14px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 14px;
        }

        button,
        .btn {
            border: none;
            border-radius: 7px;
            padding: 10px 15px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            font-size: 13px;
            vertical-align: top;
        }

        th {
            background: #f9fafb;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            background: #fee2e2;
            color: #991b1b;
        }

        .ip-form {
            display: inline;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .top-ip {
            display: flex;
            justify-content: space-between;
            padding: 11px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination nav {
            display: flex;
            justify-content: center;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 8px 12px;
            margin: 2px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #374151;
        }

        .back-link {
            display: inline-block;
            margin-top: 10px;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .cards {
                grid-template-columns: 1fr;
            }

            .header {
                padding: 20px;
            }

            .container {
                padding: 0 10px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>

<div class="header">

    <h1>🛡️ Honeypot Security Dashboard</h1>

    <p>
        Monitor spam attempts, suspicious IP addresses and blocked requests.
    </p>

    <a href="{{ url('/contact') }}" class="back-link">
        ← Back to Contact Form
    </a>

</div>

<div class="container">

    @if(session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert error">
            {{ session('error') }}
        </div>
    @endif


    {{-- Statistics Cards --}}

    <div class="cards">

        <div class="card">
            <div class="card-title">
                Total Spam Attempts
            </div>

            <div class="card-value">
                {{ $totalSpam }}
            </div>
        </div>


        <div class="card">
            <div class="card-title">
                Today's Spam
            </div>

            <div class="card-value">
                {{ $todaySpam }}
            </div>
        </div>


        <div class="card">
            <div class="card-title">
                Unique Spam IPs
            </div>

            <div class="card-value">
                {{ $uniqueIps }}
            </div>
        </div>


        <div class="card">
            <div class="card-title">
                Blocked IPs
            </div>

            <div class="card-value">
                {{ $blockedIps }}
            </div>
        </div>

    </div>


    {{-- Top IPs + Recent Activity --}}

    <div class="grid">

        <div class="section">

            <h2>🔥 Top Spam IP Addresses</h2>

            @forelse($topIps as $ip)

                <div class="top-ip">

                    <span>
                        {{ $ip->ip_address }}
                    </span>

                    <strong>
                        {{ $ip->attempts }} attempts
                    </strong>

                </div>

            @empty

                <p>No spam attempts recorded yet.</p>

            @endforelse

        </div>


        <div class="section">

            <h2>🕒 Recent Spam Activity</h2>

            @forelse($recentAttempts as $attempt)

                <div class="top-ip">

                    <div>

                        <strong>
                            {{ $attempt->ip_address }}
                        </strong>

                        <br>

                        <small>
                            {{ $attempt->reason }}
                        </small>

                    </div>

                    <small>
                        {{ $attempt->attempted_at?->format('d M Y H:i:s') }}
                    </small>

                </div>

            @empty

                <p>No recent spam activity.</p>

            @endforelse

        </div>

    </div>


    {{-- Spam History --}}

    <div class="section">

        <h2>📋 Spam Attempt History</h2>

        <form method="GET"
              action="{{ route('spam.dashboard') }}"
              class="search-form">

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search IP address, reason or user agent..."
            >

            <button class="btn btn-primary">
                Search
            </button>

            @if($search)
                <a href="{{ route('spam.dashboard') }}"
                   class="btn btn-secondary">
                    Clear
                </a>
            @endif

        </form>


        <table>

            <thead>

            <tr>
                <th>#</th>
                <th>IP Address</th>
                <th>Reason</th>
                <th>Route</th>
                <th>User Agent</th>
                <th>Date & Time</th>
                <th>Action</th>
            </tr>

            </thead>

            <tbody>

            @forelse($attempts as $attempt)

                <tr>

                    <td>
                        {{ $attempt->id }}
                    </td>

                    <td>
                        <strong>
                            {{ $attempt->ip_address }}
                        </strong>
                    </td>

                    <td>
                        <span class="badge">
                            {{ $attempt->reason }}
                        </span>
                    </td>

                    <td>
                        {{ $attempt->route }}
                    </td>

                    <td style="max-width:250px; white-space:normal;">
                        {{ $attempt->user_agent ?: 'Unknown' }}
                    </td>

                    <td>
                        {{ $attempt->attempted_at?->format('d M Y H:i:s') }}
                    </td>

                    <td>

                        <form
                            method="POST"
                            action="{{ route('spam.block-ip') }}"
                            class="ip-form"
                        >

                            @csrf

                            <input
                                type="hidden"
                                name="ip_address"
                                value="{{ $attempt->ip_address }}"
                            >

                            <button
                                type="submit"
                                class="btn btn-danger"
                            >
                                Block IP
                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" style="text-align:center;">
                        No spam attempts found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>


        <div class="pagination">
            {{ $attempts->links() }}
        </div>

    </div>


    {{-- Blocked IP Management --}}

    <div class="section">

        <h2>🚫 IP Blocking</h2>

        <p>
            Use the spam history above to block suspicious IP addresses.
            Blocked IPs will be prevented from submitting the contact form.
        </p>

        @php
            $blockedIpList = \App\Models\BlockedIp::latest('blocked_at')->get();
        @endphp

        @if($blockedIpList->count())

            <table>

                <thead>

                <tr>
                    <th>IP Address</th>
                    <th>Reason</th>
                    <th>Blocked At</th>
                    <th>Action</th>
                </tr>

                </thead>

                <tbody>

                @foreach($blockedIpList as $blockedIp)

                    <tr>

                        <td>
                            <strong>
                                {{ $blockedIp->ip_address }}
                            </strong>
                        </td>

                        <td>
                            {{ $blockedIp->reason }}
                        </td>

                        <td>
                            {{ $blockedIp->blocked_at?->format('d M Y H:i:s') }}
                        </td>

                        <td>

                            <form
                                method="POST"
                                action="{{ route('spam.unblock-ip', $blockedIp) }}"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-success"
                                >
                                    Unblock
                                </button>

                            </form>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        @else

            <p>
                No IP addresses are currently blocked.
            </p>

        @endif

    </div>

</div>

</body>

</html>