<?php

namespace tests\unit;
use Yii;
use yii\test\FixtureTrait;
use yii\test\InitDbFixture;


class PHPunit extends \PHPUnit_Framework_TestCase
{
    use FixtureTrait;
    public $appConfig = '@tests/config/unit.php';

    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();

        $this->unloadFixtures();
        $this->loadFixtures();

    }

    public function globalFixtures()
    {
        return [
            InitDbFixture::className(),
        ];
    }


    protected function tearDown()
    {
    }


    /**
     * Mocks up the application instance.
     * @param array $config the configuration that should be used to generate the application instance.
     * If null, [[appConfig]] will be used.
     * @return \yii\web\Application|\yii\console\Application the application instance
     * @throws InvalidConfigException if the application configuration is invalid
     */
    protected function mockApplication($config = null)
    {
        $config = $config === null ? $this->appConfig : $config;
        if (is_string($config)) {
            $configFile = Yii::getAlias($config);
            if (!is_file($configFile)) {
                throw new InvalidConfigException("The application configuration file does not exist: $config");
            }
            $config = require($configFile);
        }
        if (is_array($config)) {
            if (!isset($config['class'])) {
                $config['class'] = 'yii\web\Application';
            }

            return Yii::createObject($config);
        } else {
            throw new InvalidConfigException('Please provide a configuration array to mock up an application.');
        }
    }

}