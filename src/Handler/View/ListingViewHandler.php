<?php

namespace Helium\Handler\View;

use Helium\Config\Entity;
use Illuminate\Http\Request;
use \Helium\Config\View\View;

class ListingViewHandler
{
    public function __invoke(Request $request, Entity $entity, View $view)
    {
        $entries = app()->call(
            $view->listingHandler,
            [
                'entity' => $entity,
                'view' => $view
            ]
        );

        return view($view->template, [
            'entity' => $entity,
            'view' => $view,
            'entries' => $entries,
            'filtersOpen' => count(array_filter($request->except(['search', 'sort', 'page']))),
        ]);
    }
}
