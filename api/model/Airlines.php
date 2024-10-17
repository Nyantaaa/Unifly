<?php
class AirlinesException extends Exception
{
}
class Airlines
{
    private $_airlineID;
    private $_airlineName;
    private $_airlineCode;
    private $_totalSeats;

    public function __construct($airlineID, $airlineName, $airlineCode, $totalSeats)
    {
        $this->setAirlineID($airlineID);
        $this->setAirlineName($airlineName);
        $this->setAirlineCode($airlineCode);
        $this->setTotalSeats($totalSeats);
    }
    public function getAirlineID()
    {
        return $this->_airlineID;
    }
    public function getAirlineName()
    {
        return $this->_airlineName;
    }
    public function getAirlineCode()
    {
        return $this->_airlineCode;
    }
    public function getTotalSeats()
    {
        return $this->_totalSeats;
    }

    public function setAirlineID($airlineID)
    {
        if (($airlineID !== null) && (!is_numeric($airlineID) || $airlineID <= 0)) {
            throw new AirlinesException("Airline ID error");
        }
        $this->_airlineID = $airlineID;
    }
    public function setAirlineName($airlineName)
    {
        if (strlen($airlineName) <= 0 || strlen($airlineName) > 255) {
            throw new AirlinesException("Airline Name Error");
        }
        $this->_airlineName = $airlineName;
    }
    public function setAirlineCode($airlineCode)
    {
        if (strlen($airlineCode) <= 0 || strlen($airlineCode) > 255) {
            throw new AirlinesException("Airline Code Error");
        }
        $this->_airlineCode = $airlineCode;
    }
    public function setTotalSeats($totalSeats)
    {
        if (($totalSeats !== null) && (!is_numeric($totalSeats) || $totalSeats <= 0)) {
            throw new AirlinesException("Total Seats error");
        }
        $this->_totalSeats = $totalSeats;
    }
    public function returnAirlinesArray()
    {
        $Airlines = array();
        $Airlines['airline_id'] = $this->getAirlineID();
        $Airlines['airline_name'] = $this->getAirlineName();
        $Airlines['airline_code'] = $this->getAirlineCode();
        $Airlines['total_seats'] = $this->getTotalSeats();
        return $Airlines;
    }
}
?>

