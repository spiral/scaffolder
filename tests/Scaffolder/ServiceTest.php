<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Scaffolder;

use Spiral\Tests\BaseTest;
use TestApplication\Services\SampleService;

class ServiceTest extends BaseTest
{
    public function testService()
    {
        $this->assertFalse(class_exists(SampleService::class));

        $this->console->run('create:service', [
            'name'      => 'sample',
            '--comment' => 'Sample Service'
        ]);

        clearstatcache();
        $this->assertTrue(class_exists(SampleService::class));
        $this->deleteClass(SampleService::class);
    }
}