<?php

/**
 * Spiral Framework.
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder;

if (!function_exists('trimPostfix')) {
    /**
     * @param string $name
     * @param string $postfix
     * @return string
     */
    function trimPostfix(string $name, string $postfix): string
    {
        $pos = mb_strripos($name, $postfix);

        return $pos === false ? $name : mb_substr($name, 0, $pos);
    }
}
