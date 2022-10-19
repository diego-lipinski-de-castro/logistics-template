<?php

namespace App\Http\Controllers\User\Auth;

use Laravel\Fortify\Http\Responses\LogoutResponse as LogoutResponseContract;

class LogoutResponse extends LogoutResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function toResponse($request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        return redirect(route('login'));
    }
}
