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
        $this->assertFalse(class_exists(\TestApplication\Services\SampleService::class));

        $this->deleteClass(\TestApplication\Services\SampleService::class);
    }
}