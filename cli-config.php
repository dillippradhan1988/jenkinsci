<?php
	use Doctrine\ORM\Tools\Console\ConsoleRunner;
	use Doctrine\ORM\Tools\Setup;
    use Doctrine\ORM\EntityManager;

    // Import doctrine setting
	$settings = include 'app/Tnq/Todo/Configuration/settings.php';
	$settings = $settings['settings']['doctrine'];

   // Create a simple "default" Doctrine ORM configuration for Annotations
    $paths = array(__DIR__."/app/Tnq/Todo/Model");
    $isDevMode = true;
    $config = Setup::createAnnotationMetadataConfiguration(
                        $paths, $isDevMode);

    // database configuration parameters
    $conn = array(
        'driver'      =>  $settings['connection']['driver'],
        'user'        =>  $settings['connection']['user'],
        'password'    =>  $settings['connection']['password'],
        'dbname'      =>  $settings['connection']['dbName'],
        'host'        =>  $settings['connection']['host']
    );

    // obtaining the entity manager
    $entityManager = EntityManager::create($conn, $config);

    return ConsoleRunner::createHelperSet($entityManager);