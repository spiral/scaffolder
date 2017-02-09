<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Scaffolder;

use Spiral\Tests\BaseTest;

class DuplicateTest extends BaseTest
{
    public function testScaffold()
    {
        $output = $this->console->run('create:controller', [
            'name' => 'default'
        ]);

        $this->assertContains(
            "Unable to create 'DefaultController' declaration",
            $output->getOutput()->fetch()
        );
    }
}