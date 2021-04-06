<?php

namespace Helium\Http\Middleware;

use Closure;
use TwigBridge\Facade\Twig;
use Illuminate\Http\Request;
use Helium\Extensions\EntityExtension;
use Twig\Extension\StringLoaderExtension;

class LoadExtension
{
    public function handle(Request $request, Closure $next)
    {
        Twig::addExtension(new StringLoaderExtension());
        Twig::addExtension(new EntityExtension());
        return $next($request);
    }
}
