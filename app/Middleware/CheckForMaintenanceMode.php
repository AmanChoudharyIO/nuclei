<?php

namespace App\Middleware;

//use App\Middleware\Middleware;

class CheckForMaintenanceMode //extends Middleware
{
    /**
     * The Destinations that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
