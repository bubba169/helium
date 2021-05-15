<?php

namespace Helium\Extensions;

use Helium\Config\Form\Field\Field;
use Twig\TwigFilter;
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
            new TwigFilter('resolve', function (?string $str, $entry = null) {
                if ($str === null) {
                    return null;
                }

                return str_resolve($str, $entry);
            }),

            /**
             * Resolves a string to a set of values
             */
            new TwigFilter('values', function (string $str, $entry = null, ?string $key = null) {
                $data = str_resolve($str, $entry);

                if (is_string($data)) {
                    $data = json_decode($data, true);
                }

                $data = collect($data);
                if (is_array($data->first()) && !empty($key)) {
                    return $data->map(fn ($result) => str_resolve($key, $result));
                }
                return $data;
            }),

            /**
             * Generates options from a callable
             */
            new TwigFilter('options', function ($value, ?Model $entry, Field $field) {
                if (is_string($value)) {
                    return app()->call($value, ['entry' => $entry, 'field' => $field]);
                }
                return $value;
            }),

            /**
             * Builds a field name withing a form path
             */
            new TwigFilter('field_name', function (string $str, array $path) {
                if (empty($path)) {
                    return $str;
                }

                $path[] = $str;
                return array_shift($path) . '[' . implode('][', $path) . ']';
            }),

            /**
             * Builds a field name withing a form path
             */
            new TwigFilter('field_id', function (string $str, array $path) {
                $path[] = $str;
                return implode('_', $path);
            }),
        ];
    }
}
