<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'api' => [
            \App\Http\Middleware\VerifyNonceAndTimestamp::class,
            \App\Http\Middleware\DecryptGcmRequest::class,
            \App\Http\Middleware\AttachAuthUser::class,
            \App\Http\Middleware\EncryptGcmResponse::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
}
