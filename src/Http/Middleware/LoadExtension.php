<?php

namespace Helium\Http\Middleware;

use Closure;
use TwigBridge\Facade\Twig;
use Illuminate\Http\Request;
use Helium\Extensions\EntityExtension;

class LoadExtension
{
    public function handle(Request $request, Closure $next)
    {
        Twig::addExtension(new EntityExtension());
        return $next($request);
    }
}
