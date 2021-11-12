<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use FacultyServiceReports\Backend\Database;
use FacultyServiceReports\Backend\Classes\ACCT_T;
use FacultyServiceReports\Backend\Classes\FS_ACCT_SUMMARY_T;
use FacultyServiceReports\Backend\Classes\FS_ACCT_TOP_SUMM_T;
use FacultyServiceReports\Backend\Classes\FS_CURRENT_FY;
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


/** ACCT_T */
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


/** FS_ACCT_SUMMARY_T */
$app->get('/fs_acct_summary', function(Request $request, Response $response, $args) {
    $res = FS_ACCT_SUMMARY_T::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/fs_acct_summary/{account_nbr}', function(Request $request, Response $response, $args) {
    $res = FS_ACCT_SUMMARY_T::get_all_by_account_nbr($args['account_nbr']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


/** FS_ACCT_TOP_SUMM_T */
$app->get('/fs_acct_top_summ', function(Request $request, Response $response, $args) {
    $res = FS_ACCT_TOP_SUMM_T::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/fs_acct_top_summ/{account_nbr}', function(Request $request, Response $response, $args) {
    $res = FS_ACCT_TOP_SUMM_T::get_all_by_account_nbr($args['account_nbr']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


/** FS_CURRENT_FY */
$app->get('/fs_current_fy', function(Request $request, Response $response, $args) {
    $res = FS_CURRENT_FY::get();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();