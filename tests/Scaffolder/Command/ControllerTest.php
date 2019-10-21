<?php

/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Anton Titov (Wolfy-J)
 * @author  Valentin V (vvval)
 */

declare(strict_types=1);

namespace Spiral\Tests\Scaffolder\Command;

use Spiral\Prototype\Traits\PrototypeTrait;

class ControllerTest extends AbstractCommandTest
{
    /**
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function testScaffold(): void
    {
        $class = '\\TestApp\\Controller\\SampleController';
        $this->console()->run('create:controller', [
            'name'      => 'sample',
            '--comment' => 'Sample Controller',
            '-a'        => ['index', 'save']
        ]);

        clearstatcache();
        $this->assertTrue(class_exists($class));

        $reflection = new \ReflectionClass($class);

        $this->assertStringContainsString('strict_types=1', $this->files()->read($reflection->getFileName()));
        $this->assertStringContainsString('Sample Controller', $reflection->getDocComment());
        $this->assertTrue($reflection->hasMethod('index'));
        $this->assertTrue($reflection->hasMethod('save'));

        $traits = $reflection->getTraitNames();

        $this->assertEmpty($traits);
        $this->assertNotContains(PrototypeTrait::class, $traits);

        $this->deleteDeclaration($class);
    }

    /**
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function testPrototypeTrait(): void
    {
        $class = '\\TestApp\\Controller\\Sample2Controller';
        $this->console()->run('create:controller', [
            'name'        => 'sample2',
            '--prototype' => true,
        ]);

        clearstatcache();
        $this->assertTrue(class_exists($class));

        $reflection = new \ReflectionClass($class);
        $traits = $reflection->getTraitNames();

        $this->assertNotEmpty($traits);
        $this->assertContains(PrototypeTrait::class, $traits);
        $this->deleteDeclaration($class);
    }
}
