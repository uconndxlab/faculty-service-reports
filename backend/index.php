<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\Container;
use FacultyServiceReports\Backend\Database;
use FacultyServiceReports\Backend\Classes\ACCT_CLOSEOUT_STAT_T;
use FacultyServiceReports\Backend\Classes\ACCT_CLOSEOUT_T;
use FacultyServiceReports\Backend\Classes\ACCT_EXT_T;
use FacultyServiceReports\Backend\Classes\ACCT_T;
use FacultyServiceReports\Backend\Classes\AWD_ACCT_T;
use FacultyServiceReports\Backend\Classes\CLOSEOUT_STAT_L;
use FacultyServiceReports\Backend\Classes\DELIVERABLES_T;
use FacultyServiceReports\Backend\Classes\FS_ACCT_SUMMARY_T;
use FacultyServiceReports\Backend\Classes\FS_ACCT_TOP_SUMM_T;
use FacultyServiceReports\Backend\Classes\FS_CURRENT_FY;
use FacultyServiceReports\Backend\Classes\KFS_LABOR_TRANS;
use FacultyServiceReports\Backend\Classes\KFS_TRANS;
use FacultyServiceReports\Backend\Classes\KFS_TRANS_DETAILS;
use FacultyServiceReports\Backend\Classes\PROPOSALS_T;
use FacultyServiceReports\Backend\EnvironmentLoader;

require __DIR__ . '/vendor/autoload.php';

try {
    EnvironmentLoader::loadEnv();
} catch(Exception $e) {
    echo $e->getMessage();
    echo 'Exiting, environment not loaded properly.';
    exit(1);
}

$container = new Container();
$container->set('phpErrorHandler', function($container) {
    return function ($request, $response, $error) use ($container) {
        $payload = json_encode(['error' => 'Issue retrieving database records.  Please check you are looking up with a valid ID.']);
        return $response->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write($payload);
    };
});
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->get('/', function(Request $request, Response $response, $args) {
    $d = Database::create();
    echo $d;
    $payload = json_encode(['val' => 'val'], JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


/** ACCT_CLOSEOUT_STAT_T */
$app->get('/acct_closeout_stat', function(Request $request, Response $response, $args) {
    $res = ACCT_CLOSEOUT_STAT_T::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/acct_closeout_stat/{closeout_id}', function(Request $request, Response $response, $args) {
    $res = ACCT_CLOSEOUT_STAT_T::get_all_by_closeout_id($args['closeout_id']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


/** ACCT_CLOSEOUT_T */
$app->get('/acct_closeout', function(Request $request, Response $response, $args) {
    $res = ACCT_CLOSEOUT_T::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/acct_closeout/{account_nbr}', function(Request $request, Response $response, $args) {
    $res = ACCT_CLOSEOUT_T::get_all_by_account_nbr($args['account_nbr']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


/** ACCT_EXT_T */
$app->get('/acct_ext', function(Request $request, Response $response, $args) {
    $res = ACCT_EXT_T::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/acct_ext/{account_nbr}', function(Request $request, Response $response, $args) {
    $res = ACCT_EXT_T::get_all_by_account_nbr($args['account_nbr']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
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


/** AWD_ACCT_T */
$app->get('/awd_acct', function(Request $request, Response $response, $args) {
    $res = AWD_ACCT_T::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/awd_acct/{account_nbr}', function(Request $request, Response $response, $args) {
    $res = AWD_ACCT_T::get_all_by_account_nbr($args['account_nbr']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


/** 
 * CLOSEOUT_STAT_L
 * Lookup table for Closeout Statuses
 */
$app->get('/closeout_stat', function(Request $request, Response $response, $args) {
    $res = CLOSEOUT_STAT_L::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/closeout_stat/{stat_id}', function(Request $request, Response $response, $args) {
    $res = CLOSEOUT_STAT_L::get_by_stat_id($args['stat_id']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


/** 
 * DELIVERABLES_T
 */
$app->get('/deliverables', function(Request $request, Response $response, $args) {
    $res = DELIVERABLES_T::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/deliverables/{prop_no}', function(Request $request, Response $response, $args) {
    $res = DELIVERABLES_T::get_all_by_prop_no($args['prop_no']);
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


/** KFS_LABOR_TRANS */
$app->get('/kfs_labor_trans', function(Request $request, Response $response, $args) {
    $res = KFS_LABOR_TRANS::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/kfs_labor_trans/{account_nbr}', function(Request $request, Response $response, $args) {
    $res = KFS_LABOR_TRANS::get_all_by_account_nbr($args['account_nbr']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/kfs_labor_trans/{account_nbr}/{fin_code}', function(Request $request, Response $response, $args) {
    $res = KFS_LABOR_TRANS::get_all_by_account_fincode($args['account_nbr'], $args['fin_code']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


/** KFS_TRANS */
$app->get('/kfs_trans', function(Request $request, Response $response, $args) {
    $res = KFS_TRANS::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/kfs_trans/{account_nbr}', function(Request $request, Response $response, $args) {
    $res = KFS_TRANS::get_all_by_account_nbr($args['account_nbr']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/kfs_trans/{account_nbr}/{fin_code}', function(Request $request, Response $response, $args) {
    $res = KFS_TRANS::get_all_by_account_fincode($args['account_nbr'], $args['fin_code']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


/** KFS_TRANS_DETAILS */
$app->get('/kfs_trans_details', function(Request $request, Response $response, $args) {
    $res = KFS_TRANS_DETAILS::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/kfs_trans_details/{tid}', function(Request $request, Response $response, $args) {
    $res = KFS_TRANS_DETAILS::get_by_tid($args['tid']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

/** PROPOSALS_T */
$app->get('/proposals', function(Request $request, Response $response, $args) {
    $res = PROPOSALS_T::get_all();
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/proposals/{projid}', function(Request $request, Response $response, $args) {
    $res = PROPOSALS_T::get_all_by_projid($args['projid']);
    $payload = json_encode($res, JSON_PRETTY_PRINT);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

/** @TODO Change this to approved list. */
header("Access-Control-Allow-Origin: *");

$app->run();