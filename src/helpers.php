<?php

/**
 * Deep merges multiple arrays. String keys will be overwritten unless the
 * value is an array in which case the values will also be deep merged.
 * Any integer keys will be appended to the original.
 *
 * @param array ...$arrays
 * @return array
 */
function array_merge_deep(array ...$arrays) : array
{
    $result = array_shift($arrays);
    foreach ($arrays as $array) {
        foreach ($array as $key => $value) {
            if (is_string($key)) {
                if (is_array($value) && isset($result[$key]) && is_array($result[$key])) {
                    $result[$key] = array_merge_deep($result[$key], $value);
                } else {
                    $result[$key] = $value;
                }
            } else {
                $result[] = $value;
            }
        }
    }

    return $result;
}

/**
 * Converts a mixed array of values and arrays with mixed key types to an array of
 * arrays with string keys. This is useful for config arrays where the values can either ]
 * be a string or an array of options.
 *
 * The key for each item will be copied into the array with the key $nameKey and the
 * value with $valueKey
 *
 * @param array $array The array to convert
 * @param string $nameKey The key will be copied into the array with the given key if specified
 * @param string $valueKey A string value will be copied into the array with the given key if specified
 * @return array
 */
function array_normalise_keys(array $array, string $nameKey = 'name', string $valueKey = 'value') : array
{
    $result = [];

    foreach ($array as $key => $value) {
        // If a string with an anteger key convert to an
        // empty array with the old value as the key
        if (is_string($value) and is_integer($key)) {
            $key = $value;
            $value = [];
        }

        if (!is_array($value)) {
            $value = [
                $valueKey => $value
            ];
        }

        // If the name key is set copy the key into the value array
        if (!array_key_exists($nameKey, $value)) {
            $value[$nameKey] = $key;
        }

        $result[$key] = $value;
    }

    return $result;
}

/**
 * Returns the value of the first key that exists in the array
 *
 * @param array $haystack THe array to check for the key
 * @param array $needles The keys to check
 * @return mixed
 */
function array_first_available(array $haystack, array $needles)
{
    foreach ($needles as $key) {
        if (array_key_exists($key, $haystack)) {
            return $haystack[$key];
        }
    }

    return null;
}

/**
 * Takes a snake case string and converts it to a human friently format.
 *
 * @param string $str
 * @return string
 */
function str_humanize(string $str) : string
{
    return ucfirst(str_replace('_', ' ', $str));
}
