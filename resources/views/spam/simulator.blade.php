<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bot Attack Simulation & Chaos Test Playground</title>
    <style>
        * { box-sizing: border-box; }
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
        .container {
            max-width: 1350px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .alert {
            padding: 13px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }
        .card h3 {
            margin-top: 0;
            margin-bottom: 8px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 18px;
        }
        .btn {
            display: inline-block;
            width: 100%;
            text-align: center;
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-primary { background: #4f46e5; color: white; }
        .btn-primary:hover { background: #4338ca; }
        .btn-warning { background: #d97706; color: white; }
        .btn-warning:hover { background: #b45309; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-purple { background: #9333ea; color: white; }
        .btn-purple:hover { background: #7e22ce; }
        
        .section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.08);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        th { background: #f9fafb; font-weight: 600; color: #4b5563; }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #e0e7ff; color: #3730a3; }
    </style>
</head>
<body>

    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <h1 style="margin: 0 0 5px; font-size: 28px;">⚡ Bot Attack Simulator & Chaos Playground</h1>
                <p style="margin: 0; opacity: 0.9;">Simulate malicious bot traffic, rapid form submissions, and test auto-blacklisting policies.</p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('spam.dashboard') }}" style="padding: 9px 16px; background: rgba(255,255,255,0.2); color: white; font-weight: 500; border-radius: 6px; text-decoration: none;">🛡️ Overview</a>
                <a href="{{ route('spam.simulator') }}" style="padding: 9px 16px; background: #ffffff; color: #4f46e5; font-weight: bold; border-radius: 6px; text-decoration: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">⚡ Bot Simulator</a>
                <a href="{{ route('spam.analytics') }}" style="padding: 9px 16px; background: rgba(255,255,255,0.2); color: white; font-weight: 500; border-radius: 6px; text-decoration: none;">📊 Threat Analytics</a>
                <a href="{{ url('/contact') }}" style="padding: 9px 16px; background: rgba(255,255,255,0.1); color: white; font-weight: 500; border-radius: 6px; text-decoration: none;">← Contact Form</a>
            </div>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert success">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert error">
                ❌ {{ session('error') }}
            </div>
        @endif

        <div class="grid">
            <!-- Fast Form Submission Bot -->
            <div class="card">
                <h3>⚡ Fast Form Submit Bot</h3>
                <p>Simulates a bot submitting the form in less than 5 seconds to test minimum submission duration middleware.</p>
                <form action="{{ route('spam.simulator.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="scenario" value="fast_submission">
                    <button type="submit" class="btn btn-warning">Launch Fast Submission Bot</button>
                </form>
            </div>

            <!-- Hidden Honeypot Field Filler Bot -->
            <div class="card">
                <h3>🪤 Hidden Field Trap Bot</h3>
                <p>Simulates a bot filling the invisible honeypot trap field (<code>my_name</code>) to test Spatie Honeypot detection.</p>
                <form action="{{ route('spam.simulator.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="scenario" value="hidden_trap">
                    <button type="submit" class="btn btn-primary">Launch Hidden Trap Bot</button>
                </form>
            </div>

            <!-- Automated Spambot Batch -->
            <div class="card">
                <h3>🤖 Rapid Spambot Batch (3x)</h3>
                <p>Simulates a high-velocity bot sending 3 consecutive spam attempts to trigger the <b>Auto-Blacklist Policy</b>.</p>
                <form action="{{ route('spam.simulator.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="scenario" value="bot_batch">
                    <input type="hidden" name="count" value="3">
                    <button type="submit" class="btn btn-danger">Trigger Auto-Blacklist Batch (3x)</button>
                </form>
            </div>

            <!-- Custom Customizer -->
            <div class="card">
                <h3>🎯 Custom Chaos Tester</h3>
                <p>Specify a custom IP address or custom scenario options for fine-grained honeypot security testing.</p>
                <form action="{{ route('spam.simulator.run') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 10px;">
                        <input type="text" name="ip_address" placeholder="Target IP (e.g. 192.168.1.99)" style="margin-bottom: 8px;">
                        <select name="scenario">
                            <option value="fast_submission">Fast Submission (&lt; 5s)</option>
                            <option value="hidden_trap">Hidden Field Trap</option>
                            <option value="bot_batch">Automated Spambot</option>
                            <option value="blocked_ip">Blocked IP Attempt</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-purple">Run Custom Attack Test</button>
                </form>
            </div>
        </div>

        <div class="section">
            <h2>📜 Recent Simulated Attacks & Interceptions</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>IP Address</th>
                        <th>Interception Reason</th>
                        <th>User Agent</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSimulations as $attempt)
                        <tr>
                            <td>#{{ $attempt->id }}</td>
                            <td><code>{{ $attempt->ip_address }}</code></td>
                            <td>
                                @if(str_contains($attempt->reason, 'Honeypot'))
                                    <span class="badge badge-danger">{{ $attempt->reason }}</span>
                                @elseif(str_contains($attempt->reason, 'minimum'))
                                    <span class="badge badge-warning">{{ $attempt->reason }}</span>
                                @else
                                    <span class="badge badge-info">{{ $attempt->reason }}</span>
                                @endif
                            </td>
                            <td><span style="font-size: 12px; color: #4b5563;">{{ $attempt->user_agent }}</span></td>
                            <td>{{ $attempt->attempted_at ? $attempt->attempted_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #6b7280; padding: 20px;">
                                No recent simulated attacks recorded yet. Click a button above to run your first simulation!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
