<?php

/**
 * @property Doctrine\ORM\EntityManager $em 
 */
class Doctrine {
    // the Doctrine entity manager
    private static $em = null;

    public function __construct() {
        if (!self::$em) {
            // include our CodeIgniter application's database configuration
            require_once APPPATH . 'config/database.php';

            // include Doctrine's fancy ClassLoader class
            require_once APPPATH . 'libraries/Doctrine/Common/ClassLoader.php';

            // load the Doctrine classes
            $doctrineClassLoader = new \Doctrine\Common\ClassLoader('Doctrine', APPPATH . 'libraries');
            $doctrineClassLoader->register();

            // load Symfony2 helpers
            // Don't be alarmed, this is necessary for YAML mapping files
            $symfonyClassLoader = new \Doctrine\Common\ClassLoader('Symfony', APPPATH . 'libraries/Doctrine');
            $symfonyClassLoader->register();

            // load the entities
            $entityClassLoader = new \Doctrine\Common\ClassLoader('Entities', APPPATH . 'models');
            $entityClassLoader->register();

            // load the proxy entities
            $proxyClassLoader = new \Doctrine\Common\ClassLoader('Proxies', APPPATH . 'models');
            $proxyClassLoader->register();

            // load the repositories
            $entityClassLoader = new \Doctrine\Common\ClassLoader('Repositories', APPPATH . 'models');
            $entityClassLoader->register();

            // set up the configuration 
            $config = new \Doctrine\ORM\Configuration;

            if (ENVIRONMENT == 'development') {
            // set up simple array caching for development mode
                $cache = new \Doctrine\Common\Cache\ArrayCache;
            } else {
            // set up caching with APC for production mode
                $cache = new \Doctrine\Common\Cache\ApcCache;
            }
            $config->setMetadataCacheImpl($cache);
            $config->setQueryCacheImpl($cache);

            // set up proxy configuration
            $config->setProxyDir(APPPATH . 'models/Proxies');
            $config->setProxyNamespace('Proxies');

            // auto-generate proxy classes if we are in development mode
            $config->setAutoGenerateProxyClasses(ENVIRONMENT == 'development');

            // set up annotation driver
            $driverImpl = $config->newDefaultAnnotationDriver(array(APPPATH . 'models/Entities'));
            $config->setMetadataDriverImpl($driverImpl);

            $cnf = new CI_Config();
            $cnf->load('doctrine');
            $db = $cnf->item('db');

            // Database connection information
            $connectionOptions = array(
                'driver' => 'pdo_mysql',
                'user' => $db['default']['username'],
                'password' => $db['default']['password'],
                'host' => $db['default']['hostname'],
                'dbname' => $db['default']['database']
            );

            // create the EntityManager
            $em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

            // store it as a member, for use in our CodeIgniter controllers.
            self::$em = $em;
        }
    }

    /**
     * @return Doctrine\ORM\EntityManager
     */
    public static function getInstance() {
        return self::$em;
    }
}