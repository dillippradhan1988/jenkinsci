<?php
    namespace Acme\Model;

    use Doctrine\ORM\EntityManager;
    use Doctrine\ORM\Tools\Setup;

    class MyCn
    {
        /**
         * @var \Doctrine\ORM\EntityManager
         */
        private static $entityManager = null;

        /**
         * @return \Doctrine\ORM\EntityManager
         */
        public static function getEntityManager()
        {
            if (self::$entityManager === null) {
                self::$entityManager = self::createEntityManager();
            }

            return self::$entityManager;
        }

        /**
         * @return EntityManager
         */
        public function createEntityManager()
        {            
            // Import doctrine setting
            $settings = include __DIR__ .'/../Configuration/settings.php';
            $settings = $settings['settings']['doctrine'];

            $path = array(__DIR__ . '/');
            $devMode = true;

            $config = Setup::createAnnotationMetadataConfiguration(
                        $path, $devMode);

            // define credentials...
            $connectionOptions = array(
                'driver'        => $settings['connection']['driver'],
                'user'          => $settings['connection']['user'],
                'password'      => $settings['connection']['password'],
                'dbname'        => $settings['connection']['dbName'],
                'host'          => $settings['connection']['host']
            );

            return EntityManager::create($connectionOptions, $config);
        }
    }