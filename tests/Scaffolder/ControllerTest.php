<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Scaffolder;

use Spiral\Tests\BaseTest;
use TestApplication\Controllers\SampleController;

class ControllerTest extends BaseTest
{
    public function tearDown()
    {
        $this->deleteDeclaration(SampleController::class);
    }

    public function testService()
    {
        $this->console->run('create:controller', [
            'name'      => 'sample',
            '--comment' => 'Sample Controller',
            '-a'        => [
                'index',
                'save'
            ]
        ]);

        clearstatcache();
        $this->assertTrue(class_exists(SampleController::class));

        $reflection = new \ReflectionClass(SampleController::class);
        $this->assertContains('Sample Controller', $reflection->getDocComment());

        $this->assertTrue($reflection->hasMethod('indexAction'));
        $this->assertTrue($reflection->hasMethod('saveAction'));
    }
}