<?php namespace Helium\Http\View\Composers;

use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view)
    {
        $menu = config('helium.navigation', []);

        if (is_string($menu) && class_exists($menu)) {
            $menu = app()->call($menu);
        }

        foreach ($menu as $key => &$item) {
            // A simple route ha been given for this menu item.
            if (is_string($item)) {
                $item = [
                    //'url' => route($item)
                ];
            }

            if (empty($item['label'])) {
                $item['label'] = str_humanize($key);
            }
        }

        $view->with('menu', $menu);
    }
}
