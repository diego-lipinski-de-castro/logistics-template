<?php

namespace App\Http\Controllers\Driver;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Services\PhoneAuthService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function requestCode(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|phone:BR',
        ]);

        $phoneNumber = Helper::toPhone($validated['phone_number']);

        if (Driver::where('phone', $phoneNumber)->exists()) {
            return response()->json([
                'message' => 'Já existe um usuário com esse número.',
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
            'phone_number' => 'required|unique:drivers,phone',
            'code' => 'required',
        ]);

        $phoneNumber = Helper::toPhone($validated['phone_number']);
        $code = $validated['code'];

        if (Driver::where('phone', $phoneNumber)->exists()) {
            return response()->json([
                'message' => 'Já existe um usuário com esse número.',
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

        $driver = Driver::create([
            'phone' => $response->phoneNumber,
        ]);

        $accessToken = $driver->createToken('')->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
        ]);
    }

    public function finish(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required',
            'cpf' => 'required|string|max:255|unique:drivers,cpf',
            'cnh' => 'nullable|string|max:255',
        ]);

        $driver = Driver::find(auth()->id());

        $driver->update(array_merge($validated, [
            'registered' => true,
        ]));

        return response()->noContent();
    }
}
