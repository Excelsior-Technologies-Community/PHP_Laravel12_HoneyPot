<?php

namespace App\Http\Controllers;

use App\Models\BlockedIp;
use App\Models\SpamAttempt;
use Illuminate\Http\Request;

class SpamDashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = SpamAttempt::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        $attempts = $query
            ->latest('attempted_at')
            ->paginate(10)
            ->withQueryString();

        $totalSpam = SpamAttempt::count();

        $todaySpam = SpamAttempt::whereDate(
            'attempted_at',
            today()
        )->count();

        $uniqueIps = SpamAttempt::distinct('ip_address')->count('ip_address');

        $blockedIps = BlockedIp::count();

        $topIps = SpamAttempt::selectRaw(
                'ip_address, COUNT(*) as attempts'
            )
            ->groupBy('ip_address')
            ->orderByDesc('attempts')
            ->limit(10)
            ->get();

        $recentAttempts = SpamAttempt::latest('attempted_at')
            ->limit(5)
            ->get();

        return view('spam.dashboard', compact(
            'attempts',
            'totalSpam',
            'todaySpam',
            'uniqueIps',
            'blockedIps',
            'topIps',
            'recentAttempts',
            'search'
        ));
    }

    public function blockIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
        ]);

        BlockedIp::updateOrCreate(
            [
                'ip_address' => $request->ip_address,
            ],
            [
                'reason' => 'Blocked from spam monitoring dashboard',
                'blocked_at' => now(),
            ]
        );

        return back()->with(
            'success',
            'IP address blocked successfully.'
        );
    }

    public function unblockIp(BlockedIp $blockedIp)
    {
        $blockedIp->delete();

        return back()->with(
            'success',
            'IP address unblocked successfully.'
        );
    }
}