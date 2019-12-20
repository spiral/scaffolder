<?php

/**
 * Spiral Framework. Scaffolder
 *
 * @author Valentin V (vvval)
 */

declare(strict_types=1);

namespace Spiral\Tests\Scaffolder\Command\Fixtures;

class SourceEntity
{
    public $noTypeString;

    /** @var SourceEntity */
    public $obj;

    /** @var int */
    protected $int;

    private $noTypeWithDefault = 1.2;
}
