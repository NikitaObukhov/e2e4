<?php

namespace app\models;

use app\models\bridge\RuntimeDriver;
use Metadata\Driver\FileLocator;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\di\Container;
use Metadata\Driver\DriverChain;
use JMS\Serializer\Metadata\Driver\PhpDriver;
use JMS\Serializer\Metadata\Driver\XmlDriver;
use JMS\Serializer\Metadata\Driver\YamlDriver;


class Bootstrap implements BootstrapInterface
{

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton('serializer.metadata_driver', function (Container $container, array $params, array
        $config) {
            $directoryBag = $container->get('serializer.metadata_directory_bag');

            $directoryBag->freeze();

            $dirs = [];
            foreach ($directoryBag->get() as $directory) {
                $dirs[$directory['namespace']] = $directory['path'];
            }
            foreach ($config['directories'] as $directory) {
                $dirs[$directory['namespace']] = \Yii::getAlias($directory['alias']);
            }

            $locator = new FileLocator($dirs);

            return new DriverChain([
                $container->get('e2e4.serializer.metadata_driver'),
                new YamlDriver($locator),
                new XmlDriver($locator),
                new PhpDriver($locator),
            ]);
        });

    }
}