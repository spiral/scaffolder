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

class FilterTest extends AbstractCommandTest
{
    private const CLASS_NAME = '\\TestApp\\Filter\\SampleFilter';

    public function tearDown(): void
    {
        $this->deleteDeclaration(self::CLASS_NAME);
    }

    /**
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function testScaffold(): void
    {
        $this->console()->run('create:filter', [
            'name'    => 'sample',
            '--field' => [
                'name:string',
                'email:email',
                'upload:image'
            ]
        ]);

        clearstatcache();
        $this->assertTrue(class_exists(self::CLASS_NAME));

        $reflection = new \ReflectionClass(self::CLASS_NAME);

        $this->assertSame([
            'name'   => 'data:name',
            'email'  => 'data:email',
            'upload' => 'file:upload'
        ], $reflection->getConstant('SCHEMA'));
        $this->assertSame([
            'name'   => ['notEmpty', 'string'],
            'email'  => ['notEmpty', 'string', 'email'],
            'upload' => ['image::uploaded', 'image::valid']
        ], $reflection->getConstant('VALIDATES'));
    }
}
