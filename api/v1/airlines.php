<?php
session_start();
require_once('../controller/DBConfig.php');
require_once('../model/Response.php');
require_once('../model/Airlines.php');
header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

try {
    $writeDB = DB::connectionWriteDB();
    $readDB = DB::connectionReadDB();
} catch (PDOException $ex) {
    error_log("Connection Error - " . $ex, 0);
    $response = new Response();
    $response->setHTTPStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("Database Connection Error");
    $response->send();
    exit();
}

// Pengecekan Token
if (!isset($_SESSION['access_token'])) {
    $response = new Response();
    $response->setHttpStatusCode(401);
    $response->setSuccess(false);
    $response->addMessage("Access token is missing from the header");
    $response->send();
    exit;
} else {
    // Token
    $accesstoken = $_SESSION['access_token'];
}

// Mengambil Data Session
try {
    $query = $writeDB->prepare('SELECT sessions.user_id, sessions.level, accesstokenexpiry FROM sessions, users WHERE sessions.user_id = users.user_id AND accesstoken = :accesstoken');
    $query->bindParam(':accesstoken', $accesstoken, PDO::PARAM_STR);
    $query->execute();

    $rowCount = $query->rowCount();

    if ($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("Invalid access token");
        $response->addMessage($accesstoken);
        $response->addMessage($test);
        $response->send();
        exit;
    }

    $row = $query->fetch(PDO::FETCH_ASSOC);

    // Mengambil User ID, Level, Dan Access Token Expiry
    $returned_userid = $row['user_id'];
    $returned_level = $row['level'];
    $returned_accesstokenexpiry = $row['accesstokenexpiry'];

    $currentTimestamp = time() + 18000;
    $accessTokenExpiryTimestamp = strtotime($returned_accesstokenexpiry);


    // Pengecekan Access Token Expiry
    if (strtotime($returned_accesstokenexpiry) <= (time() + 18000)) {
        $response = new Response();
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("Access token has expired");
        $response->send();
        exit;
    }

} catch (PDOException $ex) {
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage($ex->getMessage());
    $response->send();
    exit;
}



