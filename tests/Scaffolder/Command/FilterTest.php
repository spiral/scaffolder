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

use ReflectionClass;
use ReflectionException;
use Spiral\Tests\Scaffolder\Command\Fixtures\SourceEntity;
use Throwable;

class FilterTest extends AbstractCommandTest
{
    private const CLASS_NAME = '\\TestApp\\Filter\\SampleFilter';

    public function tearDown(): void
    {
        $this->deleteDeclaration(self::CLASS_NAME);
    }

    /**
     * @throws ReflectionException
     * @throws Throwable
     */
    public function testScaffold(): void
    {
        $this->console()->run('create:filter', [
            'name'    => 'sample',
            '--field' => [
                'name:string',
                'email:email',
                'upload:image',
                'unknown:unknown',
                'address',
                'age:string(query)',
                'datetime:datetime(query:date)',
            ]
        ]);

        clearstatcache();
        $this->assertTrue(class_exists(self::CLASS_NAME));

        $reflection = new ReflectionClass(self::CLASS_NAME);

        $this->assertStringContainsString('strict_types=1', $this->files()->read($reflection->getFileName()));
        $this->assertSame([
            'name'     => 'data:name',
            'email'    => 'data:email',
            'upload'   => 'file:upload',
            'unknown'  => 'data:unknown',
            'address'  => 'data:address',
            'age'      => 'query:age',
            'datetime' => 'query:date',
        ], $reflection->getConstant('SCHEMA'));
        $this->assertSame([
            'name'    => ['notEmpty', 'string'],
            'email'   => ['notEmpty', 'string', 'email'],
            'upload'  => ['image::uploaded', 'image::valid'],
            'address' => ['notEmpty', 'string'],
            'age'     => ['notEmpty', 'string'],
        ], $reflection->getConstant('VALIDATES'));
        $this->assertSame([], $reflection->getConstant('SETTERS'));
    }

    /**
     * @throws Throwable
     */
    public function testFromUnknownEntity(): void
    {
        $output = $this->console()->run('create:filter', [
            'name'     => 'sample',
            '--entity' => '\Some\Unknown\Entity'
        ]);

        $this->assertStringContainsString('Unable', $output->getOutput()->fetch());
    }

    /**
     * @throws Throwable
     */
    public function testFromUnknown(): void
    {
        $line = __LINE__;
        $className = "\\TestApp\\Filter\\Sample{$line}Filter";
        $output = $this->console()->run('create:filter', [
            'name'     => 'sample' . $line,
            '--entity' => SourceEntity::class
        ]);

        $this->assertStringNotContainsString('Unable', $output->getOutput()->fetch());

        clearstatcache();
        $this->assertTrue(class_exists($className));

        $reflection = new ReflectionClass($className);

        try {
            $schema = $reflection->getConstant('SCHEMA');
            $this->assertSame($schema['noTypeString'], 'data:noTypeString');
            $this->assertSame($schema['obj'], 'data:obj');
            $this->assertSame($schema['intFromPhpDoc'], 'data:intFromPhpDoc');
            $this->assertSame($schema['noTypeWithFloatDefault'], 'data:noTypeWithFloatDefault');

            $validates = $reflection->getConstant('VALIDATES');
            $this->assertSame($validates['noTypeString'], ['notEmpty', 'string']);
            $this->assertSame($validates['obj'], ['notEmpty', 'string']);
            $this->assertSame($validates['intFromPhpDoc'], ['notEmpty', 'integer']);
            $this->assertSame($validates['noTypeWithFloatDefault'], ['notEmpty', 'float']);
        } finally {
            $this->deleteDeclaration($className);
        }
    }
}
