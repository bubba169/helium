<?php namespace Helium\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Arr;

class NavigationComposer
{
    public function compose(View $view)
    {
        $menu = config('helium.navigation', []);

        if (is_string($menu) && class_exists($menu)) {
            $menu = app()->call($menu);
        }

        $menu = array_map(
            function ($item) {
                if (empty($item['url'])) {
                    if (!empty($item['route'])) {
                        $item['url'] = route($item['route']['name'], Arr::get($item, 'route.params', []));
                    } else {
                        $item['url'] = route('entity.index', ['entityType' => $item['name']]);
                    }
                }
                if (empty($item['label'])) {
                    $item['label'] = str_humanize($item['name']);
                }

                return $item;
            },
            array_normalize_keys($menu, 'name')
        );

        $view->with('menu', $menu);
    }
}
