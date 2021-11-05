<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use FacultyServiceReports\Backend\Database;
use FacultyServiceReports\Backend\Classes\ACCT_T;
use FacultyServiceReports\Backend\EnvironmentLoader;

require __DIR__ . '/vendor/autoload.php';

try {
    EnvironmentLoader::loadEnv();
} catch(Exception $e) {
    echo $e->getMessage();
    echo 'Exiting, environment not loaded properly.';
    exit(1);
}

$app = AppFactory::create();

$app->get('/', function(Request $request, Response $response, $args) {
    $d = Database::create();
    echo $d;
    $payload = json_encode(['val' => 'val'], JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/acct', function(Request $request, Response $response, $args) {
    $res = ACCT_T::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/acct/{account_nbr}', function(Request $request, Response $response, $args) {
    $res = ACCT_T::get_by_account_nbr($args['account_nbr']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();