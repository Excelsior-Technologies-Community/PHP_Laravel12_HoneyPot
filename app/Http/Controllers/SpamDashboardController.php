<?php

namespace App\Http\Controllers;

use App\Models\BlockedIp;
use App\Models\SpamAttempt;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SpamDashboardController extends Controller
{
    /**
     * Display spam security dashboard.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $reason = $request->input('reason');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $sort = $request->input('sort', 'attempted_at');
        $direction = $request->input('direction', 'desc');

        /*
        |--------------------------------------------------------------------------
        | Allowed Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'id',
            'ip_address',
            'reason',
            'attempted_at',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'attempted_at';
        }

        $direction = strtolower($direction);

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        /*
        |--------------------------------------------------------------------------
        | Spam History Query
        |--------------------------------------------------------------------------
        */

        $query = SpamAttempt::query();

        /*
        | Search
        */

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhere('user_agent', 'like', "%{$search}%")
                    ->orWhere('route', 'like', "%{$search}%");
            });
        }

        /*
        | Reason Filter
        */

        if ($reason) {
            $query->where('reason', $reason);
        }

        /*
        | Date From
        */

        if ($dateFrom) {
            $query->whereDate('attempted_at', '>=', $dateFrom);
        }

        /*
        | Date To
        */

        if ($dateTo) {
            $query->whereDate('attempted_at', '<=', $dateTo);
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        |
        | Exactly 5 records per page.
        |
        */

        $attempts = $query
            ->orderBy($sort, $direction)
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalSpam = SpamAttempt::count();

        $todaySpam = SpamAttempt::whereDate(
            'attempted_at',
            today()
        )->count();

        $uniqueIps = SpamAttempt::distinct(
            'ip_address'
        )->count('ip_address');

        $blockedIpsCount = BlockedIp::count();

        /*
        |--------------------------------------------------------------------------
        | Available Reasons
        |--------------------------------------------------------------------------
        */

        $reasons = SpamAttempt::query()
            ->whereNotNull('reason')
            ->where('reason', '!=', '')
            ->distinct()
            ->orderBy('reason')
            ->pluck('reason');

        /*
        |--------------------------------------------------------------------------
        | Top Spam IPs
        |--------------------------------------------------------------------------
        */

        $topIps = SpamAttempt::selectRaw(
                'ip_address, COUNT(*) as attempts'
            )
            ->groupBy('ip_address')
            ->orderByDesc('attempts')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Activity
        |--------------------------------------------------------------------------
        */

        $recentAttempts = SpamAttempt::latest(
            'attempted_at'
        )
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Blocked IP Search
        |--------------------------------------------------------------------------
        */

        $blockedSearch = $request->input('blocked_search');

        $blockedQuery = BlockedIp::query();

        if ($blockedSearch) {
            $blockedQuery->where(function ($q) use ($blockedSearch) {
                $q->where(
                    'ip_address',
                    'like',
                    "%{$blockedSearch}%"
                )->orWhere(
                    'reason',
                    'like',
                    "%{$blockedSearch}%"
                );
            });
        }

        /*
        | Exactly 5 blocked IPs per page.
        */

        $blockedIpList = $blockedQuery
            ->latest('blocked_at')
            ->paginate(5, ['*'], 'blocked_page')
            ->withQueryString();

        return view('spam.dashboard', compact(
            'attempts',
            'totalSpam',
            'todaySpam',
            'uniqueIps',
            'blockedIpsCount',
            'topIps',
            'recentAttempts',
            'search',
            'reason',
            'dateFrom',
            'dateTo',
            'sort',
            'direction',
            'reasons',
            'blockedIpList',
            'blockedSearch'
        ));
    }

    /**
     * Block an IP address.
     */
    public function blockIp(Request $request)
    {
        $request->validate([
            'ip_address' => [
                'required',
                'ip',
            ],
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

    /**
     * Unblock an IP address.
     */
    public function unblockIp(BlockedIp $blockedIp)
    {
        $blockedIp->delete();

        return back()->with(
            'success',
            'IP address unblocked successfully.'
        );
    }

    /**
     * Delete one spam attempt.
     */
    public function deleteAttempt(SpamAttempt $spamAttempt)
    {
        $spamAttempt->delete();

        return back()->with(
            'success',
            'Spam attempt deleted successfully.'
        );
    }

    /**
     * Delete all spam history.
     */
    public function clearHistory()
    {
        SpamAttempt::query()->delete();

        return back()->with(
            'success',
            'All spam history has been cleared successfully.'
        );
    }

    /**
     * Export filtered spam history as CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = $this->filteredQuery($request);

        $filename = 'spam-history-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(
            function () use ($query) {
                $handle = fopen('php://output', 'w');

                /*
                |--------------------------------------------------------------------------
                | CSV Header
                |--------------------------------------------------------------------------
                */

                fputcsv($handle, [
                    'ID',
                    'IP Address',
                    'Reason',
                    'Route',
                    'Request Method',
                    'User Agent',
                    'Attempted At',
                ]);

                /*
                |--------------------------------------------------------------------------
                | CSV Rows
                |--------------------------------------------------------------------------
                */

                $query
                    ->orderBy('id', 'asc')
                    ->chunk(500, function ($attempts) use ($handle) {
                        foreach ($attempts as $attempt) {
                            fputcsv($handle, [
                                $attempt->id,
                                $attempt->ip_address,
                                $attempt->reason,
                                $attempt->route,
                                $attempt->request_method,
                                $attempt->user_agent,
                                $attempt->attempted_at?->format(
                                    'Y-m-d H:i:s'
                                ),
                            ]);
                        }
                    });

                fclose($handle);
            },
            $filename,
            [
                'Content-Type' => 'text/csv',
            ]
        );
    }

    /**
     * Export filtered spam history as JSON.
     */
    public function exportJson(Request $request)
    {
        $attempts = $this->filteredQuery($request)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'exported_at' => now()->toDateTimeString(),
            'total_records' => $attempts->count(),
            'records' => $attempts,
        ]);
    }

    /**
     * Build filtered spam history query.
     */
    private function filteredQuery(Request $request)
    {
        $search = $request->input('search');
        $reason = $request->input('reason');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = SpamAttempt::query();

        /*
        | Search
        */

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhere('user_agent', 'like', "%{$search}%")
                    ->orWhere('route', 'like', "%{$search}%");
            });
        }

        /*
        | Reason
        */

        if ($reason) {
            $query->where('reason', $reason);
        }

        /*
        | Date From
        */

        if ($dateFrom) {
            $query->whereDate(
                'attempted_at',
                '>=',
                $dateFrom
            );
        }

        /*
        | Date To
        */

        if ($dateTo) {
            $query->whereDate(
                'attempted_at',
                '<=',
                $dateTo
            );
        }

        return $query;
    }
}