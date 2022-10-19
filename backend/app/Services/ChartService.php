<?php

namespace App\Services;

use App\Models\Delivery;

class ChartService
{
    public const WEEK_MAP = [
        1 => 'Segunda-feira',
        2 => 'Terça-feira',
        3 => 'Quarta-feira',
        4 => 'Quinta-feira',
        5 => 'Sexta-feira',
        6 => 'Sábado',
        0 => 'Domingo',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     *
     * @psalm-return \Illuminate\Database\Eloquent\Collection<array-key, \stdClass>|\Illuminate\Support\Collection<array-key, \stdClass>
     */
    public static function averageDeliveriesByDay(): \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection
    {
        return Delivery::query()
            ->select(['created_at'])
            ->orderBy('created_at')
            ->get()
            ->transform(function ($item) {
                $item->dayOfWeek = $item->created_at->dayOfWeek;

                return $item;
            })
            ->groupBy(fn ($item) => $item->dayOfWeek)
            ->sortKeys()
            ->transform(function ($item) {
                $days = $item->groupBy(fn ($i) => $i->created_at->format('d/m/Y'))->count();

                return round($item->count() / $days, 2);
            })
            ->map(function ($item, $key) {
                return (object) [
                    'weekday' => ChartService::WEEK_MAP[$key],
                    'average' => $item,
                ];
            });
    }

    /**
     * @psalm-return list<mixed>
     */
    public static function averageDeliveriesByHour(): array
    {
        $result = Delivery::query()
            ->select(['created_at'])
            ->orderBy('created_at')
            ->get()
            ->transform(function ($item) {
                $item->hour = $item->created_at->hour;

                return $item;
            })
            ->groupBy(fn ($item) => $item->hour)
            ->sortKeys()
            ->transform(function ($item) {
                $hours = $item->groupBy(fn ($i) => $i->created_at->format('d/m/Y'))->count();

                return round($item->count() / $hours, 2);
            })
            ->map(function ($item, $key) {
                return (object) [
                    'hour' => $key,
                    'average' => $item,
                ];
            })->toArray();

        return array_values($result);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     *
     * @psalm-return \Illuminate\Database\Eloquent\Collection<array-key, \stdClass>|\Illuminate\Support\Collection<array-key, \stdClass>
     */
    public static function averageDeliveriesByDayByHour(): \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection
    {
        $result = Delivery::query()
            ->select(['created_at'])
            ->orderBy('created_at')
            ->get()
            ->transform(function ($item) {
                $item->dayOfWeek = $item->created_at->dayOfWeek;
                $item->hour = $item->created_at->hour;

                return $item;
            })
            ->groupBy(['dayOfWeek', fn ($item) => $item->hour])
            ->sortKeys()
            ->map(function ($item, $key) {
                $item->transform(function ($iitem) {
                    $hours = $iitem->groupBy(fn ($i) => $i->created_at->format('d/m/Y'))->count();

                    return round($iitem->count() / $hours, 2);
                });

                return $item;
            })
            ->map(function ($item, $key) {
                return (object) [
                    'weekday' => ChartService::WEEK_MAP[$key],
                    'hours' => array_values($item->map(function ($item, $key) {
                        return (object) [
                            'hour' => $key,
                            'average' => $item,
                        ];
                    })->sortKeys()->toArray()),
                ];
            });

        return $result;
    }
}
