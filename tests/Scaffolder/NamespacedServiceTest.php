<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Scaffolder;

use Spiral\Tests\BaseTest;
use TestApplication\Services\Namespaced\NamespacedService;

class NamespacedServiceTest extends BaseTest
{
    public function tearDown()
    {
        $this->deleteDeclaration(NamespacedService::class);
    }

    public function testService()
    {
        $this->console->run('create:service', [
            'name'      => 'namespaced/namespaced',
            '--comment' => 'Sample Service',
            '-m'        => [
                'methodA',
                'methodB'
            ]
        ]);

        clearstatcache();
        $this->assertTrue(class_exists(NamespacedService::class));

        $reflection = new \ReflectionClass(NamespacedService::class);
        $this->assertContains('Sample Service', $reflection->getDocComment());

        $this->assertTrue($reflection->hasMethod('methodA'));
        $this->assertTrue($reflection->hasMethod('methodB'));
    }
}