// Pengecekan Account Level
if ($returned_level == 0) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Validasi Tipe Content Type
        if (array_key_exists("id", $_GET)) {
            $AirlineID = $_GET['id'];
            if ($AirlineID == '' || !is_numeric($AirlineID)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Airline ID cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $AirlineID = '';
        }
        if (array_key_exists("name", $_GET)) {
            $AirlineName = $_GET['name'];
            if ($AirlineName == '' || is_numeric($AirlineName)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Airline Name cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $AirlineName = '';
        }
        if (array_key_exists("code", $_GET)) {
            $AirlineCode = $_GET['code'];
            if ($AirlineCode == '' || is_numeric($AirlineCode)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Airline Code cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $AirlineCode = '';
        }
        if (array_key_exists("page", $_GET)) {
            $page = $_GET['page'];
            $limitPerPage = 3;
            if ($page == '' || !is_numeric($page)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Page cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $page = '';
        }
        if (array_key_exists("pageSize", $_GET)) {
            // Jika Menggunakan Parameter pageSize
            $pageSize = $_GET['pageSize'];
            if ($pageSize == '' || !is_numeric($pageSize)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Page Size cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $pageSize = '';
        }
        try {
            if ($AirlineID) {
                $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines WHERE airline_id=:id');
                $query->bindParam(':id', $AirlineID, PDO::PARAM_INT);
            } else if ($AirlineName) {
                $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines WHERE airline_name=:name');
                $query->bindParam(':name', $AirlineName, PDO::PARAM_STR);
            } else if ($AirlineCode) {
                $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines WHERE airline_code=:code');
                $query->bindParam(':code', $AirlineCode, PDO::PARAM_STR);
            } else if ($page) {
                $queryCount = $readDB->prepare('select count(airline_id) as totalNoOfAirlines from airlines');
                $queryCount->execute();
                $row = $queryCount->fetch(PDO::FETCH_ASSOC);
                $airlinesCount = intval($row['totalNoOfAirlines']);
                $numOfPages = ceil($airlinesCount / $limitPerPage);
                if ($numOfPages == 0) {
                    $numOfPages = 1;
                }
                if ($page > $numOfPages || $page == 0) {
                    $response = new Response();
                    $response->setHTTPStatusCode(404);
                    $response->setSuccess(false);
                    $response->addMessage("Page not found");
                    $response->send();
                    exit();
                }
                $offset = ($page == 1 ? 0 : ($limitPerPage * ($page - 1)));
                $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines limit :pglimit offset :offset');
                $query->bindParam(':pglimit', $limitPerPage, PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            } else if ($pageSize) {
                $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines WHERE airline_id BETWEEN 1 AND :pageSize');
                $query->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
            } else {
                $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines');
            }
    
            $query->execute();
            $rowCount = $query->rowCount();
    
            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Airline not found");
                $response->send();
                exit();
            }
    
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Airlines = new Airlines($row['airline_id'], $row['airline_name'], $row['airline_code'], $row['total_seats']);
                $airlinesArray[] = $Airlines->returnAirlinesArray();
            }
    
            $returnData = array();
            if ($page) {
                $returnData['total_airlines'] = $airlinesCount;
                $returnData['total_pages'] = $numOfPages;
            }
            $returnData['rows_returned'] = $rowCount;
            $returnData['airlines'] = $airlinesArray;
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        } catch (AirlinesException $ex) {
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        } catch (PDOException $ex) {
            error_log("Database Query error - " . $ex, 0);
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Pengecekan Tipe Content (JSON)
            if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Content type header is not set to JSON");
                $response->send();
                exit;
            }
            // Pengecekan Isi Content
            $rawPOSTData = file_get_contents('php://input');
            if (!$jsonData = json_decode($rawPOSTData)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Request body is not valid JSON");
                $response->send();
                exit;
            }
            // Pengecekan Isi Airline
            if (!isset($jsonData->airline_name) || strlen($jsonData->airline_name) <= 0 || strlen($jsonData->airline_name) > 255) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Airline Name field cannot be blank or more than 255!");
                $response->send();
                exit;
            } else if (!isset($jsonData->airline_code) || strlen($jsonData->airline_code) <= 0 || strlen($jsonData->airline_code) > 255) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Airline Code field cannot be blank or more than 255!");
                $response->send();
                exit;
            } else if (!isset($jsonData->destination) || strlen($jsonData->destination) <= 0 || strlen($jsonData->destination) > 255) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Destination field cannot be blank or more than 255!");
                $response->send();
                exit;
            }
    
            $AirlineName = $jsonData->airline_name;
            $AirlineCode = $jsonData->airline_code;
            $Destination = $jsonData->destination;
    
            $query = $writeDB->prepare('ALTER TABLE airlines AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE airlines_info AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE bookings AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE bookings_info AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE flights AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE seats AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE sessions AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE users AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare("INSERT INTO airlines_info (airline_name, airline_code, destination, info_created, airline_status) values (:airline_name, :airline_code, :destination, CURRENT_TIMESTAMP(), 'Created')");
            $query->bindParam(':airline_name', $AirlineName, PDO::PARAM_STR);
            $query->bindParam(':airline_code', $AirlineCode, PDO::PARAM_STR);
            $query->bindParam(':destination', $Destination, PDO::PARAM_STR);
            $query->execute();
    
            $rowCount = $query->rowCount();
            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to create airline");
                $response->send();
                exit;
            }
            $query = $readDB->prepare('SELECT airlines.airline_id, airlines.airline_name, airlines.airline_code, flights.destination, airlines.total_seats FROM airlines, flights WHERE airlines.airline_id = (SELECT airline_id FROM airlines ORDER BY airline_id DESC LIMIT 1) AND flights.flight_id = (SELECT flight_id FROM flights ORDER BY flight_id DESC LIMIT 1)');
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to retrieve airline after creation");
                $response->send();
                exit;
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Airlines = array();
                $Airlines['airline_id'] = $row['airline_id'];
                $Airlines['airline_name'] = $row['airline_name'];
                $Airlines['airline_code'] = $row['airline_code'];
                $Airlines['destination'] = $row['destination'];
                $Airlines['total_seats'] = $row['total_seats'];
                $airlinesArray[] = $Airlines;
            }
    
            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['airlines'] = $airlinesArray;
            $response = new Response();
            $response->setHTTPStatusCode(201);
            $response->setSuccess(true);
            $response->addMessage("Airline Created");
            $response->setData($returnData);
            $response->send();
            exit;
    
        } catch (AirlinesException $ex) {
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        } catch (PDOException $ex) {
            if ($ex->getCode() == '23000' && strpos($ex->getMessage(), 'Duplicate entry' && strpos($ex->getMessage(), 'airline_name')) == true) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Airline Name already exists");
                $response->send();
                exit();
            } else if ($ex->getCode() == '23000' && strpos($ex->getMessage(), 'Duplicate entry' && strpos($ex->getMessage(), 'destination')) == true) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Destination already exists");
                $response->send();
                exit();
            }
    
            error_log("Database Query error - " . $ex, 0);
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        try {
            // Pengecekan Tipe Content (JSON)
            if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Content type header is not set to JSON");
                $response->send();
                exit;
            }
            // Pengecekan Isi Content
            $rawPOSTData = file_get_contents('php://input');
            if (!$jsonData = json_decode($rawPOSTData)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Request body is not valid JSON");
                $response->send();
                exit;
            }
    
            $airlineName_updated = false;
            $airlineCode_updated = false;
            $queryFields = "";
    
            if (isset($jsonData->airline_name)) {
                $airlineName_updated = true;
                $queryFields .= "airline_name = :airline_name, ";
            }
            if (isset($jsonData->airline_code)) {
                $airlineCode_updated = true;
                $queryFields .= "airline_code = :airline_code, ";
            }
    
            $queryFields = rtrim($queryFields, ", ");
    
            // Pengecekan Field
            if ($airlineName_updated === false && $airlineCode_updated === false) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("No airline fields provided");
                $response->send();
                exit;
            }
    
            // Pengecekan Parameter id
            if (array_key_exists("id", $_GET)) {
                $AirlineID = $_GET['id'];
                if ($AirlineID == '' || !is_numeric($AirlineID)) {
                    $response = new Response();
                    $response->setHTTPStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("Airline ID cannot be blank or must be numeric");
                    $response->send();
                    exit();
                }
            } else {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Endpoint Not Found");
                $response->send();
                exit();
            }
    
            $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines WHERE airline_id=:id');
            $query->bindParam(':id', $AirlineID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No airline found to update");
                $response->send();
                exit;
            }
    
            $query = $writeDB->prepare('ALTER TABLE airlines AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE airlines_info AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE bookings AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE bookings_info AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE flights AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE seats AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE sessions AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE users AUTO_INCREMENT 0');
            $query->execute();
            $queryString = "UPDATE airlines SET " . $queryFields . " WHERE airline_id = :id";
            $query = $writeDB->prepare($queryString);
    
            if ($airlineName_updated === true) {
                $AirlineName = $jsonData->airline_name;
                $query->bindParam(':airline_name', $AirlineName, PDO::PARAM_STR);
            }
            if ($airlineCode_updated === true) {
                $AirlineCode = $jsonData->airline_code;
                $query->bindParam(':airline_code', $AirlineCode, PDO::PARAM_STR);
            }
    
            $query->bindParam(':id', $AirlineID, PDO::PARAM_INT);
            $query->execute();
            // Pengecekan Inputan User (Apakah Value Yang Diinput Sama Atau Tidak)
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Airline not updated - given values may be the same as the stored values");
                $response->send();
                exit;
            }
    
            // Pengecekan Airline (Apakah ada atau tidak)
            $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines WHERE airline_id=:id');
            $query->bindParam(':id', $AirlineID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No airline found");
                $response->send();
                exit;
            }
    
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Airlines = array();
                $Airlines['airline_id'] = $row['airline_id'];
                $Airlines['airline_name'] = $row['airline_name'];
                $Airlines['airline_code'] = $row['airline_code'];
                $Airlines['total_seats'] = $row['total_seats'];
                $airlinesArray[] = $Airlines;
            }
    
            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['airlines'] = $airlinesArray;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("Airline Updated");
            $response->setData($returnData);
            $response->send();
    
        } catch (AirlinesException $ex) {
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        } catch (PDOException $ex) {
            if ($ex->getCode() == '23000' && strpos($ex->getMessage(), 'Duplicate entry') == true) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Airline Name already exists.");
                $response->send();
                exit();
            }
    
            error_log("Database Query error - " . $ex, 0);
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        try {
            // Pengecekan Tipe Content (JSON)
            if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Content type header is not set to JSON");
                $response->send();
                exit;
            }
            // Pengecekan Parameter id
            if (array_key_exists("id", $_GET)) {
                $AirlineID = $_GET['id'];
                if ($AirlineID == '' || !is_numeric($AirlineID)) {
                    $response = new Response();
                    $response->setHTTPStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("Airline ID cannot be blank or must be numeric");
                    $response->send();
                    exit();
                }
            } else {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Endpoint Not Found");
                $response->send();
                exit();
            }
    
            $query = $writeDB->prepare('ALTER TABLE airlines AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE airlines_info AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE bookings AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE bookings_info AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE flights AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE seats AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE sessions AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('ALTER TABLE users AUTO_INCREMENT 0');
            $query->execute();
            $query = $writeDB->prepare('DELETE FROM airlines WHERE airline_id = :id');
            $query->bindParam(':id', $AirlineID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
    
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No airline found to delete");
                $response->send();
                exit;
            }
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("Airline Deleted");
            $response->send();
            exit();
    
        } catch (AirlinesException $ex) {
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        } catch (PDOException $ex) {
            error_log("Database Query error - " . $ex, 0);
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        }
    } else {
        $response = new Response();
        $response->setHTTPStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit();
    }
} else if ($returned_level == 1) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Validasi Tipe Content Type
        if (array_key_exists("id", $_GET)) {
            $AirlineID = $_GET['id'];
            if ($AirlineID == '' || !is_numeric($AirlineID)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Airline ID cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $AirlineID = '';
        }
        if (array_key_exists("name", $_GET)) {
            $AirlineName = $_GET['name'];
            if ($AirlineName == '' || is_numeric($AirlineName)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Airline Name cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $AirlineName = '';
        }
        if (array_key_exists("code", $_GET)) {
            $AirlineCode = $_GET['code'];
            if ($AirlineCode == '' || is_numeric($AirlineCode)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Airline Code cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $AirlineCode = '';
        }
        if (array_key_exists("page", $_GET)) {
            $page = $_GET['page'];
            $limitPerPage = 3;
            if ($page == '' || !is_numeric($page)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Page cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $page = '';
        }
        if (array_key_exists("pageSize", $_GET)) {
            // Jika Menggunakan Parameter pageSize
            $pageSize = $_GET['pageSize'];
            if ($pageSize == '' || !is_numeric($pageSize)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Page Size cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $pageSize = '';
        }
        try {
            if ($AirlineID) {
                $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines WHERE airline_id=:id');
                $query->bindParam(':id', $AirlineID, PDO::PARAM_INT);
            } else if ($AirlineName) {
                $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines WHERE airline_name=:name');
                $query->bindParam(':name', $AirlineName, PDO::PARAM_STR);
            } else if ($AirlineCode) {
                $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines WHERE airline_code=:code');
                $query->bindParam(':code', $AirlineCode, PDO::PARAM_STR);
            } else if ($page) {
                $queryCount = $readDB->prepare('select count(airline_id) as totalNoOfAirlines from airlines');
                $queryCount->execute();
                $row = $queryCount->fetch(PDO::FETCH_ASSOC);
                $airlinesCount = intval($row['totalNoOfAirlines']);
                $numOfPages = ceil($airlinesCount / $limitPerPage);
                if ($numOfPages == 0) {
                    $numOfPages = 1;
                }
                if ($page > $numOfPages || $page == 0) {
                    $response = new Response();
                    $response->setHTTPStatusCode(404);
                    $response->setSuccess(false);
                    $response->addMessage("Page not found");
                    $response->send();
                    exit();
                }
                $offset = ($page == 1 ? 0 : ($limitPerPage * ($page - 1)));
                $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines limit :pglimit offset :offset');
                $query->bindParam(':pglimit', $limitPerPage, PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            } else if ($pageSize) {
                $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines WHERE airline_id BETWEEN 1 AND :pageSize');
                $query->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
            } else {
                $query = $readDB->prepare('SELECT airline_id, airline_name, airline_code, total_seats FROM airlines');
            }
    
            $query->execute();
            $rowCount = $query->rowCount();
    
            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Airline not found");
                $response->send();
                exit();
            }
    
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Airlines = new Airlines($row['airline_id'], $row['airline_name'], $row['airline_code'], $row['total_seats']);
                $airlinesArray[] = $Airlines->returnAirlinesArray();
            }
    
            $returnData = array();
            if ($page) {
                $returnData['total_airlines'] = $airlinesCount;
                $returnData['total_pages'] = $numOfPages;
            }
            $returnData['rows_returned'] = $rowCount;
            $returnData['airlines'] = $airlinesArray;
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        } catch (AirlinesException $ex) {
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        } catch (PDOException $ex) {
            error_log("Database Query error - " . $ex, 0);
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        }
    } else {
        $response = new Response();
        $response->setHTTPStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit();
    }
} else {
    $response = new Response();
    $response->setHTTPStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("You're Hacker !");
    $response->send();
    exit();
}