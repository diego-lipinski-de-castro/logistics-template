<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile()
    {
        $driver = Driver::with(['metadata', 'documents', 'city'])->find(auth()->id());

        return response()->json([
            'driver' => $driver,
        ]);
    }

    public function storeDocuments(Request $request)
    {
        $validated = $request->validate([
            'profile-picture' => 'nullable|file',
            'rg-front' => 'nullable|file',
            'rg-back' => 'nullable|file',
            'cnh-front' => 'nullable|file',
            'cnh-back' => 'nullable|file',
            'vehicle-doc' => 'nullable|file',
        ]);

        $id = auth()->id();

        foreach ($validated as $key => $document) {
            $path = $document->store("drivers/{$id}/{$key}");

            $filename = $document->getClientOriginalName();

            Document::create([
                'driver_id' => $id,

                'folder' => $key,

                'name' => pathinfo($filename, PATHINFO_FILENAME),
                'type' => pathinfo($filename, PATHINFO_EXTENSION),

                'path' => $path,
                'url' => Storage::url($path),
            ]);
        }

        return response()->noContent();
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function showDocument(Document $document)
    {
        if (Storage::missing($document->path)) {
            abort(404);
        }

        return response(Storage::get($document->path), 200, [
            'Content-Type' => $document->mimey,
        ]);
    }

    public function updatePix(Request $request)
    {
        $validated = $request->validate([
            'pix' => 'required',
        ]);

        $driver = Driver::find(auth()->id());

        if (! blank($driver->pix)) {
            abort(403);
        }

        $driver->update($validated);

        return response()->noContent();
    }
}
