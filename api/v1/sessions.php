<?php
require_once('../controller/DBConfig.php');
require_once('../model/Response.php');


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
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $response = new Response();
    $response->setHTTPStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request method not allowed");
    $response->send();
    exit();
    
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    sleep(1);

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
    }

    // attempt to query the database to check user details - use write connection as it needs to be synchronous for password/token
    try {
        $username = $jsonData->username;
        $password = $jsonData->password;
        // create db query
        $query = $writeDB->prepare('SELECT user_id, username, password, email, fullname, address, telephone, level FROM users WHERE username = :username AND password = :password');
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();

        // get row count
        $rowCount = $query->rowCount();

        if ($rowCount === 0) {
            // set up response for unsuccessful login attempt - obscure what is incorrect by saying username or password is wrong
            $response = new Response();
            $response->setHttpStatusCode(401);
            $response->setSuccess(false);
            $response->addMessage("Username or password is incorrect");
            $response->send();
            exit;
        }

        // get first row returned
        $row = $query->fetch(PDO::FETCH_ASSOC);

        // save returned details into variables
        $returned_id = $row['user_id'];
        $returned_username = $row['username'];
        $returned_password = $row['password'];
        $returned_email = $row['email'];
        $returned_fullname = $row['fullname'];
        $returned_address = $row['address'];
        $returned_telephone = $row['telephone'];
        $returned_level = $row['level'];

        // generate access token
        // use 24 random bytes to generate a token then encode this as base64
        // suffix with unix time stamp to guarantee uniqueness (stale tokens)
        $accesstoken = base64_encode(bin2hex(openssl_random_pseudo_bytes(24)) . time());

        // generate refresh token
        // use 24 random bytes to generate a refresh token then encode this as base64
        // suffix with unix time stamp to guarantee uniqueness (stale tokens)
        $refreshtoken = base64_encode(bin2hex(openssl_random_pseudo_bytes(24)) . time());

        // set access token and refresh token expiry in seconds (access token 20 minute lifetime and refresh token 14 days lifetime)
        // send seconds rather than date/time as this is not affected by timezones
        $access_token_expiry_seconds = 12000000;
        $refresh_token_expiry_seconds = 1209600;

    } catch (PDOException $ex) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("There was an issue logging in - please try again");
        $response->send();
        exit;
    }

    // new try catch as this is a transaction so should include roll back if error
    try {
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
        // create the query string to insert new session into sessions table and set the token and refresh token as well as their expiry dates and times
        $query = $writeDB->prepare('INSERT INTO sessions (user_id, level, accesstoken, accesstokenexpiry, refreshtoken, refreshtokenexpiry) VALUES (:user_id, :level, :accesstoken, date_add(CURRENT_TIMESTAMP(), INTERVAL :accesstokenexpiryseconds SECOND), :refreshtoken, date_add(CURRENT_TIMESTAMP(), INTERVAL :refreshtokenexpiryseconds SECOND))');
        // bind the user id
        $query->bindParam(':user_id', $returned_id, PDO::PARAM_INT);
        // bind the level
        $query->bindParam(':level', $returned_level, PDO::PARAM_INT);
        // bind the access token
        $query->bindParam(':accesstoken', $accesstoken, PDO::PARAM_STR);
        // bind the access token expiry date
        $query->bindParam(':accesstokenexpiryseconds', $access_token_expiry_seconds, PDO::PARAM_INT);
        // bind the refresh token
        $query->bindParam(':refreshtoken', $refreshtoken, PDO::PARAM_STR);
        // bind the refresh token expiry date
        $query->bindParam(':refreshtokenexpiryseconds', $refresh_token_expiry_seconds, PDO::PARAM_INT);
        // run the query
        $query->execute();

        // get last session id so we can return the session id in the json
        $lastSessionID = $writeDB->lastInsertId();

        // build response data array which contains the access token and refresh tokens
        $returnData = array();
        $returnData['session_id'] = intval($lastSessionID);
        $returnData['user_id'] = $returned_id;
        $returnData['level'] = $returned_level;
        $returnData['access_token'] = $accesstoken;
        $returnData['access_token_expires_in'] = $access_token_expiry_seconds;
        $returnData['refresh_token'] = $refreshtoken;
        $returnData['refresh_token_expires_in'] = $refresh_token_expiry_seconds;

        $response = new Response();
        $response->setHttpStatusCode(201);
        $response->setSuccess(true);
        $response->addMessage("Session Created");
        $response->setData($returnData);
        $response->send();
        exit;
    } catch (PDOException $ex) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage($ex->getMessage());
        $response->send();
        exit;
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    if (array_key_exists("id", $_GET)) {
        $SessionID = $_GET['id'];

        if ($SessionID == '' || !is_numeric($SessionID)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            ($SessionID == '' ? $response->addMessage("Session ID cannot be blank") : false);
            (!is_numeric($SessionID) ? $response->addMessage("Session ID must be numeric") : false);
            $response->send();
            exit;
        }

        // Pengecekan HTTP Authorization
        if (!isset($_SERVER['HTTP_AUTHORIZATION']) || strlen($_SERVER['HTTP_AUTHORIZATION']) < 1) {
            $response = new Response();
            $response->setHttpStatusCode(401);
            $response->setSuccess(false);
            (!isset($_SERVER['HTTP_AUTHORIZATION']) ? $response->addMessage("Access token is missing from the header") : false);
            (strlen($_SERVER['HTTP_AUTHORIZATION']) < 1 ? $response->addMessage("Access token cannot be blank") : false);
            $response->send();
            exit;
        }

        // get supplied access token from authorisation header - used for delete (log out) and patch (refresh)
        $accesstoken = $_SERVER['HTTP_AUTHORIZATION'];

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

        // check if patch request contains access token
        if (!isset($jsonData->refresh_token) || strlen($jsonData->refresh_token) < 1) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            (!isset($jsonData->refresh_token) ? $response->addMessage("Refresh Token not supplied") : false);
            (strlen($jsonData->refresh_token) < 1 ? $response->addMessage("Refresh Token cannot be blank") : false);
            $response->send();
            exit;
        }

        // attempt to query the database to check token details - use write connection as it needs to be synchronous for token
        try {

            $refreshtoken = $jsonData->refresh_token;
            // get user record for provided session id, access AND refresh token
            // create db query to retrieve user details from provided access and refresh token 
            $query = $writeDB->prepare('SELECT sessions.id AS id, sessions.user_id AS user_id, accesstoken, refreshtoken, accesstokenexpiry, refreshtokenexpiry FROM sessions, users WHERE users.user_id = sessions.user_id AND sessions.id = :id AND sessions.accesstoken = :accesstoken AND sessions.refreshtoken = :refreshtoken');
            $query->bindParam(':id', $SessionID, PDO::PARAM_INT);
            $query->bindParam(':accesstoken', $accesstoken, PDO::PARAM_STR);
            $query->bindParam(':refreshtoken', $refreshtoken, PDO::PARAM_STR);
            $query->execute();

            // get row count
            $rowCount = $query->rowCount();

            if ($rowCount === 0) {
                // set up response for unsuccessful access token refresh attempt
                $response = new Response();
                $response->setHttpStatusCode(401);
                $response->setSuccess(false);
                $response->addMessage("Access Token or Refresh Token is incorrect for Session ID");
                $response->send();
                exit;
            }

            // get returned row
            $row = $query->fetch(PDO::FETCH_ASSOC);

            // save returned details into variables
            $returned_SessionID = $row['id'];
            $returned_user_id = $row['user_id'];
            $returned_accesstoken = $row['accesstoken'];
            $returned_refreshtoken = $row['refreshtoken'];
            $returned_accesstokenexpiry = $row['accesstokenexpiry'];
            $returned_refreshtokenexpiry = $row['refreshtokenexpiry'];


            // check if refresh token has expired
            if (strtotime($returned_refreshtokenexpiry) <= (time() + 18000)) {
                $response = new Response();
                $response->setHttpStatusCode(401);
                $response->setSuccess(false);
                $response->addMessage("Refresh token has expired - please log in again");
                $response->send();
                exit;
            }

            // generate access token
            // use 24 random bytes to generate a token then encode this as base64
            // suffix with unix time stamp to guarantee uniqueness (stale tokens)
            $accesstoken = base64_encode(bin2hex(openssl_random_pseudo_bytes(24)) . time());

            // generate refresh token
            // use 24 random bytes to generate a refresh token then encode this as base64
            // suffix with unix time stamp to guarantee uniqueness (stale tokens)
            $refreshtoken = base64_encode(bin2hex(openssl_random_pseudo_bytes(24)) . time());

            // set access token and refresh token expiry in seconds (access token 20 minute lifetime and refresh token 14 days lifetime)
            // send seconds rather than date/time as this is not affected by timezones
            $access_token_expiry_seconds = 1200;
            $refresh_token_expiry_seconds = 1209600;

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
            // create the query string to update the current session row in the sessions table and set the token and refresh token as well as their expiry dates and times
            $query = $writeDB->prepare('UPDATE sessions SET accesstoken = :accesstoken, accesstokenexpiry = date_add(NOW(), INTERVAL :accesstokenexpiryseconds SECOND), refreshtoken = :refreshtoken, refreshtokenexpiry = date_add(NOW(), INTERVAL :refreshtokenexpiryseconds SECOND) WHERE id = :id AND user_id = :user_id AND accesstoken = :returnedaccesstoken AND refreshtoken = :returnedrefreshtoken');
            // bind the user id
            $query->bindParam(':user_id', $returned_user_id, PDO::PARAM_INT);
            // bind the session id
            $query->bindParam(':id', $returned_SessionID, PDO::PARAM_INT);
            // bind the access token
            $query->bindParam(':accesstoken', $accesstoken, PDO::PARAM_STR);
            // bind the access token expiry date
            $query->bindParam(':accesstokenexpiryseconds', $access_token_expiry_seconds, PDO::PARAM_INT);
            // bind the refresh token
            $query->bindParam(':refreshtoken', $refreshtoken, PDO::PARAM_STR);
            // bind the refresh token expiry date
            $query->bindParam(':refreshtokenexpiryseconds', $refresh_token_expiry_seconds, PDO::PARAM_INT);
            // bind the old access token for where clause as user could have multiple sessions
            $query->bindParam(':returnedaccesstoken', $returned_accesstoken, PDO::PARAM_STR);
            // bind the old refresh token for where clause as user could have multiple sessions
            $query->bindParam(':returnedrefreshtoken', $returned_refreshtoken, PDO::PARAM_STR);
            // run the query
            $query->execute();

            // get count of rows updated - should be 1
            $rowCount = $query->rowCount();

            // check that a row has been updated
            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(401);
                $response->setSuccess(false);
                $response->addMessage("Access token could not be refreshed - please log in again");
                $response->send();
                exit;
            }

            // build response data array which contains the session id, access token and refresh token
            $returnData = array();
            $returnData['session_id'] = $returned_SessionID;
            $returnData['access_token'] = $accesstoken;
            $returnData['access_token_expiry'] = $access_token_expiry_seconds;
            $returnData['refresh_token'] = $refreshtoken;
            $returnData['refresh_token_expiry'] = $refresh_token_expiry_seconds;

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->setData($returnData);
            $response->send();
            exit;
        } catch (PDOException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("There was an issue refreshing access token - please log in again");
            $response->send();
            exit;
        }
    } else {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Endpoint Not Found");
        $response->send();
        exit;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (array_key_exists("id", $_GET)) {
        $SessionID = $_GET['id'];

        if ($SessionID == '' || !is_numeric($SessionID)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            ($SessionID == '' ? $response->addMessage("Session ID cannot be blank") : false);
            (!is_numeric($SessionID) ? $response->addMessage("Session ID must be numeric") : false);
            $response->send();
            exit;
        }

        // Pengecekan HTTP Authorization
        if (!isset($_SERVER['HTTP_AUTHORIZATION']) || strlen($_SERVER['HTTP_AUTHORIZATION']) < 1) {
            $response = new Response();
            $response->setHttpStatusCode(401);
            $response->setSuccess(false);
            (!isset($_SERVER['HTTP_AUTHORIZATION']) ? $response->addMessage("Access token is missing from the header") : false);
            (strlen($_SERVER['HTTP_AUTHORIZATION']) < 1 ? $response->addMessage("Access token cannot be blank") : false);
            $response->send();
            exit;
        }

        // get supplied access token from authorisation header - used for delete (log out) and patch (refresh)
        $accesstoken = $_SERVER['HTTP_AUTHORIZATION'];

        // attempt to query the database to check token details - use write connection as it needs to be synchronous for token
        try {
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
            // create db query to delete session where access token is equal to the one provided (leave other sessions active)
            // doesn't matter about if access token has expired as we are deleting the session
            $query = $writeDB->prepare('DELETE FROM sessions WHERE id = :id AND accesstoken = :accesstoken');
            $query->bindParam(':id', $SessionID, PDO::PARAM_INT);
            $query->bindParam(':accesstoken', $accesstoken, PDO::PARAM_STR);
            $query->execute();

            // get row count
            $rowCount = $query->rowCount();

            if ($rowCount === 0) {
                // set up response for unsuccessful log out response
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Failed to log out of this session using access token provided");
                $response->send();
                exit;
            }

            // build response data array which contains the session id that has been deleted (logged out)
            $returnData = array();
            $returnData['session_id'] = intval($SessionID);

            // send successful response for log out
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->setData($returnData);
            $response->addMessage("Session Deleted");
            $response->send();
            exit;
        } catch (PDOException $ex) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("There was an issue logging out - please try again");
            $response->send();
            exit;
        }

    } else {
        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("Endpoint Not Found");
        $response->send();
        exit;
    }
} else {
    $response = new Response();
    $response->setHTTPStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request method not allowed");
    $response->send();
    exit();
}