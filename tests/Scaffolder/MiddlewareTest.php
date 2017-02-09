<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Scaffolder;

use Spiral\Tests\BaseTest;
use TestApplication\Middlewares\SampleMiddleware;

class MiddlewareTest extends BaseTest
{
    public function tearDown()
    {
        $this->deleteDeclaration(SampleMiddleware::class);
    }

    public function testScaffold()
    {
        $this->console->run('create:middleware', [
            'name'      => 'sample-middleware',
            '--comment' => 'Sample Middleware'
        ]);

        clearstatcache();
        $this->assertTrue(class_exists(SampleMiddleware::class));

        $reflection = new \ReflectionClass(SampleMiddleware::class);
        $this->assertContains('Sample Middleware', $reflection->getDocComment());

        $this->assertTrue($reflection->hasMethod('__invoke'));
    }
}