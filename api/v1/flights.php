<?php
session_start();
require_once('../controller/DBConfig.php');
require_once('../model/Response.php');
require_once('../model/Flights.php');
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
        $response->send();
        exit;
    }


    $row = $query->fetch(PDO::FETCH_ASSOC);

    // Mengambil User ID, Level, Dan Access Token Expiry
    $returned_userid = $row['user_id'];
    $returned_level = $row['level'];
    $returned_accesstokenexpiry = $row['accesstokenexpiry'];


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
    $response->addMessage("There was an issue authenticating - please try again");
    $response->send();
    exit;
}

// Pengecekan Account Level
if ($returned_level == 0) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (array_key_exists("id", $_GET)) {
            $FlightID = $_GET['id'];
            if ($FlightID == '' || !is_numeric($FlightID)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Flights ID cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $FlightID = '';
        }
        if (array_key_exists("origin", $_GET)) {
            $Origin = $_GET['origin'];
            if ($Origin == '' || is_numeric($Origin)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Origin cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $Origin = '';
        }
        if (array_key_exists("destination", $_GET)) {
            $Destination = $_GET['destination'];
            if ($Destination == '' || is_numeric($Destination)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Destination cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $Destination = '';
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
            if ($FlightID) {
                $query = $readDB->prepare('SELECT flights.flight_id, airline_name, airline_code, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, price, airlines.total_seats - COUNT(CASE WHEN seats.is_available = 0 THEN 1 END) AS available_seats FROM flights INNER JOIN airlines ON flights.airline_id = airlines.airline_id INNER JOIN seats ON flights.flight_id = seats.flight_id  WHERE flights.flight_id=:id GROUP BY flights.flight_id');
                $query->bindParam(':id', $FlightID, PDO::PARAM_INT);
            } else if ($Origin) {
                $query = $readDB->prepare('SELECT flights.flight_id, airline_name, airline_code, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, price, airlines.total_seats - COUNT(CASE WHEN seats.is_available = 0 THEN 1 END) AS available_seats FROM flights INNER JOIN airlines ON flights.airline_id = airlines.airline_id INNER JOIN seats ON flights.flight_id = seats.flight_id WHERE origin=:origin GROUP BY flights.flight_id');
                $query->bindParam(':origin', $Origin, PDO::PARAM_STR);
            } else if ($Destination) {
                $query = $readDB->prepare('SELECT flights.flight_id, airline_name, airline_code, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, price, airlines.total_seats - COUNT(CASE WHEN seats.is_available = 0 THEN 1 END) AS available_seats FROM flights INNER JOIN airlines ON flights.airline_id = airlines.airline_id INNER JOIN seats ON flights.flight_id = seats.flight_id WHERE destination=:destination GROUP BY flights.flight_id ');
                $query->bindParam(':destination', $Destination, PDO::PARAM_STR);
            } else if ($page) {
                $queryCount = $readDB->prepare('select count(flight_id) as totalNoOfFlights from flights');
                $queryCount->execute();
                $row = $queryCount->fetch(PDO::FETCH_ASSOC);
                $flightsCount = intval($row['totalNoOfFlights']);
                $numOfPages = ceil($flightsCount / $limitPerPage);
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
                $query = $readDB->prepare('SELECT flights.flight_id, airline_name, airline_code, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, price, airlines.total_seats - COUNT(CASE WHEN seats.is_available = 0 THEN 1 END) AS available_seats FROM flights INNER JOIN airlines ON flights.airline_id = airlines.airline_id INNER JOIN seats ON flights.flight_id = seats.flight_id GROUP BY flights.flight_id limit :pglimit offset :offset');
                $query->bindParam(':pglimit', $limitPerPage, PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            } else if ($pageSize) {
                $query = $readDB->prepare('SELECT flights.flight_id, airline_name, airline_code, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, price, airlines.total_seats - COUNT(CASE WHEN seats.is_available = 0 THEN 1 END) AS available_seats FROM flights INNER JOIN airlines ON flights.airline_id = airlines.airline_id INNER JOIN seats ON flights.flight_id = seats.flight_id WHERE flights.flight_id BETWEEN 1 AND :pageSize GROUP BY flights.flight_id ');
                $query->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
            } else {
                $query = $readDB->prepare('SELECT flights.flight_id, airline_name, airline_code, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, price, airlines.total_seats - COUNT(CASE WHEN seats.is_available = 0 THEN 1 END) AS available_seats FROM flights INNER JOIN airlines ON flights.airline_id = airlines.airline_id INNER JOIN seats ON flights.flight_id = seats.flight_id GROUP BY flights.flight_id');
            }
    
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Flight not found");
                $response->send();
                exit();
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Flights = new Flights($row['flight_id'], $row['airline_name'], $row['airline_code'], $row['origin'], $row['destination'], $row['departure_time'], $row['arrival_time'], $row['price'], $row['available_seats']);
                $flightsArray[] = $Flights->returnFlightsArray();
                
            }
            $returnData = array();
            if ($page) {
                $returnData['total_flights'] = $flightsCount;
                $returnData['total_pages'] = $numOfPages;
            }
            $returnData['rows_returned'] = $rowCount;
            $returnData['flights'] = $flightsArray;
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        } catch (FlightsException $ex) {
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
        $response = new Response();
        $response->setHTTPStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit();
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
    
            $destination_updated = false;
            $departureTime_updated = false;
            $arrivalTime_updated = false;
            $price_updated = false;
            $queryFields = "";
    
            if (isset($jsonData->destination)) {
                $destination_updated = true;
                $queryFields .= "destination = :destination,";
            }
            if (isset($jsonData->departure_time)) {
                $departureTime_updated = true;
                $queryFields .= "departure_time = STR_TO_DATE(:departure_time, '%d/%m/%Y %H:%i'),";
            }
            if (isset($jsonData->arrival_time)) {
                $arrivalTime_updated = true;
                $queryFields .= "arrival_time = STR_TO_DATE(:arrival_time, '%d/%m/%Y %H:%i'),";
            }
            if (isset($jsonData->price)) {
                $price_updated = true;
                $queryFields .= "price = :price,";
            }
    
            $queryFields = rtrim($queryFields, ", ");
    
            // Pengecekan Field
            if ($destination_updated === false && $departureTime_updated === false && $arrivalTime_updated === false && $price_updated === false) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("No flight fields provided");
                $response->send();
                exit;
            }
    
            // Pengecekan Parameter id
            if (array_key_exists("id", $_GET)) {
                $FlightID = $_GET['id'];
                if ($FlightID == '' || !is_numeric($FlightID)) {
                    $response = new Response();
                    $response->setHTTPStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("Flight ID cannot be blank or must be numeric");
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
    
            $query = $readDB->prepare('SELECT flight_id, airline_id, origin, destination, departure_time, arrival_time, price FROM flights WHERE flight_id=:id');
            $query->bindParam(':id', $FlightID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No flight found to update");
                $response->send();
                exit;
            }
    
            $query = $writeDB->prepare('ALTER TABLE airlines AUTO_INCREMENT 0');
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
            $queryString = "UPDATE flights SET " . $queryFields . " WHERE flight_id = :id";
            $query = $writeDB->prepare($queryString);
    
            if ($destination_updated === true) {
                $Destination = $jsonData->destination;
                $query->bindParam(':destination', $Destination, PDO::PARAM_STR);
            }
            if ($departureTime_updated === true) {
                $DepartureTime = $jsonData->departure_time;
                $query->bindParam(':departure_time', $DepartureTime, PDO::PARAM_STR);
            }
            if ($arrivalTime_updated === true) {
                $ArrivalTime = $jsonData->arrival_time;
                $query->bindParam(':arrival_time', $ArrivalTime, PDO::PARAM_STR);
            }
            if ($price_updated === true) {
                $Price = $jsonData->price;
                $query->bindParam(':price', $Price, PDO::PARAM_STR);
            }
    
            $query->bindParam(':id', $FlightID, PDO::PARAM_INT);
            $query->execute();
            // Pengecekan Inputan User (Apakah Value Yang Diinput Sama Atau Tidak)
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Flight not updated - given values may be the same as the stored values");
                $response->send();
                exit;
            }
    
            // Pengecekan Flight (Apakah ada atau tidak)
            $query = $readDB->prepare('SELECT flight_id, airline_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i"), DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i"), price FROM flights WHERE flight_id=:id');
            $query->bindParam(':id', $FlightID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No flight found");
                $response->send();
                exit;
            }
    
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Flights= array();
                $Flights['flight_id'] = $row['flight_id'];
                $Flights['airline_id'] = $row['airline_id'];
                $Flights['origin'] = $row['origin'];
                $Flights['destination'] = $row['destination'];
                $Flights['departure_time'] = $row['DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i")'];
                $Flights['arrival_time'] = $row['DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i")'];
                $Flights['price'] = $row['price'];
                $flightsArray[] = $Flights;
            }
    
            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['flights'] = $flightsArray;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("Flights Updated");
            $response->setData($returnData);
            $response->send();
    
        } catch (FlightsException $ex) {
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        } catch (PDOException $ex) {
            if ($ex->getCode() == '23000' && strpos($ex->getMessage(), 'Duplicate entry') == true && strpos($ex->getMessage(), 'destination') == true) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Destination already exists");
                $response->send();
                exit();
            } else if ($ex->getCode() == '23000') {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Airline ID is not exists in database.");
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
        $response = new Response();
        $response->setHTTPStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit();
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
        if (array_key_exists("id", $_GET)) {
            $FlightID = $_GET['id'];
            if ($FlightID == '' || !is_numeric($FlightID)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Flights ID cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $FlightID = '';
        }
        if (array_key_exists("origin", $_GET)) {
            $Origin = $_GET['origin'];
            if ($Origin == '' || is_numeric($Origin)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Origin cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $Origin = '';
        }
        if (array_key_exists("destination", $_GET)) {
            $Destination = $_GET['destination'];
            if ($Destination == '' || is_numeric($Destination)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Destination cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $Destination = '';
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
            if ($FlightID) {
                $query = $readDB->prepare('SELECT flights.flight_id, airline_name, airline_code, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, price, airlines.total_seats - COUNT(CASE WHEN seats.is_available = 0 THEN 1 END) AS available_seats FROM flights INNER JOIN airlines ON flights.airline_id = airlines.airline_id INNER JOIN seats ON flights.flight_id = seats.flight_id  WHERE flights.flight_id=:id GROUP BY flights.flight_id');
                $query->bindParam(':id', $FlightID, PDO::PARAM_INT);
            } else if ($Origin) {
                $query = $readDB->prepare('SELECT flights.flight_id, airline_name, airline_code, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, price, airlines.total_seats - COUNT(CASE WHEN seats.is_available = 0 THEN 1 END) AS available_seats FROM flights INNER JOIN airlines ON flights.airline_id = airlines.airline_id INNER JOIN seats ON flights.flight_id = seats.flight_id WHERE origin=:origin GROUP BY flights.flight_id');
                $query->bindParam(':origin', $Origin, PDO::PARAM_STR);
            } else if ($Destination) {
                $query = $readDB->prepare('SELECT flights.flight_id, airline_name, airline_code, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, price, airlines.total_seats - COUNT(CASE WHEN seats.is_available = 0 THEN 1 END) AS available_seats FROM flights INNER JOIN airlines ON flights.airline_id = airlines.airline_id INNER JOIN seats ON flights.flight_id = seats.flight_id WHERE destination=:destination GROUP BY flights.flight_id ');
                $query->bindParam(':destination', $Destination, PDO::PARAM_STR);
            } else if ($page) {
                $queryCount = $readDB->prepare('select count(flight_id) as totalNoOfFlights from flights');
                $queryCount->execute();
                $row = $queryCount->fetch(PDO::FETCH_ASSOC);
                $flightsCount = intval($row['totalNoOfFlights']);
                $numOfPages = ceil($flightsCount / $limitPerPage);
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
                $query = $readDB->prepare('SELECT flights.flight_id, airline_name, airline_code, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, price, airlines.total_seats - COUNT(CASE WHEN seats.is_available = 0 THEN 1 END) AS available_seats FROM flights INNER JOIN airlines ON flights.airline_id = airlines.airline_id INNER JOIN seats ON flights.flight_id = seats.flight_id GROUP BY flights.flight_id limit :pglimit offset :offset');
                $query->bindParam(':pglimit', $limitPerPage, PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            } else if ($pageSize) {
                $query = $readDB->prepare('SELECT flights.flight_id, airline_name, airline_code, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, price, airlines.total_seats - COUNT(CASE WHEN seats.is_available = 0 THEN 1 END) AS available_seats FROM flights INNER JOIN airlines ON flights.airline_id = airlines.airline_id INNER JOIN seats ON flights.flight_id = seats.flight_id WHERE flights.flight_id BETWEEN 1 AND :pageSize GROUP BY flights.flight_id ');
                $query->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
            } else {
                $query = $readDB->prepare('SELECT flights.flight_id, airline_name, airline_code, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, price, airlines.total_seats - COUNT(CASE WHEN seats.is_available = 0 THEN 1 END) AS available_seats FROM flights INNER JOIN airlines ON flights.airline_id = airlines.airline_id INNER JOIN seats ON flights.flight_id = seats.flight_id GROUP BY flights.flight_id');
            }
    
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Flight not found");
                $response->send();
                exit();
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Flights = new Flights($row['flight_id'], $row['airline_name'], $row['airline_code'], $row['origin'], $row['destination'], $row['departure_time'], $row['arrival_time'], $row['price'], $row['available_seats']);
                $flightsArray[] = $Flights->returnFlightsArray();
                
            }
            $returnData = array();
            if ($page) {
                $returnData['total_flights'] = $flightsCount;
                $returnData['total_pages'] = $numOfPages;
            }
            $returnData['rows_returned'] = $rowCount;
            $returnData['flights'] = $flightsArray;
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        } catch (FlightsException $ex) {
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
