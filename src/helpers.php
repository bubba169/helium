<?php

function array_merge_deep(array ...$arrays)
{
    $result = array_shift($arrays);
    foreach ($arrays as $array) {
        foreach ($array as $key => $value) {
            if (is_array($value) && isset($result[$key]) && is_array($result[$key])) {
                $result[$key] = array_merge_deep($result[$key], $value);
            } else {
                $result[$key] = $value;
            }
        }
    }

    return $result;
}
