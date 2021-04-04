<?php

namespace Helium\Extensions;

use Twig\TwigFilter;
use Illuminate\Support\Arr;
use Twig\Extension\AbstractExtension;

class EntityExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('resolve', function (string $str, $entry) {
                return preg_replace_callback('/\{entry\.(.*)\}/', function ($match) use ($entry) {
                    return Arr::get($entry, $match[1]);
                }, $str);
            }),
            new TwigFilter('options', function ($value, $entry) {
                if (is_string($value)) {
                    return app()->call($value, ['entry' => $entry]);
                }
                return $value;
            })
        ];
    }
}
