<?php

namespace App\Services;

use App\Models\BlockedIp;
use App\Models\SpamAttempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SpamSecurityService
{
    /**
     * Evaluate auto-blacklist policy for an IP address.
     * Blocks IP automatically if >= 3 spam attempts occur within 10 minutes.
     */
    public function evaluateAutoBlacklistPolicy(string $ipAddress): bool
    {
        $recentAttemptsCount = SpamAttempt::where('ip_address', $ipAddress)
            ->where('attempted_at', '>=', now()->subMinutes(10))
            ->count();

        if ($recentAttemptsCount >= 3) {
            $isBlocked = BlockedIp::where('ip_address', $ipAddress)->exists();

            if (!$isBlocked) {
                BlockedIp::create([
                    'ip_address' => $ipAddress,
                    'reason' => 'Auto-blacklisted: 3+ spam attempts within 10 minutes',
                    'blocked_at' => now(),
                ]);
            }

            return true;
        }

        return false;
    }

    /**
     * Run simulated bot attack scenario.
     */
    public function runSimulation(string $scenario, ?string $targetIp = null, int $count = 1): array
    {
        $ipsPool = [
            '198.51.100.42',
            '203.0.113.195',
            '192.0.2.88',
            '185.220.101.5',
            '91.240.118.172'
        ];

        $ipAddress = $targetIp ?: $ipsPool[array_rand($ipsPool)];
        $userAgents = [
            'fast_submission' => 'Mozilla/5.0 (FastBot/2.1; ChaosSimulator)',
            'hidden_trap' => 'Python-urllib/3.9 HoneypotTrappedBot',
            'bot_batch' => 'Go-http-client/1.1 RapidSpammerBot/4.0',
            'blocked_ip' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) BlockedBot/1.0',
        ];

        $reasons = [
            'fast_submission' => 'Form submitted before minimum 5-second time limit',
            'hidden_trap' => 'Honeypot spam protection triggered (hidden field filled)',
            'bot_batch' => 'Automated rapid spambot submission batch',
            'blocked_ip' => 'Request rejected because IP address is auto-blocked',
        ];

        $userAgent = $userAgents[$scenario] ?? 'Mozilla/5.0 (ChaosTester/1.0)';
        $reason = $reasons[$scenario] ?? 'Simulated Bot Attack';
        $route = 'contact';
        $method = 'POST';

        $created = [];

        for ($i = 0; $i < max(1, $count); $i++) {
            $attempt = SpamAttempt::create([
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'reason' => $reason,
                'route' => $route,
                'request_method' => $method,
                'attempted_at' => now()->subSeconds($i * 2),
            ]);

            $created[] = $attempt;
        }

        $wasBlacklisted = $this->evaluateAutoBlacklistPolicy($ipAddress);

        return [
            'success' => true,
            'scenario' => $scenario,
            'ip_address' => $ipAddress,
            'attempts_created' => count($created),
            'auto_blacklisted' => $wasBlacklisted,
            'message' => "Successfully simulated {$scenario} attack with " . count($created) . " attempt(s) from IP {$ipAddress}." . ($wasBlacklisted ? " IP is now Auto-Blacklisted!" : ""),
        ];
    }

    /**
     * Get threat analytics data for Chart.js dashboard.
     */
    public function getAnalyticsData(): array
    {
        // 1. Last 7 Days Daily Trend
        $days = collect(range(6, 0))->map(function ($dayOffset) {
            $date = Carbon::now()->subDays($dayOffset)->format('Y-m-d');
            $count = SpamAttempt::whereDate('attempted_at', $date)->count();
            return [
                'label' => Carbon::now()->subDays($dayOffset)->format('M d'),
                'count' => $count,
            ];
        });

        // 2. Attack Reasons Breakdown
        $reasons = SpamAttempt::selectRaw('reason, COUNT(*) as count')
            ->groupBy('reason')
            ->orderByDesc('count')
            ->get()
            ->map(function ($item) {
                return [
                    'reason' => $item->reason ?: 'Unknown Reason',
                    'count' => (int)$item->count,
                ];
            })->toArray();

        // 3. User-Agent Distribution
        $userAgents = SpamAttempt::selectRaw('user_agent, COUNT(*) as count')
            ->groupBy('user_agent')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'agent' => $item->user_agent ?: 'Unknown User-Agent',
                    'count' => (int)$item->count,
                ];
            })->toArray();

        // 4. IP Threat Leaderboard
        $ipLeaderboard = SpamAttempt::selectRaw('ip_address, COUNT(*) as attempts')
            ->groupBy('ip_address')
            ->orderByDesc('attempts')
            ->limit(8)
            ->get()
            ->map(function ($item) {
                $isBlocked = BlockedIp::where('ip_address', $item->ip_address)->exists();
                return [
                    'ip' => $item->ip_address,
                    'attempts' => (int)$item->attempts,
                    'is_blocked' => $isBlocked,
                ];
            })->toArray();

        // Overview Summary Statistics
        $totalAttempts = SpamAttempt::count();
        $todayAttempts = SpamAttempt::whereDate('attempted_at', today())->count();
        $autoBlockedCount = BlockedIp::where('reason', 'like', '%Auto-blacklisted%')->count();
        $totalBlocked = BlockedIp::count();

        return [
            'summary' => [
                'total_attempts' => $totalAttempts,
                'today_attempts' => $todayAttempts,
                'auto_blocked' => $autoBlockedCount,
                'total_blocked' => $totalBlocked,
            ],
            'trend_labels' => $days->pluck('label')->toArray(),
            'trend_data' => $days->pluck('count')->toArray(),
            'reasons' => $reasons,
            'user_agents' => $userAgents,
            'ip_leaderboard' => $ipLeaderboard,
        ];
    }
}
