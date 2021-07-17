<?php

namespace Helium\Handler\View;

use Illuminate\Http\Request;
use \Helium\Config\View\View;

class ListingViewHandler
{
    public function __invoke(Request $request, View $view)
    {
        $entries = app()->call(
            $view->listingHandler,
            [
                'view' => $view
            ]
        );

        return view($view->template, [
            'view' => $view,
            'entries' => $entries,
            'filtersOpen' => count(array_filter($request->except(['search', 'sort', 'page']))),
        ]);
    }
}
