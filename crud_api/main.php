<?php
header("Access-Control-Allow-Origin: *");                                           // all included origins
header("Content-Type: application/json; charset=utf-8");                            // json comms only
header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE, OPTIONS");         // allowed methods
header("Access-Control-Allow-Headers: X-Requested-With,  Origin, Content-Type,");   // allowed headers 
header("Access-Control-Max-Age: 86400");                                            // invalidated requests to avoid overloading
date_default_timezone_set("Asia/Manila");
set_time_limit(1000);

$rootPath = $_SERVER["DOCUMENT_ROOT"];    // htDocs
$apiPath = $rootPath . "/crud_api";       // folder path

// Connections
require_once($apiPath .'/configs/connection.php');

// Models
require_once($apiPath .'/models/try.model.php');     // Functions
require_once($apiPath .'/models/global.model.php');  // Global Functions

$db = new Connection();
$pdo = $db->connect();

// Model Instantiates
$global = new GlobalMethods();
$try = new allFunctions($pdo, $global);

// Request from htAccess
$req = [];
if (isset($_REQUEST['request']))
    $req = explode('/', rtrim($_REQUEST['request'], '/'));
else $req = array("errorcatcher");

// Server Methods (To add function cases for Insomnia)
switch ($_SERVER['REQUEST_METHOD']) {
    // Get all Users
    case 'GET':
        if ($req[0]=='getAllUsers') {echo json_encode($try->getAll()); return; }
            // Get a Single User ID
            $data_input = json_decode(file_get_contents("php://input"));
            if ($req[0] == 'getID' && isset($data_input->id)) {$id = $data_input->id; echo json_encode($try->getSingleID($id)); return; }
        break;
    // Insert User
    case 'POST':
        $data_input = json_decode(file_get_contents("php://input"));
        if ($req[0] == 'insert') {echo json_encode($try->insert($data_input)); return; }
        break;
    // Update User
    case 'PUT':
        $data_input = json_decode(file_get_contents("php://input"));
        if ($req[0] == 'update' && isset($data_input->id)) {echo json_encode($try->update($data_input)); return; }
        break;
    // Delete User
    case 'DELETE':
        $data_input = json_decode(file_get_contents("php://input"));
        if ($req[0] == 'delete' && isset($data_input->id)) {$id = $data_input->id; echo json_encode($try->delete($id)); return; }
        break;
}