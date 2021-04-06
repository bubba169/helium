<?php

namespace Helium\Extensions;

use ArrayAccess;
use Twig\TwigFilter;
use Illuminate\Support\Arr;
use Twig\Extension\AbstractExtension;

class EntityExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('resolve', function (string $str, ArrayAccess $entry) {
                return $this->resolveString($str, $entry);
            }),
            new TwigFilter('values', function (string $str, ArrayAccess $entry, ?string $key) {
                $arr = json_decode($this->resolveString($str, $entry), true) ?? [];
                if (is_array(reset($arr))) {
                    return Arr::pluck($arr, $key);
                }
                return $arr ?? [];
            }),
            new TwigFilter('options', function ($value, ArrayAccess $entry, array $field) {
                if (is_string($value)) {
                    return app()->call($value, ['entry' => $entry, 'field' => $field]);
                }
                return $value;
            })
        ];
    }

    /**
     * Takes a string that can include {entity.xyz} tags that will resolve
     * from the entity using dot notation
     */
    protected function resolveString(string $str, ArrayAccess $entry) : string {
        return preg_replace_callback('/\{entry\.(.*)\}/', function ($match) use ($entry) {
            return Arr::get($entry, $match[1]);
        }, $str);
    }
}
