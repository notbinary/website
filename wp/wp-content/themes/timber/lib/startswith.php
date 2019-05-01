<?php

/**
 * Tests if string $haystack starts with string $needle
 * @param  string $haystack The string to search in
 * @param  string $needle   The string to search for
 * @return bool             True or false result
 */
function startswith($haystack, $needle) {
    return substr($haystack, 0, strlen($needle)) === $needle;
}
