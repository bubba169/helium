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
                return $this->resolveString($str, $entry);
            }),

            /**
             * Resolves a string to a set of values
             */
            new TwigFilter('values', function (string $str, ?ArrayAccess $entry = null, ?string $key = null) {
                $arr = json_decode($this->resolveString($str, $entry), true) ?? [];
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

    /**
     * Takes a string that can include {entity.xyz} tags that will resolve
     * from the entity or query string using dot notation
     */
    protected function resolveString(string $str, ?ArrayAccess $entry = null) : string {

        // Replace any entity references with values from the entity
        if ($entry) {
            $str = preg_replace_callback('/\{entry\.(.*)\}/', function ($match) use ($entry) {
                return $entry ? Arr::get($entry, $match[1]) : '';
            }, $str);
        }

        //Replace any values from the query
        $str = preg_replace_callback('/\{query\.(.*)\}/', function ($match) {
            $val = request()->query($match[1]);
            if (is_array($val)) {
                $val = json_encode($val);
            }
            return $val;
        }, $str);

        return $str;
    }
}
