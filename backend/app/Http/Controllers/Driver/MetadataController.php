<?php

namespace App\Http\Controllers\Driver;

use Ajthinking\LaravelPostgis\Geometries\Point;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MetadataController extends Controller
{
    public function updateStatus()
    {
        $driver = Driver::with('metadata')->find(auth()->id());

        if ($driver->metadata->status == 'BUSY') {
            return response()->json([
                'message' => 'Você não pode alterar seu status no momento.',
            ], 403);
        }

        $driver->metadata->update([
            'status' => $driver->metadata->status == 'OFFLINE' ? 'ONLINE' : 'OFFLINE',
        ]);

        return response()->json([
            'status' => $driver->metadata->status,
        ]);
    }

    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'coordinates' => 'required|array|size:2',
            'battery_level' => 'nullable',
        ]);

        $createdAt = $request->query('created_at', null);

        if (! blank($createdAt)) {
            Log::debug("DRIVER METADATA - UPDATE LOCATION");
            Log::debug($createdAt);
        }

        $driver = Driver::find(auth()->id());

        $coordinates = $validated['coordinates'];

        $driver->metadata->update([
            'location' => new Point($coordinates[0], $coordinates[1]),
            'battery_level' => optional($validated)['battery_level'],
        ]);

        return response()->noContent();
    }

    public function updateDevice(Request $request)
    {
        $validated = $request->validate([
            'device' => 'nullable',
            'version' => 'nullable',
            'build' => 'nullable',
        ]);

        $driver = Driver::find(auth()->id());

        $driver->metadata->update([
            'device' => optional($validated)['device'],
            'version' => optional($validated)['version'],
            'build' => optional($validated)['build'],
        ]);

        return response()->noContent();
    }
}
