<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'util/*',
        'mgr/*/del',
        '*/products/data',
        'linebot/*',
        'mgr/*/title_data',
        'mgr/*/unit_data',
        'mgr/*/del_academics',
        'mgr/*/del_specialty'
    ];
}
