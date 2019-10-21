<?php

/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */

declare(strict_types=1);

namespace Spiral\Tests\Scaffolder;

use PHPUnit\Framework\TestCase;
use TestApp\TestApp;

abstract class BaseTest extends TestCase
{
    /** @var TestApp */
    protected $app;

    public function setUp(): void
    {
        $this->app = TestApp::init([
            'root'   => $this->dir(),
        ], null, false);
    }

    private function dir(): string
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
    }
}
