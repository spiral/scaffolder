<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Scaffolder;

use Spiral\Tests\BaseTest;
use TestApplication\Commands\SampleCommand;

class CommandTest extends BaseTest
{
    public function tearDown()
    {
        $this->deleteDeclaration(SampleCommand::class);
    }

    public function testScaffold()
    {
        $this->console->run('create:command', [
            'name'          => 'sample',
            'alias'         => 'my-command',
            '--description' => 'My Command',
        ]);

        clearstatcache();
        $this->assertTrue(class_exists(SampleCommand::class));

        $reflection = new \ReflectionClass(SampleCommand::class);
        $this->assertContains('Sample Command', $reflection->getDocComment());

        $this->assertTrue($reflection->hasMethod('perform'));

        $this->assertTrue($reflection->hasConstant('NAME'));
        $this->assertTrue($reflection->hasConstant('DESCRIPTION'));
        $this->assertTrue($reflection->hasConstant('ARGUMENTS'));
        $this->assertTrue($reflection->hasConstant('OPTIONS'));

        $this->assertSame('my-command', $reflection->getConstant('NAME'));
        $this->assertSame('My Command', $reflection->getConstant('DESCRIPTION'));
    }
}