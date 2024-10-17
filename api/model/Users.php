<?php
class UsersException extends Exception
{
}
class Users
{
    private $_userID;
    private $_username;
    private $_password;
    private $_email;
    private $_fullname;
    private $_address;
    private $_telephone;

    public function __construct($userID, $username, $password, $email, $fullname, $address, $telephone)
    {
        $this->setUserID($userID);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setEmail($email);
        $this->setFullname($fullname);
        $this->setAddress($address);
        $this->setTelephone($telephone);
    }
    public function getUserID()
    {
        return $this->_userID;
    }
    public function getUsername()
    {
        return $this->_username;
    }
    public function getPassword()
    {
        return $this->_password;
    }
    public function getEmail()
    {
        return $this->_email;
    }
    public function getFullname()
    {
        return $this->_fullname;
    }
    public function getAddress()
    {
        return $this->_address;
    }
    public function getTelephone()
    {
        return $this->_telephone;
    }
    
    
    public function setUserID($userID)
    {
        if (($userID !== null) && (!is_numeric($userID) || $userID <= 0)) {
            throw new UsersException("User ID Error");
        }
        $this->_userID = $userID;
    }
    public function setUsername($username)
    {
        if (strlen($username) < 0 || strlen($username) > 255) {
            throw new UsersException("Username Error");
        }
        $this->_username = $username;
    }
    public function setPassword($password)
    {
        if (strlen($password) < 0 || strlen($password) > 255) {
            throw new UsersException("Password Error");
        }
        $this->_password = $password;
    }
    public function setEmail($email)
    {
        if (strlen($email) < 0 || strlen($email) > 255) {
            throw new UsersException("Email Error");
        }
        $this->_email = $email;
    }
    public function setFullname($fullname)
    {
        if (strlen($fullname) < 0 || strlen($fullname) > 255) {
            throw new UsersException("Fullname Error");
        }
        $this->_fullname = $fullname;
    }
    public function setAddress($address)
    {
        if (strlen($address) < 0 || strlen($address) > 255) {
            throw new UsersException("Address Error");
        }
        $this->_address = $address;
    }
    public function setTelephone($telephone)
    {
        if (($telephone !== null) && (!is_numeric($telephone) || $telephone <= 0)) {
            throw new UsersException("Telephone Error");
        }
        $this->_telephone = $telephone;
    }
    public function returnUsersArray()
    {
        $Users = array();
        $Users['user_id'] = $this->getUserID();
        $Users['username'] = $this->getUsername();
        $Users['password'] = $this->getPassword();
        $Users['email'] = $this->getEmail();
        $Users['fullname'] = $this->getFullname();
        $Users['address'] = $this->getAddress();
        $Users['telephone'] = $this->getTelephone();
        return $Users;
    }
}
?>

