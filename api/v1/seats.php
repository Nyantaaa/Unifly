<?php
session_start();
require_once('../controller/DBConfig.php');
require_once('../model/Response.php');
require_once('../model/Seats.php');
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
            $SeatID = $_GET['id'];
            if ($SeatID == '' || !is_numeric($SeatID)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Seats ID cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $SeatID = '';
        }
        if (array_key_exists("seat", $_GET)) {
            $SeatNumber = $_GET['seat'];
            if ($SeatNumber == '' || is_numeric($SeatNumber)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Seat cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $SeatNumber = '';
        }
        if (array_key_exists("available", $_GET)) {
            $Available = $_GET['available'];
            if ($Available == '' || !is_numeric($Available)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Available cannot be blank or must be numeric");
                $response->send();
                exit();
            }
            if (($Available != 0 && $Available != 1)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Available cannot be blank or must be 0/1");
                $response->send();
                exit();
            }
        } else {
            $Available = '';
        }
        if (array_key_exists("page", $_GET)) {
            $page = $_GET['page'];
            $limitPerPage = 6;
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
            if ($SeatID) {
                $query = $readDB->prepare('SELECT seat_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, seat_number, is_available FROM seats INNER JOIN flights ON seats.flight_id = flights.flight_id WHERE seat_id=:id');
                $query->bindParam(':id', $SeatID, PDO::PARAM_INT);
            } else if ($SeatNumber) {
                $query = $readDB->prepare('SELECT seat_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, seat_number, is_available FROM seats INNER JOIN flights ON seats.flight_id = flights.flight_id WHERE seat_number=:seat');
                $query->bindParam(':seat', $SeatNumber, PDO::PARAM_STR);
            } else if ($Available == 0 || $Available == 1) {
                $query = $readDB->prepare('SELECT seat_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, seat_number, is_available FROM seats INNER JOIN flights ON seats.flight_id = flights.flight_id WHERE is_available=:available');
                $query->bindParam(':available', $Available, PDO::PARAM_INT);
            } else if ($page) {
                $queryCount = $readDB->prepare('select count(seat_id) as totalNoOfSeats from seats');
                $queryCount->execute();
                $row = $queryCount->fetch(PDO::FETCH_ASSOC);
                $seatsCount = intval($row['totalNoOfSeats']);
                $numOfPages = ceil($seatsCount / $limitPerPage);
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
                $query = $readDB->prepare('SELECT seat_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, seat_number, is_available FROM seats INNER JOIN flights ON seats.flight_id = flights.flight_id limit :pglimit offset :offset');
                $query->bindParam(':pglimit', $limitPerPage, PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            } else if ($pageSize) {
                $query = $readDB->prepare('SELECT seat_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, seat_number, is_available FROM seats INNER JOIN flights ON seats.flight_id = flights.flight_id WHERE seat_id BETWEEN 1 AND :pageSize');
                $query->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
            } else {
                $query = $readDB->prepare('SELECT seat_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, seat_number, is_available FROM seats INNER JOIN flights ON seats.flight_id = flights.flight_id');
            }
    
            $query->execute();
            $rowCount = $query->rowCount();
    
            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Seat not found");
                $response->send();
                exit();
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Seats = new Seats($row['seat_id'], $row['origin'], $row['destination'], $row['departure_time'], $row['arrival_time'], $row['seat_number'], $row['is_available']);
                $seatsArray[] = $Seats->returnSeatsArray();
            }
    
            $returnData = array();
            if ($page) {
                $returnData['total_seats'] = $seatsCount;
                $returnData['total_pages'] = $numOfPages;
            }
            $returnData['rows_returned'] = $rowCount;
            $returnData['seats'] = $seatsArray;
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        } catch (SeatsException $ex) {
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
    
            $isAvailable_updated = false;
            $queryFields = "";
    
            if (isset($jsonData->is_available)) {
                if ($jsonData->is_available == 1 || $jsonData->is_available == 0) {
                    $isAvailable_updated = true;
                    $queryFields .= "is_available = :is_available, ";
                } else {
                    $response = new Response();
                    $response->setHttpStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("Is Available must be 0 or 1");
                    $response->send();
                    exit;
                }
            }
    
            $queryFields = rtrim($queryFields, ", ");
    
            // Pengecekan Field
            if ($isAvailable_updated === false) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("No seat fields provided");
                $response->send();
                exit;
            }
    
            // Pengecekan Parameter id
            if (array_key_exists("id", $_GET)) {
                $SeatID = $_GET['id'];
                if ($SeatID == '' || !is_numeric($SeatID)) {
                    $response = new Response();
                    $response->setHTTPStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("Seat ID cannot be blank or must be numeric");
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
    
            $query = $readDB->prepare('SELECT seat_id, flight_id, seat_number, is_available FROM seats WHERE seat_id=:id');
            $query->bindParam(':id', $SeatID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No seat found to update");
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
            $queryString = "UPDATE seats SET " . $queryFields . " WHERE seat_id = :id";
            $query = $writeDB->prepare($queryString);
    
            if ($isAvailable_updated === true) {
                $isAvailable = $jsonData->is_available;
                $query->bindParam(':is_available', $isAvailable, PDO::PARAM_STR);
            }
    
    
            $query->bindParam(':id', $SeatID, PDO::PARAM_INT);
            $query->execute();
            // Pengecekan Inputan User (Apakah Value Yang Diinput Sama Atau Tidak)
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Seat not updated - given values may be the same as the stored values");
                $response->send();
                exit;
            }
    
            // Pengecekan Seat (Apakah ada atau tidak)
            $query = $readDB->prepare('SELECT seat_id, flight_id, seat_number, is_available FROM seats WHERE seat_id=:id');
            $query->bindParam(':id', $SeatID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No seat found");
                $response->send();
                exit;
            }
    
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Seats = array();
                $Seats['seat_id'] = $row['seat_id'];
                $Seats['flight_id'] = $row['flight_id'];
                $Seats['seat_number'] = $row['seat_number'];
                $Seats['is_available'] = $row['is_available'];
                $seatsArray[] = $Seats;
            }
    
            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['seats'] = $seatsArray;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("Seat Updated");
            $response->setData($returnData);
            $response->send();
    
        } catch (SeatsException $ex) {
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
            $SeatID = $_GET['id'];
            if ($SeatID == '' || !is_numeric($SeatID)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Seats ID cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $SeatID = '';
        }
        if (array_key_exists("seat", $_GET)) {
            $SeatNumber = $_GET['seat'];
            if ($SeatNumber == '' || is_numeric($SeatNumber)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Seat cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $SeatNumber = '';
        }
        if (array_key_exists("available", $_GET)) {
            $Available = $_GET['available'];
            if ($Available == '' || !is_numeric($Available)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Available cannot be blank or must be numeric");
                $response->send();
                exit();
            }
            if (($Available != 0 && $Available != 1)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Available cannot be blank or must be 0/1");
                $response->send();
                exit();
            }
        } else {
            $Available = '';
        }
        if (array_key_exists("page", $_GET)) {
            $page = $_GET['page'];
            $limitPerPage = 6;
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
            if ($SeatID) {
                $query = $readDB->prepare('SELECT seat_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, seat_number, is_available FROM seats INNER JOIN flights ON seats.flight_id = flights.flight_id WHERE seat_id=:id');
                $query->bindParam(':id', $SeatID, PDO::PARAM_INT);
            } else if ($SeatNumber) {
                $query = $readDB->prepare('SELECT seat_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, seat_number, is_available FROM seats INNER JOIN flights ON seats.flight_id = flights.flight_id WHERE seat_number=:seat');
                $query->bindParam(':seat', $SeatNumber, PDO::PARAM_STR);
            } else if ($Available == 0 || $Available == 1) {
                $query = $readDB->prepare('SELECT seat_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, seat_number, is_available FROM seats INNER JOIN flights ON seats.flight_id = flights.flight_id WHERE is_available=:available');
                $query->bindParam(':available', $Available, PDO::PARAM_INT);
            } else if ($page) {
                $queryCount = $readDB->prepare('select count(seat_id) as totalNoOfSeats from seats');
                $queryCount->execute();
                $row = $queryCount->fetch(PDO::FETCH_ASSOC);
                $seatsCount = intval($row['totalNoOfSeats']);
                $numOfPages = ceil($seatsCount / $limitPerPage);
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
                $query = $readDB->prepare('SELECT seat_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, seat_number, is_available FROM seats INNER JOIN flights ON seats.flight_id = flights.flight_id limit :pglimit offset :offset');
                $query->bindParam(':pglimit', $limitPerPage, PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            } else if ($pageSize) {
                $query = $readDB->prepare('SELECT seat_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, seat_number, is_available FROM seats INNER JOIN flights ON seats.flight_id = flights.flight_id WHERE seat_id BETWEEN 1 AND :pageSize');
                $query->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
            } else {
                $query = $readDB->prepare('SELECT seat_id, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") as departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") as arrival_time, seat_number, is_available FROM seats INNER JOIN flights ON seats.flight_id = flights.flight_id');
            }
    
            $query->execute();
            $rowCount = $query->rowCount();
    
            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Seat not found");
                $response->send();
                exit();
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Seats = new Seats($row['seat_id'], $row['origin'], $row['destination'], $row['departure_time'], $row['arrival_time'], $row['seat_number'], $row['is_available']);
                $seatsArray[] = $Seats->returnSeatsArray();
            }
    
            $returnData = array();
            if ($page) {
                $returnData['total_seats'] = $seatsCount;
                $returnData['total_pages'] = $numOfPages;
            }
            $returnData['rows_returned'] = $rowCount;
            $returnData['seats'] = $seatsArray;
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        } catch (SeatsException $ex) {
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
