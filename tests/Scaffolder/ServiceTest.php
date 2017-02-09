<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Scaffolder;

use Spiral\Tests\BaseTest;

class ServiceTest extends BaseTest
{
    public function testService()
    {
        $this->assertFalse(
            class_exists(\TestApplication\Models\SampleService::class)
        );

        $this->console->run('create:service', [
            'name' => 'sample'
        ]);

        $this->assertTrue(
            class_exists(\TestApplication\Models\SampleService::class)
        );

        $this->deleteClass(\TestApplication\Models\SampleService::class);
    }
}