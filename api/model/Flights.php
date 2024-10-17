<?php
class FlightsException extends Exception
{
}
class Flights
{
    private $_flightID;
    private $_airlineName;
    private $_airlineCode;
    private $_origin;
    private $_destination;
    private $_departureTime;
    private $_arrivalTime;
    private $_price;
    private $_totalSeats;

    public function __construct($flightID, $airlineName, $airlineCode, $origin, $destination, $departureTime, $arrivalTime, $price, $totalSeats)
    {
        $this->setFlightID($flightID);
        $this->setAirlineName($airlineName);
        $this->setAirlineCode($airlineCode);
        $this->setOrigin($origin);
        $this->setDestination($destination);
        $this->setDepartureTime($departureTime);
        $this->setArrivalTime($arrivalTime);
        $this->setPrice($price);
        $this->setTotalSeats($totalSeats);
    }
    public function getFlightID()
    {
        return $this->_flightID;
    }
    public function getAirlineName()
    {
        return $this->_airlineName;
    }
    public function getAirlineCode()
    {
        return $this->_airlineCode;
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
    public function getPrice()
    {
        return $this->_price;
    }
    public function getTotalSeats()
    {
        return $this->_totalSeats;
    }
    
    
    public function setFlightID($flightID)
    {
        if (($flightID !== null) && (!is_numeric($flightID) || $flightID <= 0)) {
            throw new FlightsException("Flight ID Error");
        }
        $this->_flightID = $flightID;
    }
    public function setAirlineName($airlineName)
    {
        if (strlen($airlineName) < 0 || strlen($airlineName) > 255) {
            throw new FlightsException("Airline Name error");
        }
        $this->_airlineName = $airlineName;
    }
    public function setAirlineCode($airlineCode)
    {
        if (strlen($airlineCode) < 0 || strlen($airlineCode) > 255) {
            throw new FlightsException("Airline Code error");
        }
        $this->_airlineCode = $airlineCode;
    }
    public function setOrigin($origin)
    {
        if (strlen($origin) < 0 || strlen($origin) > 255) {
            throw new FlightsException("Origin Error");
        }
        $this->_origin = $origin;
    }
    public function setDestination($destination)
    {
        if (strlen($destination) < 0 || strlen($destination) > 255) {
            throw new FlightsException("Destination Error");
        }
        $this->_destination = $destination;
    }
    public function setDepartureTime($departureTime)
    {
        if (($departureTime !== null) && date_format(date_create_from_format('d/m/Y H:i', $departureTime), 'd/m/Y H:i') != $departureTime) {
            throw new FlightsException("Departure Time error");
        }
        $this->_departureTime = $departureTime;
    }
    public function setArrivalTime($arrivalTime)
    {
        if (($arrivalTime !== null) && date_format(date_create_from_format('d/m/Y H:i', $arrivalTime), 'd/m/Y H:i') != $arrivalTime) {
            throw new FlightsException("Arrival Time error");
        }
        $this->_arrivalTime = $arrivalTime;
    }
    public function setPrice($price)
    {
        if (($price !== null) && (!is_numeric($price) || $price <= 0)) {
            throw new FlightsException("Price Error");
        }
        $this->_price = $price;
    }
    public function setTotalSeats($totalSeats)
    {
        if (($totalSeats !== null) && (!is_numeric($totalSeats) || $totalSeats <= 0)) {
            throw new FlightsException("Total Seats Error");
        }
        $this->_totalSeats = $totalSeats;
    }
    public function returnFlightsArray()
    {
        $Flights = array();
        $Flights['flight_id'] = $this->getFlightID();
        $Flights['airline_name'] = $this->getAirlineName();
        $Flights['airline_code'] = $this->getAirlineCode();
        $Flights['origin'] = $this->getOrigin();
        $Flights['destination'] = $this->getDestination();
        $Flights['departure_time'] = $this->getDepartureTime();
        $Flights['arrival_time'] = $this->getArrivalTime();
        $Flights['price'] = $this->getPrice();
        $Flights['available_seats'] = $this->getTotalSeats();
        return $Flights;
    }
}
?>

