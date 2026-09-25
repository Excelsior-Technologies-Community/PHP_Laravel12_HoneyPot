<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anti-Spam Threat Analytics & Intelligence</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
        .card-title {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: 500;
        }
        .card-value {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
        }
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        @media (max-width: 900px) {
            .charts-grid { grid-template-columns: 1fr; }
        }
        .section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.08);
            border: 1px solid #e5e7eb;
        }
        .section h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
            color: #111827;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        .badge-success { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>

    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <h1 style="margin: 0 0 5px; font-size: 28px;">📊 Anti-Spam Threat Analytics</h1>
                <p style="margin: 0; opacity: 0.9;">Real-time bot attack patterns, honeypot interception metrics, and IP security insights.</p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('spam.dashboard') }}" style="padding: 9px 16px; background: rgba(255,255,255,0.2); color: white; font-weight: 500; border-radius: 6px; text-decoration: none;">🛡️ Overview</a>
                <a href="{{ route('spam.simulator') }}" style="padding: 9px 16px; background: rgba(255,255,255,0.2); color: white; font-weight: 500; border-radius: 6px; text-decoration: none;">⚡ Bot Simulator</a>
                <a href="{{ route('spam.analytics') }}" style="padding: 9px 16px; background: #ffffff; color: #4f46e5; font-weight: bold; border-radius: 6px; text-decoration: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">📊 Threat Analytics</a>
                <a href="{{ url('/contact') }}" style="padding: 9px 16px; background: rgba(255,255,255,0.1); color: white; font-weight: 500; border-radius: 6px; text-decoration: none;">← Contact Form</a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Summary Cards -->
        <div class="cards">
            <div class="card">
                <div class="card-title">Total Intercepted Attempts</div>
                <div class="card-value">{{ number_format($data['summary']['total_attempts']) }}</div>
            </div>
            <div class="card">
                <div class="card-title">Attempts Today</div>
                <div class="card-value" style="color: #4f46e5;">{{ number_format($data['summary']['today_attempts']) }}</div>
            </div>
            <div class="card">
                <div class="card-title">Auto-Blacklisted IPs</div>
                <div class="card-value" style="color: #dc2626;">{{ number_format($data['summary']['auto_blocked']) }}</div>
            </div>
            <div class="card">
                <div class="card-title">Total Blocked IPs</div>
                <div class="card-value" style="color: #d97706;">{{ number_format($data['summary']['total_blocked']) }}</div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="charts-grid">
            <div class="section">
                <h2>📈 Attack Trends (Last 7 Days)</h2>
                <div style="height: 300px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <div class="section">
                <h2>🪤 Attack Reason Breakdown</h2>
                <div style="height: 300px; display: flex; justify-content: center; align-items: center;">
                    <canvas id="reasonChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="charts-grid">
            <div class="section">
                <h2>🎯 Top Offending IP Addresses</h2>
                <table>
                    <thead>
                        <tr>
                            <th>IP Address</th>
                            <th>Total Attempts</th>
                            <th>Policy Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['ip_leaderboard'] as $item)
                            <tr>
                                <td><code>{{ $item['ip'] }}</code></td>
                                <td><b>{{ $item['attempts'] }}</b> attempt(s)</td>
                                <td>
                                    @if($item['is_blocked'])
                                        <span class="badge badge-danger">🔒 Auto-Blacklisted</span>
                                    @else
                                        <span class="badge badge-success">🟢 Active Monitoring</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; color: #6b7280; padding: 15px;">No IP threat data available yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h2>🤖 Top Bot User-Agents</h2>
                <table>
                    <thead>
                        <tr>
                            <th>User-Agent</th>
                            <th>Attempts</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['user_agents'] as $agent)
                            <tr>
                                <td><span style="font-size: 13px; font-family: monospace;">{{ Str::limit($agent['agent'], 35) }}</span></td>
                                <td><b>{{ $agent['count'] }}</b></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="text-align: center; color: #6b7280; padding: 15px;">No User-Agent data recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Trend Line Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($data['trend_labels']) !!},
                    datasets: [{
                        label: 'Interception Count',
                        data: {!! json_encode($data['trend_data']) !!},
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 3,
                        pointBackgroundColor: '#4f46e5',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });

            // Reason Doughnut Chart
            const reasonCtx = document.getElementById('reasonChart').getContext('2d');
            const reasonLabels = {!! json_encode(array_column($data['reasons'], 'reason')) !!};
            const reasonData = {!! json_encode(array_column($data['reasons'], 'count')) !!};

            new Chart(reasonCtx, {
                type: 'doughnut',
                data: {
                    labels: reasonLabels,
                    datasets: [{
                        data: reasonData,
                        backgroundColor: ['#dc2626', '#d97706', '#2563eb', '#9333ea', '#059669'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12 } }
                    }
                }
            });
        });
    </script>
</body>
</html>
