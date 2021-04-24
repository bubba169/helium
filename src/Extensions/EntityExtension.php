<?php

namespace Helium\Extensions;

use ArrayAccess;
use Twig\TwigFilter;
use Illuminate\Support\Arr;
use Twig\Extension\AbstractExtension;
use Illuminate\Database\Eloquent\Model;

class EntityExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            /**
             * Resolves a string with entry and query params
             */
            new TwigFilter('resolve', function (string $str, ?ArrayAccess $entry = null) {
                return str_resolve($str, $entry);
            }),

            /**
             * Resolves a string to a set of values
             */
            new TwigFilter('values', function (string $str, ?ArrayAccess $entry = null, ?string $key = null) {
                $arr = json_decode(str_resolve($str, $entry), true) ?? [];
                if (is_array(reset($arr))) {
                    return Arr::pluck($arr, $key);
                }
                return $arr ?? [];
            }),

            /**
             * Generates options from a callable
             */
            new TwigFilter('options', function ($value, ?Model $entry, array $field) {
                if (is_string($value)) {
                    return app()->call($value, ['entry' => $entry, 'fieldConfig' => $field]);
                }
                return $value;
            })
        ];
    }
}
