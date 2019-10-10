<?php
/**
 * PHP Data Grid Source
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Declaration\ConfigDeclaration;

class TypeHints
{
    private const MAPPED_HINT_TYPES = [
        'boolean' => 'bool',
        'integer' => 'int',
        'double'  => 'float',
    ];

    private const REAL_HINT_TYPES = [
        'bool'     => 'bool',
        'int'      => 'int',
        'float'    => 'float',
        'string'   => 'string',
        'array'    => 'array',
        'object'   => 'object',
        'callable' => 'callable',
        'iterable' => 'iterable',
    ];

    /**
     * @param string $type
     * @return string
     */
    public function getHint(string $type): ?string
    {
        return self::MAPPED_HINT_TYPES[$type] ?? self::REAL_HINT_TYPES[$type] ?? null;
    }
}