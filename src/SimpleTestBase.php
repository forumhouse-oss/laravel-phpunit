<?php namespace FH\Stock\Tests;

use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase;

/**
 * Base class for tests
 */
class SimpleTestBase extends TestCase
{
    /** Booting traits before application is initialized */
    const EVENT_BEFORE_APP = 'beforeApp';

    /** Booting traits after application is initialized */
    const EVENT_AFTER_APP = 'afterApp';

    /**
     * @var string App contract to use when starting up test application
     */
    protected $appContract = 'Illuminate\Contracts\Console\Kernel';

    /**
     * @var string Absolute path to bootstrap/app.php file to boot laravel up
     */
    protected $bootstrapPath;

    /**
     * Creates the application.
     *
     * @return Application
     * @throws Exception
     */
    public function createApplication()
    {
        if (!file_exists($this->bootstrapPath)) {
            throw new Exception(
                'You should set "$bootstrapPath" instancevariable pointing to /bootstrap/app.php file in your Laravel installation'
            );
        }

        $this->bootTraits(self::EVENT_BEFORE_APP);
        /** @var Application $app */
        $app = require $this->bootstrapPath;
        $app->make($this->appContract)->bootstrap();
        $this->bootTraits(self::EVENT_AFTER_APP, $app);

        return $app;
    }

    /**
     * Boot all of the bootable traits on the test case.
     *
     * @param string      $event
     * @param Application $app
     */
    protected function bootTraits($event, Application $app = null)
    {
        foreach (class_uses_recursive(static::class) as $trait) {
            if (method_exists($this, $method = $event.class_basename($trait))) {
                call_user_func([$this, $method], $app);
            }
        }
    }
}
