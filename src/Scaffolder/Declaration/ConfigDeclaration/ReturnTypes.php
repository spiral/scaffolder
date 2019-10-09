<?php
/**
 * PHP Data Grid Source
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Declaration\ConfigDeclaration;

class ReturnTypes
{
    private const ANNOTATIONS_TYPE_MAPPING = [
        'boolean'  => 'bool',
        'integer'  => 'int',
        'double'   => 'float',
        'NULL'     => 'null',

        //These types aren't mapped
        'float'    => 'float',
        'string'   => 'string',
        'array'    => 'array',
        'object'   => 'object',
        'resource' => 'resource',
    ];

    private const HINTS_TYPE_MAPPING = [
        'boolean' => 'bool',
        'integer' => 'int',
        'double'  => 'float',

        //These types aren't mapped
        'float'   => 'float',
        'string'  => 'string',
        'array'   => 'array',
        'object'  => 'object',
    ];


    /**
     * @param string $type
     * @return string
     */
    public function getHint(string $type): ?string
    {
        return self::HINTS_TYPE_MAPPING[$type] ?? null;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function getAnnotation($value): string
    {
        if (is_array($value)) {
            return $this->makeArrayAnnotation($value);
        }

        return $this->makeAnnotation(gettype($value));
    }

    /**
     * @param array $value
     * @return string
     */
    private function makeArrayAnnotation(array $value): string
    {
        $types = [];
        foreach ($value as $item) {
            $types[] = gettype($item);
        }
        $types = array_unique($types);

        return count($types) === 1 ? "array|{$this->makeAnnotation($types[0])}[]" : 'array';
    }

    /**
     * @param string $type
     * @return string
     */
    private function makeAnnotation(string $type): string
    {
        return self::ANNOTATIONS_TYPE_MAPPING[$type] ?? 'mixed';
    }
}
