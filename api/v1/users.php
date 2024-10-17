<?php
session_start();
require_once('../controller/DBConfig.php');
require_once('../model/Response.php');
require_once('../model/Users.php');
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

// Mengambil Data Session
try {
    $query = $writeDB->prepare('SELECT user_id, level FROM users');
    $query->execute();

    $rowCount = $query->rowCount();

    if ($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("Users Not Found");
        $response->send();
        exit;
    }

    $row = $query->fetch(PDO::FETCH_ASSOC);

    // Mengambil User ID, Level, Dan Access Token Expiry
    $returned_userid = $row['user_id'];
    $returned_level = $row['level'];
} catch (PDOException $ex) {
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage($ex->getMessage());
    $response->send();
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
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

    if ($returned_level == 0) {
        if (array_key_exists("id", $_GET)) {
            $UserID = $_GET['id'];
            if ($UserID == '' || !is_numeric($UserID)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Users ID cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $UserID = '';
        }
        if (array_key_exists("username", $_GET)) {
            $Username = $_GET['username'];
            if ($Username == '' || is_numeric($Username)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Username cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $Username = '';
        }
        if (array_key_exists("fullname", $_GET)) {
            $Fullname = $_GET['fullname'];
            if ($Fullname == '' || is_numeric($Fullname)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Fullname cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $Fullname = '';
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
            if ($UserID) {
                $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE user_id = :id');
                $query->bindParam(':id', $UserID, PDO::PARAM_INT);
            } else if ($Username) {
                $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE username = :username');
                $query->bindParam(':username', $Username, PDO::PARAM_STR);
            } else if ($Fullname) {
                $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE fullname = :fullname');
                $query->bindParam(':fullname', $Fullname, PDO::PARAM_STR);
            } else if ($page) {
                $queryCount = $readDB->prepare('select count(user_id) as totalNoOfUsers from users');
                $queryCount->execute();
                $row = $queryCount->fetch(PDO::FETCH_ASSOC);
                $usersCount = intval($row['totalNoOfUsers']);
                $numOfPages = ceil($usersCount / $limitPerPage);
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
                $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users limit :pglimit offset :offset');
                $query->bindParam(':pglimit', $limitPerPage, PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            } else if ($pageSize) {
                $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE user_id BETWEEN 1 AND :pageSize');
                $query->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
            } else {
                $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users');
            }

            $query->execute();
            $rowCount = $query->rowCount();

            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("User not found");
                $response->send();
                exit();
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Users = new Users($row['user_id'], $row['username'], $row['password'], $row['email'], $row['fullname'], $row['address'], $row['telephone']);
                $usersArray[] = $Users->returnUsersArray();
            }

            $returnData = array();
            if ($page) {
                $returnData['total_users'] = $usersCount;
                $returnData['total_pages'] = $numOfPages;
            }
            $returnData['rows_returned'] = $rowCount;
            $returnData['users'] = $usersArray;
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        } catch (UsersException $ex) {
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
    } else if ($returned_level == 1) {
        if (array_key_exists("id", $_GET)) {
            $UserID = $_GET['id'];
            if ($UserID == '' || !is_numeric($UserID)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Users ID cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $UserID = '';
        }
        if (array_key_exists("username", $_GET)) {
            $Username = $_GET['username'];
            if ($Username == '' || is_numeric($Username)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Username cannot be blank or must be alphabet");
                $response->send();
                exit();
            }
        } else {
            $Username = '';
        }
        if (array_key_exists("fullname", $_GET)) {
            $Fullname = $_GET['fullname'];
            if ($Fullname == '' || is_numeric($Fullname)) {
                $response = new Response();
                $response->setHTTPStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Fullname cannot be blank or must be numeric");
                $response->send();
                exit();
            }
        } else {
            $Fullname = '';
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
            if ($UserID) {
                $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE user_id = :id AND user_id = :user_id');
                $query->bindParam(':id', $UserID, PDO::PARAM_INT);
                $query->bindParam(':user_id', $returned_userid, PDO::PARAM_INT);
            } else if ($Username) {
                $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE username = :username AND user_id = :user_id');
                $query->bindParam(':username', $Username, PDO::PARAM_STR);
                $query->bindParam(':user_id', $returned_userid, PDO::PARAM_INT);
            } else if ($Fullname) {
                $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE fullname = :fullname AND user_id = :user_id');
                $query->bindParam(':fullname', $Fullname, PDO::PARAM_STR);
                $query->bindParam(':user_id', $returned_userid, PDO::PARAM_INT);
            } else if ($page) {
                $queryCount = $readDB->prepare('select count(user_id) as totalNoOfUsers from users');
                $queryCount->execute();
                $row = $queryCount->fetch(PDO::FETCH_ASSOC);
                $usersCount = intval($row['totalNoOfUsers']);
                $numOfPages = ceil($usersCount / $limitPerPage);
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
                $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users limit :pglimit offset :offset AND user_id = :user_id');
                $query->bindParam(':pglimit', $limitPerPage, PDO::PARAM_INT);
                $query->bindParam(':offset', $offset, PDO::PARAM_INT);
                $query->bindParam(':user_id', $returned_userid, PDO::PARAM_INT);
            } else if ($pageSize) {
                $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE user_id BETWEEN 1 AND :pageSize AND user_id = :user_id');
                $query->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
                $query->bindParam(':user_id', $returned_userid, PDO::PARAM_INT);
            } else {
                $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE user_id = :user_id');
                $query->bindParam(':user_id', $returned_userid, PDO::PARAM_INT);
            }

            $query->execute();
            $rowCount = $query->rowCount();

            if ($rowCount == 0) {
                $response = new Response();
                $response->setHTTPStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("User not found");
                $response->send();
                exit();
            }
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Users = new Users($row['user_id'], $row['username'], $row['password'], $row['email'], $row['fullname'], $row['address'], $row['telephone']);
                $usersArray[] = $Users->returnUsersArray();
            }

            $returnData = array();
            if ($page) {
                $returnData['total_users'] = $usersCount;
                $returnData['total_pages'] = $numOfPages;
            }
            $returnData['rows_returned'] = $rowCount;
            $returnData['users'] = $usersArray;
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit();
        } catch (UsersException $ex) {
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
        $response->addMessage("You're Hacker !");
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
        // Pengecekan Isi User
        if (!isset($jsonData->username) || strlen($jsonData->username) <= 0 || strlen($jsonData->username) > 255) {
            $response = new Response();
            $response->setHTTPStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Username field cannot be blank or more than 255!");
            $response->send();
            exit;
        } else if (!isset($jsonData->password) || strlen($jsonData->password) <= 0 || strlen($jsonData->password) > 255) {
            $response = new Response();
            $response->setHTTPStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Password field cannot be blank or more than 255!");
            $response->send();
            exit;
        } else if (!isset($jsonData->email) || strlen($jsonData->email) <= 0 || strlen($jsonData->email) > 255) {
            $response = new Response();
            $response->setHTTPStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Email field cannot be blank or more than 255!");
            $response->send();
            exit;
        } else if (!isset($jsonData->fullname) || strlen($jsonData->fullname) <= 0 || strlen($jsonData->fullname) > 255) {
            $response = new Response();
            $response->setHTTPStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Fullname field cannot be blank or more than 255!");
            $response->send();
            exit;
        } else if (!isset($jsonData->address) || strlen($jsonData->address) <= 0 || strlen($jsonData->address) > 255) {
            $response = new Response();
            $response->setHTTPStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Address field cannot be blank or more than 255!");
            $response->send();
            exit;
        } else if (!isset($jsonData->telephone) || !is_numeric($jsonData->telephone)) {
            $response = new Response();
            $response->setHTTPStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Telephone field cannot be blank or must be numeric");
            $response->send();
            exit;
        }

        $Username = $jsonData->username;
        $Password = $jsonData->password;
        $Email = $jsonData->email;
        $Fullname = $jsonData->fullname;
        $Address = $jsonData->address;
        $Telephone = $jsonData->telephone;


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
        $query = $writeDB->prepare("INSERT INTO users (username, password, email, fullname, address, telephone, level) values (:username, :password, :email, :fullname, :address, :telephone, '1')");
        $query->bindParam(':username', $Username, PDO::PARAM_STR);
        $query->bindParam(':password', $Password, PDO::PARAM_STR);
        $query->bindParam(':email', $Email, PDO::PARAM_STR);
        $query->bindParam(':fullname', $Fullname, PDO::PARAM_STR);
        $query->bindParam(':address', $Address, PDO::PARAM_STR);
        $query->bindParam(':telephone', $Telephone, PDO::PARAM_STR);
        $query->execute();

        $rowCount = $query->rowCount();
        if ($rowCount == 0) {
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to create user");
            $response->send();
            exit;
        }
        $lastUserID = $writeDB->lastInsertId();
        $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE user_id = :user_id');
        $query->bindParam(':user_id', $lastUserID, PDO::PARAM_INT);
        $query->execute();
        $rowCount = $query->rowCount();
        if ($rowCount == 0) {
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to retrieve user after creation");
            $response->send();
            exit;
        }

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $User = new Users($row['user_id'], $row['username'], $row['password'], $row['email'], $row['fullname'], $row['address'], $row['telephone']);
            $usersArray[] = $User->returnUsersArray();
        }

        $returnData = array();
        $returnData['rows_returned'] = $rowCount;
        $returnData['users'] = $usersArray;
        $response = new Response();
        $response->setHTTPStatusCode(201);
        $response->setSuccess(true);
        $response->addMessage("User Created");
        $response->setData($returnData);
        $response->send();
        exit;
    } catch (UsersException $ex) {
        $response = new Response();
        $response->setHTTPStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage($ex->getMessage());
        $response->send();
        exit();
    } catch (PDOException $ex) {
        if ($ex->getCode() == '23000') {
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Username already exists.");
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
    // Pengecekan HTTP Authorization
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $response = new Response();
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("Access token is missing from the header");
        $response->send();
        exit;
    }

    // Token
    $accesstoken = $_SERVER['HTTP_AUTHORIZATION'];

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

    if ($returned_level == 0) {
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

            $username_updated = false;
            $password_updated = false;
            $email_updated = false;
            $fullname_updated = false;
            $address_updated = false;
            $telephone_updated = false;
            $queryFields = "";

            if (isset($jsonData->username)) {
                $username_updated = true;
                $queryFields .= "username = :username, ";
            }
            if (isset($jsonData->password)) {
                $password_updated = true;
                $queryFields .= "password = :password, ";
            }
            if (isset($jsonData->email)) {
                $email_updated = true;
                $queryFields .= "email = :email,";
            }
            if (isset($jsonData->fullname)) {
                $fullname_updated = true;
                $queryFields .= "fullname = :fullname,";
            }
            if (isset($jsonData->address)) {
                $address_updated = true;
                $queryFields .= "address = :address,";
            }
            if (isset($jsonData->telephone)) {
                $telephone_updated = true;
                $queryFields .= "telephone = :telephone,";
            }

            $queryFields = rtrim($queryFields, ", ");

            // Pengecekan Field
            if ($username_updated === false && $password_updated === false && $email_updated === false && $fullname_updated === false && $address_updated === false && $telephone_updated === false) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("No user fields provided");
                $response->send();
                exit;
            }

            // Pengecekan Parameter id
            if (array_key_exists("id", $_GET)) {
                $UserID = $_GET['id'];
                if ($UserID == '' || !is_numeric($UserID)) {
                    $response = new Response();
                    $response->setHTTPStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("User ID cannot be blank or must be numeric");
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

            $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE user_id=:id');
            $query->bindParam(':id', $UserID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No user found to update");
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
            $queryString = "UPDATE users SET " . $queryFields . " WHERE user_id = :id";
            $query = $writeDB->prepare($queryString);

            if ($username_updated === true) {
                $Username = $jsonData->username;
                $query->bindParam(':username', $Username, PDO::PARAM_STR);
            }
            if ($password_updated === true) {
                $Password = $jsonData->password;
                $query->bindParam(':password', $Password, PDO::PARAM_STR);
            }
            if ($email_updated === true) {
                $Email = $jsonData->email;
                $query->bindParam(':email', $Email, PDO::PARAM_STR);
            }
            if ($fullname_updated === true) {
                $Fullname = $jsonData->fullname;
                $query->bindParam(':fullname', $Fullname, PDO::PARAM_STR);
            }
            if ($address_updated === true) {
                $Address = $jsonData->address;
                $query->bindParam(':address', $Address, PDO::PARAM_STR);
            }
            if ($telephone_updated === true) {
                $Telephone = $jsonData->telephone;
                $query->bindParam(':telephone', $Telephone, PDO::PARAM_STR);
            }

            $query->bindParam(':id', $UserID, PDO::PARAM_INT);
            $query->execute();
            // Pengecekan Inputan User (Apakah Value Yang Diinput Sama Atau Tidak)
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("User not updated - given values may be the same as the stored values");
                $response->send();
                exit;
            }

            // Pengecekan User (Apakah ada atau tidak)
            $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE user_id=:id');
            $query->bindParam(':id', $UserID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No user found");
                $response->send();
                exit;
            }

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Users = new Users($row['user_id'], $row['username'], $row['password'], $row['email'], $row['fullname'], $row['address'], $row['telephone'],);
                $usersArray[] = $Users->returnUsersArray();
            }

            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['users'] = $usersArray;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("User Updated");
            $response->setData($returnData);
            $response->send();
        } catch (UsersException $ex) {
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        } catch (PDOException $ex) {
            if ($ex->getCode() == '23000') {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Username already exists.");
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
    } else if ($returned_level == 1) {
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

            $password_updated = false;
            $email_updated = false;
            $fullname_updated = false;
            $address_updated = false;
            $telephone_updated = false;
            $queryFields = "";


            if (isset($jsonData->password)) {
                $password_updated = true;
                $queryFields .= "password = :password, ";
            }
            if (isset($jsonData->email)) {
                $email_updated = true;
                $queryFields .= "email = :email,";
            }
            if (isset($jsonData->fullname)) {
                $fullname_updated = true;
                $queryFields .= "fullname = :fullname,";
            }
            if (isset($jsonData->address)) {
                $address_updated = true;
                $queryFields .= "address = :address,";
            }
            if (isset($jsonData->telephone)) {
                $telephone_updated = true;
                $queryFields .= "telephone = :telephone,";
            }

            $queryFields = rtrim($queryFields, ", ");

            // Pengecekan Field
            if ($password_updated === false && $email_updated === false && $fullname_updated === false && $address_updated === false && $telephone_updated === false) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("No user fields provided");
                $response->send();
                exit;
            }

            // Pengecekan Parameter id
            if (array_key_exists("id", $_GET)) {
                $UserID = $_GET['id'];
                if ($UserID == '' || !is_numeric($UserID)) {
                    $response = new Response();
                    $response->setHTTPStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("User ID cannot be blank or must be numeric");
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

            $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE user_id=:id');
            $query->bindParam(':id', $UserID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No user found to update");
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
            $queryString = "UPDATE users SET " . $queryFields . " WHERE user_id = :id";
            $query = $writeDB->prepare($queryString);

            if ($password_updated === true) {
                $Password = $jsonData->password;
                $query->bindParam(':password', $Password, PDO::PARAM_STR);
            }
            if ($email_updated === true) {
                $Email = $jsonData->email;
                $query->bindParam(':email', $Email, PDO::PARAM_STR);
            }
            if ($fullname_updated === true) {
                $Fullname = $jsonData->fullname;
                $query->bindParam(':fullname', $Fullname, PDO::PARAM_STR);
            }
            if ($address_updated === true) {
                $Address = $jsonData->address;
                $query->bindParam(':address', $Address, PDO::PARAM_STR);
            }
            if ($telephone_updated === true) {
                $Telephone = $jsonData->telephone;
                $query->bindParam(':telephone', $Telephone, PDO::PARAM_STR);
            }

            $query->bindParam(':id', $UserID, PDO::PARAM_INT);
            $query->execute();
            // Pengecekan Inputan User (Apakah Value Yang Diinput Sama Atau Tidak)
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("User not updated - given values may be the same as the stored values");
                $response->send();
                exit;
            }

            // Pengecekan User (Apakah ada atau tidak)
            $query = $readDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone FROM users WHERE user_id=:id');
            $query->bindParam(':id', $UserID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No user found");
                $response->send();
                exit;
            }

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $Users = new Users($row['user_id'], $row['username'], $row['password'], $row['email'], $row['fullname'], $row['address'], $row['telephone'],);
                $usersArray[] = $Users->returnUsersArray();
            }

            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['users'] = $usersArray;
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("User Updated");
            $response->setData($returnData);
            $response->send();
        } catch (UsersException $ex) {
            $response = new Response();
            $response->setHTTPStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($ex->getMessage());
            $response->send();
            exit();
        } catch (PDOException $ex) {
            if ($ex->getCode() == '23000') {
                $response = new Response();
                $response->setHTTPStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Username already exists.");
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
        $response->addMessage("You're Hacker !");
        $response->send();
        exit();
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Pengecekan HTTP Authorization
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $response = new Response();
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("Access token is missing from the header");
        $response->send();
        exit;
    }

    // Token
    $accesstoken = $_SERVER['HTTP_AUTHORIZATION'];

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

    if ($returned_level == 0) {
        try {
            // Pengecekan Parameter id
            if (array_key_exists("id", $_GET)) {
                $UserID = $_GET['id'];
                if ($UserID == '' || !is_numeric($UserID)) {
                    $response = new Response();
                    $response->setHTTPStatusCode(400);
                    $response->setSuccess(false);
                    $response->addMessage("User ID cannot be blank or must be numeric");
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
            $query = $writeDB->prepare('DELETE FROM users WHERE user_id = :id');
            $query->bindParam(':id', $UserID, PDO::PARAM_INT);
            $query->execute();
            $rowCount = $query->rowCount();

            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No user found to delete");
                $response->send();
                exit;
            }
            $response = new Response();
            $response->setHTTPStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("User Deleted");
            $response->send();
            exit();
        } catch (UsersException $ex) {
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
    } else if ($returned_level == 1) {
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
        $response->addMessage("You're Hacker !");
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
