<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral;

use Spiral\Core\DirectoriesInterface;
use Spiral\Modules\ModuleInterface;
use Spiral\Modules\PublisherInterface;
use Spiral\Modules\RegistratorInterface;
use Spiral\Scaffolder\Configs\ScaffolderConfig;

class ScaffolderModule implements ModuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(RegistratorInterface $registrator)
    {
        //To ensure that our commands can be located
        $registrator->configure('tokenizer', 'directories', 'spiral/scaffolder', [
            "directory('libraries') . 'spiral/scaffolder/source/',"
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function publish(PublisherInterface $publisher, DirectoriesInterface $directories)
    {
        $publisher->publish(
            __DIR__ . '/../resources/config.php',
            $directories->directory('config') . ScaffolderConfig::CONFIG . '.php'
        );
    }
}