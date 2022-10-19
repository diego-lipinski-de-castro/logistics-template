<?php

namespace App\Http\Controllers\Driver;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Services\PhoneAuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function fake()
    {
        if (! app()->environment('local')) {
            abort(404);
        }

        $driver = Driver::find(1);

        $driver->tokens()->delete();

        $accessToken = $driver->createToken('')->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
        ]);
    }

    public function requestCode(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|phone:BR',
        ]);

        $phoneNumber = Helper::toPhone($validated['phone_number']);

        if (! Driver::where('phone', $phoneNumber)->exists()) {
            return response()->json([
                'message' => 'Não existe um usuário com esse número.',
            ], 401);
        }

        $response = (new PhoneAuthService())->verify($phoneNumber);

        return response()->json([
            'phone_number' => $response->phoneNumber,
        ]);
    }

    public function checkCode(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required',
            'code' => 'required',
        ]);

        $phoneNumber = Helper::toPhone($validated['phone_number']);
        $code = $validated['code'];

        if (! Driver::where('phone', $phoneNumber)->exists()) {
            return response()->json([
                'message' => 'Não existe um usuário com esse número.',
            ], 401);
        }

        $response = (new PhoneAuthService())->check($phoneNumber, $code);

        if (blank($response)) {
            return response()->json([
                'message' => 'Não foi possível verificar o número de telefone.',
            ], 400);
        }

        if (! $response->valid || $response->status != 'approved') {
            return response()->json([
                'message' => 'Código inválido.',
            ], 422);
        }

        $driver = Driver::query()
                ->where('phone', $response->phoneNumber)
                ->first();

        $driver->tokens()->delete();

        $accessToken = $driver->createToken('')->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
        ]);
    }
}
