<?php

/**
 * Deep merges multiple arrays. String keys will be overwritten unless the
 * value is an array in which case the values will also be deep merged. 
 * Any integer keys will be appended to the original instead
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
