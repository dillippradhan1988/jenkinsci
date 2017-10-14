<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require __DIR__ . '/../../vendor/autoload.php';

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    use Acme\Model\ProductModel;
    use Acme\Service\ProductService;


    // Instantiate the app
    $settings = require __DIR__
              . '/../../app/Tnq/Todo/Configuration/settings.php';
    $app = new \Slim\App($settings);

    // Set up dependencies
    require __DIR__ . '/../../app/Tnq/Todo/Util/dependencies.php';

    $container = $app->getContainer();
    $productResource = new Acme\Service\ProductService($container->get('view'));

    // Set up routes
    $app->get('/',
        function ($request, $response, $arg) use ($productResource){
            echo $productResource->getLandingPage($request, $response, $arg);
        }
    );

    $app->get('/api/v1/products[/{id}]',
        function ($request, $response, $arg) use ($productResource){
            if (isset($arg['id'])) {
                echo $productResource->getProduct($arg['id']);
            } else {
                echo $productResource->getProducts();
            }
        }
    );

    $app->post('[/api/v1/products]',
        function ($request, $response, $arg) use ($productResource){
            echo $productResource->createProduct($request, $response, $arg);
        }
    );

    $app->put('/api/v1/products/[{id}]',
        function ($request, $response, $arg) use ($productResource){
            echo $productResource->updateProduct($request, $response, $arg);
        }
    );

    $app->delete('/api/v1/products/[{id}]',
        function ($request, $response, $arg) use ($productResource){
            echo $productResource->deleteProduct($request, $response, $arg);
        }
    );

    $app->get('/dataset',
        function ($request, $response, $arg) use ($productResource){
            echo $productResource->getDataSet($request, $response, $arg);
        }
    );

    $app->post('/uploads',
        function ($request, $response, $arg) use ($productResource){
            echo $productResource->uploadFile($request, $response, $arg);
        }
    );

    $app->get('/download[/{fileName}]',
        function ($request, $response, $arg) use ($productResource){
            if (isset($arg['fileName'])) {
                echo $productResource->download($arg['fileName']);
            } else {
                echo $productResource->download();
            }
        }
    );

    // Run app
    $app->run();