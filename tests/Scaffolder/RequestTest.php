<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Scaffolder;

use Spiral\Tests\BaseTest;
use TestApplication\Requests\SampleRequest;

class RequestTest extends BaseTest
{
    public function tearDown()
    {
        $this->deleteDeclaration(SampleRequest::class);
    }

    public function testScaffold()
    {
        $this->console->run('create:request', [
            'name'    => 'sample',
            '--field' => [
                'name:string',
                'email:email',
                'upload:image'
            ]
        ]);

        clearstatcache();
        $this->assertTrue(class_exists(SampleRequest::class));

        $reflection = new \ReflectionClass(SampleRequest::class);

        $this->assertSame([
            'name'   => 'data:name',
            'email'  => 'data:email',
            'upload' => 'file:upload'
        ], $reflection->getConstant('SCHEMA'));

        $this->assertSame([
            'name'  => 'strval',
            'email' => 'strval'
        ], $reflection->getConstant('SETTERS'));

        $this->assertSame([
            'name'   => [
                'notEmpty',
                'string'
            ],
            'email'  => [
                'notEmpty',
                'string',
                'email'
            ],
            'upload' => [
                'image::uploaded',
                'image::valid'
            ]
        ], $reflection->getConstant('VALIDATES'));
    }
}