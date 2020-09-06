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
 * Converts a mixed array of strings with integer keys and nested arrays with string
 * keys to an array of nested arrays with string keys. The strings will be used as the
 * key for an empty array.
 *
 * This is useful for config arrays where the values can either be a string or an
 * array of options.
 *
 * Optionally the key for each item can be copied into the array with the key $nameKey
 *
 * @param array $array The array to convert
 * @param string|null $nameKey The key will be copied into the array with the given key if specified
 * @return array
 */
function array_normalize_keys(array $array, ?string $nameKey = null) : array
{
    $result = [];

    foreach ($array as $key => $value) {
        // If a string with an anteger key convert to an
        // empty array with the old value as the key
        if (is_string($value) and is_integer($key)) {
            $key = $value;
            $value = [];
        }

        // If the name key is set copy the key into the value array
        if (!empty($nameKey) && is_array($value) && !array_key_exists($nameKey, $value)) {
            $value[$nameKey] = $key;
        }

        $result[$key] = $value;
    }

    return $result;
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
