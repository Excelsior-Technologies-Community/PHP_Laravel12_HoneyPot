<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Honeypot Security Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family:
                'Segoe UI',
                Tahoma,
                Geneva,
                Verdana,
                sans-serif;

            background: #f4f6f9;

            color: #1f2937;
        }

        .header {
            background:
                linear-gradient(135deg,
                    #667eea,
                    #764ba2);

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
            max-width: 1350px;

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

            grid-template-columns:
                repeat(4, 1fr);

            gap: 20px;

            margin-bottom: 30px;
        }

        .card {
            background: white;

            border-radius: 12px;

            padding: 22px;

            box-shadow:
                0 5px 18px rgba(0, 0, 0, 0.08);
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

            box-shadow:
                0 5px 18px rgba(0, 0, 0, 0.08);
        }

        .section h2 {
            margin-top: 0;

            margin-bottom: 20px;

            font-size: 20px;
        }

        .filters {
            display: grid;

            grid-template-columns:
                2fr 1.5fr 1fr 1fr;

            gap: 10px;

            margin-bottom: 15px;
        }

        .filters input,
        .filters select {

            width: 100%;

            padding: 11px 12px;

            border:
                1px solid #d1d5db;

            border-radius: 7px;

            font-size: 14px;

            background: white;
        }

        .filter-buttons {

            display: flex;

            gap: 10px;

            flex-wrap: wrap;

            margin-bottom: 20px;
        }

        button,
        .btn {

            border: none;

            border-radius: 7px;

            padding: 10px 15px;

            cursor: pointer;

            text-decoration: none;

            font-size: 14px;

            display: inline-block;
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

        .btn-dark {
            background: #374151;

            color: white;
        }

        .btn-warning {
            background: #d97706;

            color: white;
        }

        .btn-info {
            background: #0891b2;

            color: white;
        }

        table {

            width: 100%;

            border-collapse: collapse;
        }

        th,
        td {

            padding: 13px;

            border-bottom:
                1px solid #e5e7eb;

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

        .action-buttons {

            display: flex;

            gap: 6px;

            flex-wrap: wrap;
        }

        .grid {

            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 25px;
        }

        .top-ip {

            display: flex;

            justify-content:
                space-between;

            gap: 10px;

            padding: 11px 0;

            border-bottom:
                1px solid #e5e7eb;
        }

        .pagination {

            margin-top: 20px;
        }

        .pagination nav {

            display: flex;

            justify-content:
                center;
        }

        .pagination a,
        .pagination span {

            display: inline-block;

            padding: 8px 12px;

            margin: 2px;

            border:
                1px solid #ddd;

            border-radius: 5px;

            text-decoration: none;

            color: #374151;
        }

        .pagination .active span {

            background: #667eea;

            color: white;

            border-color: #667eea;
        }

        .back-link {

            display: inline-block;

            margin-top: 10px;

            color: white;

            text-decoration: none;

            font-size: 14px;
        }

        .export-box {

            display: flex;

            gap: 10px;

            flex-wrap: wrap;

            margin-bottom: 20px;
        }

        .danger-zone {

            border:
                1px solid #fecaca;

            background: #fff7f7;
        }

        .empty {

            text-align: center;

            padding: 25px;

            color: #6b7280;
        }

        .small-text {

            font-size: 12px;

            color: #6b7280;
        }

        .blocked-search {

            display: flex;

            gap: 10px;

            margin-bottom: 20px;
        }

        .blocked-search input {

            flex: 1;

            padding: 11px 14px;

            border:
                1px solid #d1d5db;

            border-radius: 7px;

            font-size: 14px;
        }

        .sort-links {

            margin-bottom: 15px;

            display: flex;

            gap: 8px;

            flex-wrap: wrap;
        }

        .sort-links a {

            text-decoration: none;

            padding: 7px 10px;

            border-radius: 6px;

            background: #f3f4f6;

            color: #374151;

            font-size: 12px;
        }

        @media (max-width: 1000px) {

            .cards {

                grid-template-columns:
                    repeat(2, 1fr);
            }

            .filters {

                grid-template-columns:
                    1fr 1fr;
            }

        }

        @media (max-width: 700px) {

            .cards {

                grid-template-columns:
                    1fr;
            }

            .grid {

                grid-template-columns:
                    1fr;
            }

            .filters {

                grid-template-columns:
                    1fr;
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

            .blocked-search {

                flex-direction: column;
            }

        }
    </style>

</head>

<body>

    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <h1 style="margin: 0 0 5px; font-size: 28px;">🛡️ Honeypot Security Dashboard</h1>
                <p style="margin: 0; opacity: 0.9;">Monitor spam attempts, suspicious IP addresses, and auto-blacklisted bots.</p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('spam.dashboard') }}" style="padding: 9px 16px; background: #ffffff; color: #4f46e5; font-weight: bold; border-radius: 6px; text-decoration: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">🛡️ Overview</a>
                <a href="{{ route('spam.simulator') }}" style="padding: 9px 16px; background: rgba(255,255,255,0.2); color: white; font-weight: 500; border-radius: 6px; text-decoration: none;">⚡ Bot Simulator</a>
                <a href="{{ route('spam.analytics') }}" style="padding: 9px 16px; background: rgba(255,255,255,0.2); color: white; font-weight: 500; border-radius: 6px; text-decoration: none;">📊 Threat Analytics</a>
                <a href="{{ url('/contact') }}" style="padding: 9px 16px; background: rgba(255,255,255,0.1); color: white; font-weight: 500; border-radius: 6px; text-decoration: none;">← Contact Form</a>
            </div>
        </div>
    </div>


    <div class="container">

        {{-- Success Message --}}

        @if(session('success'))

        <div class="alert success">

            {{ session('success') }}

        </div>

        @endif


        {{-- Error Message --}}

        @if(session('error'))

        <div class="alert error">

            {{ session('error') }}

        </div>

        @endif


        {{-- Statistics --}}

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
                    {{ $blockedIpsCount }}
                </div>

            </div>

        </div>


        {{-- Top IPs + Recent Activity --}}

        <div class="grid">

            <div class="section">

                <h2>
                    🔥 Top Spam IP Addresses
                </h2>

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

                <p>
                    No spam attempts recorded yet.
                </p>

                @endforelse

            </div>


            <div class="section">

                <h2>
                    🕒 Recent Spam Activity
                </h2>

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

                <p>
                    No recent spam activity.
                </p>

                @endforelse

            </div>

        </div>


        {{-- Spam History --}}

        <div class="section">

            <h2>
                📋 Spam Attempt History
            </h2>


            {{-- Filters --}}

            <form
                method="GET"
                action="{{ route('spam.dashboard') }}">

                <div class="filters">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search IP, reason, user agent or route...">


                    <select name="reason">

                        <option value="">
                            All Reasons
                        </option>

                        @foreach($reasons as $item)

                        <option
                            value="{{ $item }}"
                            @selected($reason===$item)>
                            {{ $item }}
                        </option>

                        @endforeach

                    </select>


                    <input
                        type="date"
                        name="date_from"
                        value="{{ $dateFrom }}">


                    <input
                        type="date"
                        name="date_to"
                        value="{{ $dateTo }}">

                </div>


                <div class="filter-buttons">

                    <button
                        type="submit"
                        class="btn btn-primary">
                        🔎 Apply Filters
                    </button>


                    <a
                        href="{{ route('spam.dashboard') }}"
                        class="btn btn-secondary">
                        ✖ Clear Filters
                    </a>

                </div>

            </form>


            {{-- Sorting --}}

            <div class="sort-links">

                <strong>
                    Sort:
                </strong>

                <a
                    href="{{ request()->fullUrlWithQuery([
                    'sort' => 'id',
                    'direction' => 'asc'
                ]) }}">
                    ID ↑
                </a>

                <a
                    href="{{ request()->fullUrlWithQuery([
                    'sort' => 'id',
                    'direction' => 'desc'
                ]) }}">
                    ID ↓
                </a>

                <a
                    href="{{ request()->fullUrlWithQuery([
                    'sort' => 'ip_address',
                    'direction' => 'asc'
                ]) }}">
                    IP ↑
                </a>

                <a
                    href="{{ request()->fullUrlWithQuery([
                    'sort' => 'ip_address',
                    'direction' => 'desc'
                ]) }}">
                    IP ↓
                </a>

                <a
                    href="{{ request()->fullUrlWithQuery([
                    'sort' => 'attempted_at',
                    'direction' => 'asc'
                ]) }}">
                    Date ↑
                </a>

                <a
                    href="{{ request()->fullUrlWithQuery([
                    'sort' => 'attempted_at',
                    'direction' => 'desc'
                ]) }}">
                    Date ↓
                </a>

            </div>


            {{-- Export Buttons --}}

            <div class="export-box">

                <a
                    href="{{ route('spam.export.csv', request()->query()) }}"
                    class="btn btn-success">
                    📥 Export CSV
                </a>


                <a
                    href="{{ route('spam.export.json', request()->query()) }}"
                    class="btn btn-info">
                    📄 Export JSON
                </a>

            </div>


            {{-- Spam Table --}}

            <div style="overflow-x:auto;">

                <table>

                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                IP Address
                            </th>

                            <th>
                                Reason
                            </th>

                            <th>
                                Route
                            </th>

                            <th>
                                Method
                            </th>

                            <th>
                                User Agent
                            </th>

                            <th>
                                Date & Time
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($attempts as $attempt)

                        <tr>

                            <td>
                                <strong>
                                    {{ $attempt->id }}
                                </strong>
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


                            <td>
                                {{ $attempt->request_method }}
                            </td>


                            <td
                                style="
                                max-width:250px;
                                white-space:normal;
                            ">
                                {{ $attempt->user_agent ?: 'Unknown' }}
                            </td>


                            <td>

                                {{ $attempt->attempted_at?->format(
                                'd M Y H:i:s'
                            ) }}

                            </td>


                            <td>

                                <div class="action-buttons">

                                    {{-- Block IP --}}

                                    <form
                                        method="POST"
                                        action="{{ route(
                                        'spam.block-ip'
                                    ) }}"
                                        class="ip-form"
                                        onsubmit="
                                        return confirm(
                                            'Block this IP address?'
                                        );
                                    ">

                                        @csrf

                                        <input
                                            type="hidden"
                                            name="ip_address"
                                            value="{{ $attempt->ip_address }}">

                                        <button
                                            type="submit"
                                            class="btn btn-danger">
                                            🚫 Block
                                        </button>

                                    </form>


                                    {{-- Delete Attempt --}}

                                    <form
                                        method="POST"
                                        action="{{ route(
                                        'spam.delete-attempt',
                                        $attempt
                                    ) }}"
                                        onsubmit="
                                        return confirm(
                                            'Delete this spam attempt?'
                                        );
                                    ">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-warning">
                                            🗑 Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="8"
                                class="empty">
                                No spam attempts found.
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Spam Pagination --}}

            <div class="pagination">

                {{ $attempts->links() }}

            </div>


            <div class="small-text">

                Showing
                {{ $attempts->firstItem() ?? 0 }}
                -
                {{ $attempts->lastItem() ?? 0 }}
                of
                {{ $attempts->total() }}
                filtered spam attempts.
                <br>
                Maximum 5 records per page.

            </div>

        </div>


        {{-- Clear History --}}

        <div class="section danger-zone">

            <h2>
                ⚠️ Danger Zone
            </h2>

            <p>
                Permanently delete all recorded spam attempts.
            </p>

            <form
                method="POST"
                action="{{ route('spam.clear-history') }}"
                onsubmit="
                return confirm(
                    'Are you sure you want to delete ALL spam history? This cannot be undone.'
                );
            ">

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-danger">
                    🧹 Clear All Spam History
                </button>

            </form>

        </div>


        {{-- Blocked IP Management --}}

        <div class="section">

            <h2>
                🚫 Blocked IP Management
            </h2>

            <p>
                Blocked IP addresses cannot submit the contact form.
            </p>


            {{-- Blocked IP Search --}}

            <form
                method="GET"
                action="{{ route('spam.dashboard') }}"
                class="blocked-search">

                <input
                    type="text"
                    name="blocked_search"
                    value="{{ $blockedSearch }}"
                    placeholder="Search blocked IP or reason...">

                <button
                    type="submit"
                    class="btn btn-primary">
                    🔎 Search
                </button>


                @if($blockedSearch)

                <a
                    href="{{ route('spam.dashboard') }}"
                    class="btn btn-secondary">
                    Clear
                </a>

                @endif

            </form>


            @if($blockedIpList->count())

            <div style="overflow-x:auto;">

                <table>

                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                IP Address
                            </th>

                            <th>
                                Reason
                            </th>

                            <th>
                                Blocked At
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($blockedIpList as $blockedIp)

                        <tr>

                            <td>
                                {{ $blockedIp->id }}
                            </td>


                            <td>

                                <strong>
                                    {{ $blockedIp->ip_address }}
                                </strong>

                            </td>


                            <td>
                                {{ $blockedIp->reason }}
                            </td>


                            <td>

                                {{ $blockedIp->blocked_at?->format(
                                    'd M Y H:i:s'
                                ) }}

                            </td>


                            <td>

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'spam.unblock-ip',
                                        $blockedIp
                                    ) }}"
                                    onsubmit="
                                        return confirm(
                                            'Unblock this IP address?'
                                        );
                                    ">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-success">
                                        🔓 Unblock
                                    </button>

                                </form>

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Blocked IP Pagination --}}

            <div class="pagination">

                {{ $blockedIpList->links() }}

            </div>


            <div class="small-text">

                Showing
                {{ $blockedIpList->firstItem() ?? 0 }}
                -
                {{ $blockedIpList->lastItem() ?? 0 }}
                of
                {{ $blockedIpList->total() }}
                blocked IPs.
                <br>
                Maximum 5 blocked IPs per page.

            </div>

            @else

            <div class="empty">

                No IP addresses are currently blocked.

            </div>

            @endif

        </div>

    </div>

</body>

</html>