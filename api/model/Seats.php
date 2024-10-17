<?php
class SeatsException extends Exception
{
}
class Seats
{
    private $_seatID;
    private $_origin;
    private $_destination;
    private $_departureTime;
    private $_arrivalTime;
    private $_seatNumber;
    private $_isAvailable;

    public function __construct($seatID, $origin, $destination, $departureTime, $arrivalTime, $seatNumber, $isAvailable)
    {
        $this->setSeatID($seatID);
        $this->setOrigin($origin);
        $this->setDestination($destination);
        $this->setDepartureTime($departureTime);
        $this->setArrivalTime($arrivalTime);
        $this->setSeatNumber($seatNumber);
        $this->setIsAvailable($isAvailable);
    }
    public function getSeatID()
    {
        return $this->_seatID;
    }
    public function getOrigin()
    {
        return $this->_origin;
    }
    public function getDestination()
    {
        return $this->_destination;
    }
    public function getDepartureTime()
    {
        return $this->_departureTime;
    }
    public function getArrivalTime()
    {
        return $this->_arrivalTime;
    }
    public function getSeatNumber()
    {
        return $this->_seatNumber;
    }
    public function getIsAvailable()
    {
        return $this->_isAvailable;
    }
    
    
    public function setSeatID($seatID)
    {
        if (($seatID !== null) && (!is_numeric($seatID) || $seatID <= 0)) {
            throw new SeatsException("Seat ID Error");
        }
        $this->_seatID = $seatID;
    }
    public function setOrigin($origin)
    {
        if (strlen($origin) < 0 || strlen($origin) > 255) {
            throw new SeatsException("Origin Error");
        }
        $this->_origin = $origin;
    }
    public function setDestination($destination)
    {
        if (strlen($destination) < 0 || strlen($destination) > 255) {
            throw new SeatsException("Destination Error");
        }
        $this->_destination = $destination;
    }
    public function setDepartureTime($departureTime)
    {
        if (($departureTime !== null) && date_format(date_create_from_format('d/m/Y H:i', $departureTime), 'd/m/Y H:i') != $departureTime) {
            throw new SeatsException("Departure Time error");
        }
        $this->_departureTime = $departureTime;
    }
    public function setArrivalTime($arrivalTime)
    {
        if (($arrivalTime !== null) && date_format(date_create_from_format('d/m/Y H:i', $arrivalTime), 'd/m/Y H:i') != $arrivalTime) {
            throw new SeatsException("Arrival Time error");
        }
        $this->_arrivalTime = $arrivalTime;
    }
    public function setSeatNumber($seatNumber)
    {
        if (strlen($seatNumber) < 0 || strlen($seatNumber) > 255) {
            throw new SeatsException("Seat Number Error");
        }
        $this->_seatNumber = $seatNumber;
    }
    public function setIsAvailable($isAvailable)
    {
        if (($isAvailable !== null) && (!is_numeric($isAvailable) || $isAvailable < 0)) {
            throw new SeatsException("Is Available Error");
        }
        $this->_isAvailable = $isAvailable;
    }
    public function returnSeatsArray()
    {
        $Seats = array();
        $Seats['seat_id'] = $this->getSeatID();
        $Seats['origin'] = $this->getOrigin();
        $Seats['destination'] = $this->getDestination();
        $Seats['departure_time'] = $this->getDepartureTime();
        $Seats['arrival_time'] = $this->getArrivalTime();
        $Seats['seat_number'] = $this->getSeatNumber();
        $Seats['is_available'] = $this->getIsAvailable();
        return $Seats;
    }
}
?>

