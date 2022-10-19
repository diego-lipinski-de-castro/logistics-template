<?php

namespace App\Http\Controllers\Developer;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Driver;
use App\Models\Route;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(): \Inertia\Response
    {
        $WEEK_MAP = [
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
            0 => 'Domingo',
        ];

        // $prev = [now()->startOfWeek()->subWeek(), now()->startOfWeek()->subWeek()->endOfWeek()];
        // $current = [now()->startOfWeek(), now()->endOfWeek()];

        $prev = [now()->subMonths(2)->startOfWeek()->subWeek(), now()->subMonths(2)->startOfWeek()->subWeek()->endOfWeek()];
        $current = [now()->subMonths(2)->startOfWeek(), now()->subMonths(2)->endOfWeek()];
        
        $prevs = Delivery::ofStatus('COMPLETED')->whereBetween('created_at', $prev)->get();
        $nexts = Delivery::ofStatus('COMPLETED')->whereBetween('created_at', $current)->get();

        $pprevs = $prevs
            ->transform(function ($item) {
                $item->dayOfWeek = $item->created_at->dayOfWeek;

                return $item;
            })
            ->groupBy(fn ($item) => $item->dayOfWeek)
            ->sortKeys()
            ->transform(function ($item) {
                $days = $item->groupBy(fn ($i) => $i->created_at->format('d/m/Y'))->count();

                return (object) [
                    'count' => $item->count(),
                    'avg' => round($item->count() / $days, 2),
                ];
            })
            ->map(function ($item, $key) use ($WEEK_MAP) {
                return (object) [
                    'weekday' => $WEEK_MAP[$key],
                    'average' => $item->avg,
                    'count' => $item->count,
                ];
            });
        
        $nnexts = $nexts
            ->transform(function ($item) {
                $item->dayOfWeek = $item->created_at->dayOfWeek;

                return $item;
            })
            ->groupBy(fn ($item) => $item->dayOfWeek)
            ->sortKeys()
            ->transform(function ($item) {
                $days = $item->groupBy(fn ($i) => $i->created_at->format('d/m/Y'))->count();

                return (object) [
                    'count' => $item->count(),
                    'avg' => round($item->count() / $days, 2),
                ];
            })
            ->map(function ($item, $key) use ($WEEK_MAP) {
                return (object) [
                    'weekday' => $WEEK_MAP[$key],
                    'average' => $item->avg,
                    'count' => $item->count,
                ];
            });
                
        $revenue_percents = [];
        $load_percents = [];

        foreach ($WEEK_MAP as $key => $dayOfWeek) {
            $revenue_percents[$dayOfWeek] = round((1 - $pprevs[$key]->average / $nnexts[$key]->average) * 100, 1);
            $load_percents[$dayOfWeek] = round((1 - $pprevs[$key]->count / $nnexts[$key]->count) * 100, 1);
        }
        
        // $prev_paid = $prevs->sum('paid');
        $prev_charged = $prevs->sum('charged');

        // $next_paid = $nexts->sum('paid');
        $next_charged = $nexts->sum('charged');

        $revenue_percent = round((1 - $prev_charged / $next_charged) * 100, 1);
        $load_percent = round((1 - $prevs->count() / $nexts->count()) * 100, 1);

        // $routes = Route::take(10)->get('route')->pluck('route');

        $stats = [
            [
                'label' => 'Revenue',
                'stat' => Helper::toMoney($next_charged),
                'change' => $revenue_percent . '%',
                'changeType' => $revenue_percent > 0 ? 'increase' : 'decrease',
                'line' => [
                    'labels' => array_keys($revenue_percents),
                    'points' => array_values($revenue_percents),
                ],
                'percent' => $revenue_percent,
            ],
            [
                'label' => 'Load amount',
                'stat' => $nexts->count() . ' deliveries',
                'change' => $load_percent . '%',
                'changeType' => $load_percent > 0 ? 'increase' : 'decrease',
                'line' => [
                    'labels' => array_keys($load_percents),
                    'points' => array_values($load_percents),
                ],
                'percent' => $load_percent,
            ],
        ];

        $prev_drivers = Driver::withCount([
            'deliveries' => function ($query) use ($prev) {
                $query->ofStatus('COMPLETED')->whereBetween('created_at', $prev);
            },
        ])->orderByDesc('deliveries_count')->take(5)->get()->transform(function ($item, $key) {
            return (object) [
                'index' => $key,
                'id' => $item->id,
                'full_name' => $item->full_name,
                'deliveries_count' => $item->deliveries_count,
            ];
        });

        $next_drivers = Driver::withCount([
            'deliveries' => function ($query) use ($current) {
                $query->ofStatus('COMPLETED')->whereBetween('created_at', $current);
            },
        ])->orderByDesc('deliveries_count')->take(5)->get()->transform(function ($item, $key) {
            return (object) [
                'index' => $key,
                'id' => $item->id,
                'full_name' => $item->full_name,
                'deliveries_count' => $item->deliveries_count,
            ];
        });

        $next_drivers->transform(function ($item, $key) use ($prev_drivers) {
            $iitem = $prev_drivers->firstWhere('id', $item->id);

            if (is_null($iitem)) {
                $item->position = 'up';
            } else {
                if ($item->index == $iitem->index) {
                    $item->position = 'same';
                } elseif ($item->index < $iitem->index) {
                    $item->position = 'up';
                } else {
                    $item->position = 'down';
                }
            }

            return $item;
        });

        $prev_users = User::withCount([
            'deliveries' => function ($query) use ($prev) {
                $query->ofStatus('COMPLETED')->whereBetween('created_at', $prev);
            },
        ])->orderByDesc('deliveries_count')->take(5)->get()->transform(function ($item, $key) {
            return (object) [
                'index' => $key,
                'id' => $item->id,
                'name' => $item->name,
                'deliveries_count' => $item->deliveries_count,
            ];
        });

        $next_users = User::withCount([
            'deliveries' => function ($query) use ($current) {
                $query->ofStatus('COMPLETED')->whereBetween('created_at', $current);
            },
        ])->orderByDesc('deliveries_count')->take(5)->get()->transform(function ($item, $key) {
            return (object) [
                'index' => $key,
                'id' => $item->id,
                'name' => $item->name,
                'deliveries_count' => $item->deliveries_count,
            ];
        });

        $next_users->transform(function ($item, $key) use ($prev_users) {
            $iitem = $prev_users->firstWhere('id', $item->id);

            if (is_null($iitem)) {
                $item->position = 'up';
            } else {
                if ($item->index == $iitem->index) {
                    $item->position = 'same';
                } elseif ($item->index < $iitem->index) {
                    $item->position = 'up';
                } else {
                    $item->position = 'down';
                }
            }

            return $item;
        });

        return Inertia::render('Developer/Dashboard', [
            'status' => session('status'),
            'stats' => $stats,
            'drivers' => $next_drivers,
            'users' => $next_users,
            // 'routes' => $routes,
        ]);
    }
}
