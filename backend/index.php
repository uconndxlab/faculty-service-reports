<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use FacultyServiceReports\Backend\Database;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function(Request $request, Response $response, $args) {
    $db = Database::create(
        'sys',
        'oracledb',
        'localhost:1521/xe',
        null,
        OCI_SYSDBA
    );
    if ( !$db ) {
        $e = oci_error();
        $response->getBody()->write($e['message']);
        return $response;
    }

    $stid = oci_parse($db, 'SELECT * FROM ACCT_ROLLUP_T');
    oci_execute($stid);

    $all = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    $payload = json_encode($all, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();