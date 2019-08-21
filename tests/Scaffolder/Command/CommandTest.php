<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Tests\Scaffolder\Command;

class CommandTest extends AbstractCommandTest
{
    /**
     * @dataProvider commandDataProvider
     * @param string      $className
     * @param string      $name
     * @param string|null $alias
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function testScaffold(string $className, string $name, ?string $alias): void
    {
        $input = [
            'name'          => $name,
            'alias'         => $alias,
            '--description' => 'My sample command description',
        ];
        if ($alias === null) {
            unset($input['alias']);
        }

        $this->console()->run('create:command', $input);

        clearstatcache();

        $this->assertTrue(class_exists($className));

        $reflection = new \ReflectionClass($className);

        $this->assertTrue($reflection->hasMethod('perform'));

        $this->assertTrue($reflection->hasConstant('NAME'));
        $this->assertTrue($reflection->hasConstant('DESCRIPTION'));
        $this->assertTrue($reflection->hasConstant('ARGUMENTS'));
        $this->assertTrue($reflection->hasConstant('OPTIONS'));

        $this->assertSame($alias ?? $name, $reflection->getConstant('NAME'));
        $this->assertSame('My sample command description', $reflection->getConstant('DESCRIPTION'));

        $this->deleteDeclaration($className);
    }

    public function commandDataProvider(): array
    {
        return [
            ['\\TestApp\\Command\\SampleCommand', 'sample', null],
            ['\\TestApp\\Command\\SampleAliasCommand', 'sampleAlias', 'my-sample-command-alias'],
        ];
    }
}