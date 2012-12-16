<?php
/**
 * A simple wrapper class for the Laravel Database package.  This is only
 * to be used outside of a Laravel application.
 *
 * @author  Dan Horrigan <dan@dhorrigan.com>
 */

namespace DbWrapper;

use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\DatabaseManager;

/**
 * A simple wrapper class for the Laravel Database package.
 */
class Db {

    /**
     * @var ConnectionResolver  Hold the ConnectionResolver object.
     */
    private static $resolver = null;

    /**
     * @var ConnectionFactory  Hold the ConnectionFactory object.
     */
    private static $factory = null;

    /**
     * @var bool  Holds whether the Eloquent Model object has the resolver assigned.
     */
    private static $modelInitialized = false;

    /**
     * Creates a new Connection via the Factory then adds it to the resolver.  If
     * this is the first time it is ran, it will also initialize the Eloquent
     * Model with the ConnectionResolver object so your models work.
     *
     * @param   string  Name of the connection
     * @param   array   The config array for the connection
     * @param   bool    Whether to make this the default connection
     * @return  Illuminate\Database\Connectors\Connection
     */
    public static function makeConnection($name, array $config, $default = false) {
        self::setupResolverAndFactory();

        $conn = self::$factory->make($config);
        self::$resolver->addConnection($name, $conn);

        if ($default) {
            self::$resolver->setDefaultConnection($name);
        }

        if ( ! self::$modelInitialized) {
            Model::setConnectionResolver(self::$resolver);
            self::$modelInitialized = true;
        }

        return $conn;
    }

    /**
     * Passes calls through to the Connection object.
     *
     * @param   string  The method name
     * @param   array   The method parameters sent
     * @return  mixed   The result of the call
     */
    public static function __callStatic($method, $parameters) {
        return call_user_func_array(array(self::$resolver->connection(), $method), $parameters);
    }

    /**
     * Sets up the ConnectionResolver and ConnectionFactory objects.
     *
     * @return void
     */
    private static function setupResolverAndFactory() {
        if (is_null(self::$resolver)) {
            self::$resolver = new ConnectionResolver;
        }
        if (is_null(self::$factory)) {
            self::$factory = new ConnectionFactory;
        }
    }
}
