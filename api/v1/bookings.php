<?php
session_start();
require_once('../controller/DBConfig.php');
require_once('../model/Response.php');
require_once('../model/Bookings.php');
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
    $response->addMessage($ex->getMessage());
    $response->send();
    exit;
}

// Pengecekan Account Level
if ($returned_level == 0) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (array_key_exists("id", $_GET)) {
            $BookingID = $_GET['id'];
            if ($BookingID == '' || !is_numeric($BookingID)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Booking ID cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $BookingID = '';
        }
        if (array_key_exists("fullname", $_GET)) {
            $Fullname = $_GET['fullname'];
            if ($Fullname == '' || is_numeric($Fullname)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Fullname cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $Fullname = '';
        }
        if (array_key_exists("seat", $_GET)) {
            $Seat = $_GET['seat'];
            if ($Seat == '' || is_numeric($Seat)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Seat cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $Seat = '';
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
            if ($BookingID) {
                $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id SEPARATOR ", ") as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passengers, price*COUNT(seats.seat_number) AS total_price 
                FROM bookings
                INNER JOIN users ON bookings.user_id = users.user_id
                INNER JOIN flights ON bookings.flight_id = flights.flight_id
                INNER JOIN seats ON bookings.seat_id = seats.seat_id
                INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
                WHERE seats.is_available = 0 AND booking_id=:id
                GROUP BY bookings.user_id, bookings.flight_id');
                $query->bindParam(':id', $BookingID, PDO::PARAM_INT);
            } else if ($Fullname) {
                $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id SEPARATOR ", ") as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passengers, price*COUNT(seats.seat_number) AS total_price 
                FROM bookings
                INNER JOIN users ON bookings.user_id = users.user_id
                INNER JOIN flights ON bookings.flight_id = flights.flight_id
                INNER JOIN seats ON bookings.seat_id = seats.seat_id
                INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
                WHERE seats.is_available = 0 AND fullname=:fullname
                GROUP BY bookings.user_id, bookings.flight_id');
                $query->bindParam(':fullname', $Fullname, PDO::PARAM_STR);
            } else if ($Seat) {
                $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id SEPARATOR ", ") as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passengers, price*COUNT(seats.seat_number) AS total_price 
                FROM bookings
                INNER JOIN users ON bookings.user_id = users.user_id
                INNER JOIN flights ON bookings.flight_id = flights.flight_id
                INNER JOIN seats ON bookings.seat_id = seats.seat_id
                INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
                WHERE seats.is_available = 0 AND seats.seat_number=:seat
                GROUP BY bookings.user_id, bookings.flight_id');
                $query->bindParam(':seat', $Seat, PDO::PARAM_STR);
            } else if ($page) {
                $queryCount = $readDB->prepare('select count(booking_id) as totalNoOfBookings from bookings');
                $queryCount->execute();
                $row = $queryCount->fetch(PDO::FETCH_ASSOC);
                $bookingsCount = intval($row['totalNoOfBookings']);
                $numOfPages = ceil($bookingsCount / $limitPerPage);
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
                $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id SEPARATOR ", ") as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passengers, price*COUNT(seats.seat_number) AS total_price 
                FROM bookings
                INNER JOIN users ON bookings.user_id = users.user_id
                INNER JOIN flights ON bookings.flight_id = flights.flight_id
                INNER JOIN seats ON bookings.seat_id = seats.seat_id
                INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
                WHERE seats.is_available = 0 
                GROUP BY bookings.booking_id limit :pglimit offset :offset');
                $query->bindParam(':pglimit', $limitPerPage, PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, PDO::PARAM_INT);

            } else if ($pageSize) {
                $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id SEPARATOR ", ") as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passengers, price*COUNT(seats.seat_number) AS total_price 
                FROM bookings
                INNER JOIN users ON bookings.user_id = users.user_id
                INNER JOIN flights ON bookings.flight_id = flights.flight_id
                INNER JOIN seats ON bookings.seat_id = seats.seat_id
                INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
                WHERE seats.is_available = 0 AND booking_id BETWEEN 1 AND :pageSize
                GROUP BY bookings.user_id, bookings.flight_id');
                $query->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
            } else {
                $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id SEPARATOR ", ") as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passengers, price*COUNT(seats.seat_number) AS total_price 
                FROM bookings
                INNER JOIN users ON bookings.user_id = users.user_id
                INNER JOIN flights ON bookings.flight_id = flights.flight_id
                INNER JOIN seats ON bookings.seat_id = seats.seat_id
                INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
                WHERE seats.is_available = 0
                GROUP BY bookings.user_id, bookings.flight_id');
            }

            $query->execute();
            $rowCount = $query->rowCount();

            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Booking not found");
                $response->send();
                exit();
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Bookings = new Bookings($row['booking_id'], $row['fullname'], $row['airline_name'], $row['origin'], $row['destination'], $row['departure_time'], $row['arrival_time'], $row['booking_date'], $row['seat_number'], $row['total_passengers'], $row['total_price'], );
                $bookingsArray[] = $Bookings->returnBookingsArray();
            }

            $returnData = array();
            if ($page) {
                $returnData['total_bookings'] = $bookingsCount;
                $returnData['total_pages'] = $numOfPages;
            }
            $returnData['rows_returned'] = $rowCount;
            $returnData['bookings'] = $bookingsArray;
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        } catch (BookingsException $ex) {
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
            // Pengecekan Isi Booking
            if (!isset($jsonData->fullname) || strlen($jsonData->fullname) <= 0 || strlen($jsonData->fullname) > 255) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Fullname field cannot be blank or more than 255!");
                $response->send();
                exit;
            } else if (!isset($jsonData->destination) || strlen($jsonData->destination) <= 0 || strlen($jsonData->destination) > 255) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Please select your Destination...");
                $response->send();
                exit;
            } else if (!isset($jsonData->seat_number) || strlen($jsonData->seat_number) <= 0 || strlen($jsonData->seat_number) > 255) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Please select your Seat Number...");
                $response->send();
                exit;
            }

            $Fullname = $jsonData->fullname;
            $Destination = $jsonData->destination;
            $SeatNumber = $jsonData->seat_number;

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
            $query = $writeDB->prepare("INSERT INTO bookings_info (fullname, destination, seat_number, info_created, booking_status) values (:fullname, :destination, :seat_number, CURRENT_TIMESTAMP(), 'Created')");
            $query->bindParam(':fullname', $Fullname, PDO::PARAM_STR);
            $query->bindParam(':destination', $Destination, PDO::PARAM_STR);
            $query->bindParam(':seat_number', $SeatNumber, PDO::PARAM_STR);
            $query->execute();

            $rowCount = $query->rowCount();
            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to create booking");
                $response->send();
                exit;
            }

            $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id) as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passenger, price*COUNT(seats.seat_number) AS total_price 
            FROM bookings
            INNER JOIN users ON bookings.user_id = users.user_id
            INNER JOIN flights ON bookings.flight_id = flights.flight_id
            INNER JOIN seats ON bookings.seat_id = seats.seat_id
            INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
            WHERE seats.is_available = 0 AND booking_id = (SELECT booking_id FROM bookings ORDER BY booking_id DESC LIMIT 1)
            GROUP BY bookings.user_id, bookings.flight_id');
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to retrieve booking after creation");
                $response->send();
                exit;
            }

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Booking = new Bookings($row['booking_id'], $row['fullname'], $row['airline_name'], $row['origin'], $row['destination'], $row['departure_time'], $row['arrival_time'], $row['booking_date'], $row['seat_number'], $row['total_passenger'], $row['total_price']);
                $bookingArray[] = $Booking->returnBookingsArray();
            }

            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['bookings'] = $bookingArray;
            $response = new Response();
            $response->setHTTPStatusCode(201);
            $response->setSuccess(true);
            $response->addMessage("Booking Created");
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
            if ($ex->getCode() == '23000' && strpos($ex->getMessage(), 'user_id') == true && strpos($ex->getMessage(), 'cannot be null') == true) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Fullname is not exists in our database.");
                $response->send();
                exit();
            } else if ($ex->getCode() == '23000' && strpos($ex->getMessage(), 'flight_id') == true && strpos($ex->getMessage(), 'cannot be null') == true) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Destination is not exists in our flights.");
                $response->send();
                exit();
            } else if ($ex->getCode() == '23000' && strpos($ex->getMessage(), 'seat_id') == true && strpos($ex->getMessage(), 'cannot be null') == true) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Seat Number is not exists in our flights.");
                $response->send();
                exit();
            } else if ($ex->getCode() == '23000' && strpos($ex->getMessage(), 'Duplicate entry') == true && strpos($ex->getMessage(), 'seat_id') == true) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Seat is not available");
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
        $response = new Response();
        $response->setHTTPStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit();
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
                $BookingID = $_GET['id'];
                if ($BookingID == '') {
                    $response = new Response();
                    $response->setHTTPStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("Booking ID cannot be blank or must be numeric");
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
            $query = $writeDB->prepare('DELETE FROM bookings WHERE booking_id IN (:id)');
            $query->bindParam(':id', $BookingID, PDO::PARAM_STR);
            $query->execute();
            $rowCount = $query->rowCount();

            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No booking found to delete");
                $response->send();
                exit;
            }
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("Booking Deleted");
            $response->send();
            exit();

        } catch (BookingsException $ex) {
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
        if (array_key_exists("id", $_GET)) {
            $BookingID = $_GET['id'];
            if ($BookingID == '' || !is_numeric($BookingID)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Booking ID cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $BookingID = '';
        }
        if (array_key_exists("fullname", $_GET)) {
            $Fullname = $_GET['fullname'];
            if ($Fullname == '' || is_numeric($Fullname)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Fullname cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $Fullname = '';
        }
        if (array_key_exists("seat", $_GET)) {
            $Seat = $_GET['seat'];
            if ($Seat == '' || is_numeric($Seat)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Seat cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $Seat = '';
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
            if ($BookingID) {
                $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id SEPARATOR ", ") as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passengers, price*COUNT(seats.seat_number) AS total_price 
                FROM bookings
                INNER JOIN users ON bookings.user_id = users.user_id
                INNER JOIN flights ON bookings.flight_id = flights.flight_id
                INNER JOIN seats ON bookings.seat_id = seats.seat_id
                INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
                WHERE seats.is_available = 0 AND booking_id=:id AND bookings.user_id = :user_id
                GROUP BY bookings.user_id, bookings.flight_id');
                $query->bindParam(':id', $BookingID, PDO::PARAM_INT);
                $query->bindParam(':user_id', $returned_userid, PDO::PARAM_INT);
            } else if ($Fullname) {
                $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id SEPARATOR ", ") as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passengers, price*COUNT(seats.seat_number) AS total_price 
                FROM bookings
                INNER JOIN users ON bookings.user_id = users.user_id
                INNER JOIN flights ON bookings.flight_id = flights.flight_id
                INNER JOIN seats ON bookings.seat_id = seats.seat_id
                INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
                WHERE seats.is_available = 0 AND fullname=:fullname AND bookings.user_id = :user_id
                GROUP BY bookings.user_id, bookings.flight_id');
                $query->bindParam(':fullname', $Fullname, PDO::PARAM_STR);
                $query->bindParam(':user_id', $returned_userid, PDO::PARAM_INT);
            } else if ($Seat) {
                $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id SEPARATOR ", ") as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passengers, price*COUNT(seats.seat_number) AS total_price 
                FROM bookings
                INNER JOIN users ON bookings.user_id = users.user_id
                INNER JOIN flights ON bookings.flight_id = flights.flight_id
                INNER JOIN seats ON bookings.seat_id = seats.seat_id
                INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
                WHERE seats.is_available = 0 AND seats.seat_number=:seat AND bookings.user_id = :user_id
                GROUP BY bookings.user_id, bookings.flight_id');
                $query->bindParam(':seat', $Seat, PDO::PARAM_STR);
                $query->bindParam(':user_id', $returned_userid, PDO::PARAM_INT);
            } else if ($page) {
                $queryCount = $readDB->prepare('select count(booking_id) as totalNoOfBookings from bookings');
                $queryCount->execute();
                $row = $queryCount->fetch(PDO::FETCH_ASSOC);
                $bookingsCount = intval($row['totalNoOfBookings']);
                $numOfPages = ceil($bookingsCount / $limitPerPage);
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
                $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id SEPARATOR ", ") as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passengers, price*COUNT(seats.seat_number) AS total_price 
                FROM bookings
                INNER JOIN users ON bookings.user_id = users.user_id
                INNER JOIN flights ON bookings.flight_id = flights.flight_id
                INNER JOIN seats ON bookings.seat_id = seats.seat_id
                INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
                WHERE seats.is_available = 0 AND limit :pglimit offset :offset AND bookings.user_id = :user_id
                GROUP BY bookings.booking_id');
                $query->bindParam(':pglimit', $limitPerPage, PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, PDO::PARAM_INT);
                $query->bindParam(':user_id', $returned_userid, PDO::PARAM_INT);

            } else if ($pageSize) {
                $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id SEPARATOR ", ") as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passengers, price*COUNT(seats.seat_number) AS total_price 
                FROM bookings
                INNER JOIN users ON bookings.user_id = users.user_id
                INNER JOIN flights ON bookings.flight_id = flights.flight_id
                INNER JOIN seats ON bookings.seat_id = seats.seat_id
                INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
                WHERE seats.is_available = 0 AND booking_id BETWEEN 1 AND :pageSize AND bookings.user_id = :user_id
                GROUP BY bookings.user_id, bookings.flight_id');
                $query->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
                $query->bindParam(':user_id', $returned_userid, PDO::PARAM_INT);
            } else {
                $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id SEPARATOR ", ") as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passengers, price*COUNT(seats.seat_number) AS total_price 
                FROM bookings
                INNER JOIN users ON bookings.user_id = users.user_id
                INNER JOIN flights ON bookings.flight_id = flights.flight_id
                INNER JOIN seats ON bookings.seat_id = seats.seat_id
                INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
                WHERE seats.is_available = 0 AND bookings.user_id = :user_id
                GROUP BY bookings.user_id, bookings.flight_id');
                $query->bindParam(':user_id', $returned_userid, PDO::PARAM_INT);
            }

            $query->execute();
            $rowCount = $query->rowCount();

            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Booking not found");
                $response->send();
                exit();
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Bookings = new Bookings($row['booking_id'], $row['fullname'], $row['airline_name'], $row['origin'], $row['destination'], $row['departure_time'], $row['arrival_time'], $row['booking_date'], $row['seat_number'], $row['total_passengers'], $row['total_price'], );
                $bookingsArray[] = $Bookings->returnBookingsArray();
            }

            $returnData = array();
            if ($page) {
                $returnData['total_bookings'] = $bookingsCount;
                $returnData['total_pages'] = $numOfPages;
            }
            $returnData['rows_returned'] = $rowCount;
            $returnData['bookings'] = $bookingsArray;
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        } catch (BookingsException $ex) {
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
            // Pengecekan Isi Booking
            if (!isset($jsonData->fullname) || strlen($jsonData->fullname) <= 0 || strlen($jsonData->fullname) > 255) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Fullname field cannot be blank or more than 255!");
                $response->send();
                exit;
            } else if (!isset($jsonData->destination) || strlen($jsonData->destination) <= 0 || strlen($jsonData->destination) > 255) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Destination field cannot be blank or more than 255!");
                $response->send();
                exit;
            } else if (!isset($jsonData->seat_number) || strlen($jsonData->seat_number) <= 0 || strlen($jsonData->seat_number) > 255) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Seat Number field cannot be blank or more than 255!");
                $response->send();
                exit;
            }



            $query = $readDB->prepare('SELECT fullname FROM users WHERE user_id = :user_id');
            $query->bindParam(':user_id', $returned_userid, PDO::PARAM_STR);
            $query->execute();

            $rowCount = $query->rowCount();
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if ($jsonData->fullname == $row['fullname']) {
                $Fullname = $jsonData->fullname;
                $Destination = $jsonData->destination;
                $SeatNumber = $jsonData->seat_number;
            } else if ($jsonData->fullname != $row['fullname']) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Please input the fullname according to your account");
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
            $query = $writeDB->prepare("INSERT INTO bookings_info (fullname, destination, seat_number, info_created) values (:fullname, :destination, :seat_number, CURRENT_TIMESTAMP())");
            $query->bindParam(':fullname', $Fullname, PDO::PARAM_STR);
            $query->bindParam(':destination', $Destination, PDO::PARAM_STR);
            $query->bindParam(':seat_number', $SeatNumber, PDO::PARAM_STR);
            $query->execute();

            $rowCount = $query->rowCount();
            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to create booking");
                $response->send();
                exit;
            }

            $query = $readDB->prepare('SELECT GROUP_CONCAT(booking_id) as booking_id, fullname, airline_name, origin, destination, DATE_FORMAT(departure_time, "%d/%m/%Y %H:%i") AS departure_time, DATE_FORMAT(arrival_time, "%d/%m/%Y %H:%i") AS arrival_time, DATE_FORMAT(booking_date, "%d/%m/%Y %H:%i") AS booking_date, GROUP_CONCAT(DISTINCT seats.seat_number SEPARATOR ", ") AS seat_number, COUNT(seats.seat_number) AS total_passenger, price*COUNT(seats.seat_number) AS total_price 
            FROM bookings
            INNER JOIN users ON bookings.user_id = users.user_id
            INNER JOIN flights ON bookings.flight_id = flights.flight_id
            INNER JOIN seats ON bookings.seat_id = seats.seat_id
            INNER JOIN airlines ON flights.airline_id = airlines.airline_id 
            WHERE seats.is_available = 0 AND booking_id = (SELECT booking_id FROM bookings ORDER BY booking_id DESC LIMIT 1)
            GROUP BY bookings.user_id, bookings.flight_id');
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to retrieve booking after creation");
                $response->send();
                exit;
            }

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Booking = new Bookings($row['booking_id'], $row['fullname'], $row['airline_name'], $row['origin'], $row['destination'], $row['departure_time'], $row['arrival_time'], $row['booking_date'], $row['seat_number'], $row['total_passenger'], $row['total_price']);
                $bookingArray[] = $Booking->returnBookingsArray();
            }

            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['bookings'] = $bookingArray;
            $response = new Response();
            $response->setHTTPStatusCode(201);
            $response->setSuccess(true);
            $response->addMessage("Booking Created");
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
            if ($ex->getCode() == '23000' && strpos($ex->getMessage(), 'user_id') == true && strpos($ex->getMessage(), 'cannot be null') == true) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Fullname is not exists in our database.");
                $response->send();
                exit();
            } else if ($ex->getCode() == '23000' && strpos($ex->getMessage(), 'flight_id') == true && strpos($ex->getMessage(), 'cannot be null') == true) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Destination is not exists in our flights.");
                $response->send();
                exit();
            } else if ($ex->getCode() == '23000' && strpos($ex->getMessage(), 'seat_id') == true && strpos($ex->getMessage(), 'cannot be null') == true) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Seat Number is not exists in our flights.");
                $response->send();
                exit();
            } else if ($ex->getCode() == '23000' && strpos($ex->getMessage(), 'Duplicate entry') == true && strpos($ex->getMessage(), 'seat_id') == true) {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Seat is not available");
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