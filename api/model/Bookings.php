<?php
class BookingsException extends Exception
{
}
class Bookings
{
    private $_bookingID;
    private $_fullname;
    private $_airlineName;
    private $_origin;
    private $_destination;
    private $_departureTime;
    private $_arrivalTime;
    private $_bookingDate;
    private $_seatNumber;
    private $_totalPassenger;
    private $_totalPrice;

    public function __construct($bookingID, $fullname, $airlineName, $origin, $destination, $departureTime, $arrivalTime, $bookingDate, $seatNumber, $totalPassenger, $totalPrice)
    {
        $this->setBookingID($bookingID);
        $this->setFullname($fullname);
        $this->setAirlineName($airlineName);
        $this->setOrigin($origin);
        $this->setDestination($destination);
        $this->setDepartureTime($departureTime);
        $this->setArrivalTime($arrivalTime);
        $this->setBookingDate($bookingDate);
        $this->setSeatNumber($seatNumber);
        $this->setTotalPassenger($totalPassenger);
        $this->setTotalPrice($totalPrice);
    }
    public function getBookingID()
    {
        return $this->_bookingID;
    }
    public function getFullname()
    {
        return $this->_fullname;
    }
    public function getAirlineName()
    {
        return $this->_airlineName;
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
    public function getBookingDate()
    {
        return $this->_bookingDate;
    }
    public function getSeatNumber()
    {
        return $this->_seatNumber;
    }
    public function getTotalPassenger()
    {
        return $this->_totalPassenger;
    }
    public function getTotalPrice()
    {
        return $this->_totalPrice;
    }
    
    
    public function setBookingID($bookingID)
    {
        if (($bookingID !== null) && (!is_string($bookingID)) || $bookingID < 0) {
            throw new BookingsException("Booking ID Error");
        }
        $this->_bookingID = $bookingID;
    }
    public function setFullname($fullname)
    {
        if (strlen($fullname) < 0 || strlen($fullname) > 255) {
            throw new BookingsException("Fullname Error");
        }
        $this->_fullname = $fullname;
    }
    public function setAirlineName($airlineName)
    {
        if (strlen($airlineName) < 0 || strlen($airlineName) > 255) {
            throw new BookingsException("Airline Name Error");
        }
        $this->_airlineName = $airlineName;
    }
    public function setOrigin($origin)
    {
        if (strlen($origin) < 0 || strlen($origin) > 255) {
            throw new BookingsException("Origin Error");
        }
        $this->_origin = $origin;
    }
    public function setDestination($destination)
    {
        if (strlen($destination) < 0 || strlen($destination) > 255) {
            throw new BookingsException("Destination Error");
        }
        $this->_destination = $destination;
    }
    public function setDepartureTime($departureTime)
    {
        if (($departureTime !== null) && date_format(date_create_from_format('d/m/Y H:i', $departureTime), 'd/m/Y H:i') != $departureTime) {
            throw new BookingsException("Departure Time error");
        }
        $this->_departureTime = $departureTime;
    }
    public function setArrivalTime($arrivalTime)
    {
        if (($arrivalTime !== null) && date_format(date_create_from_format('d/m/Y H:i', $arrivalTime), 'd/m/Y H:i') != $arrivalTime) {
            throw new BookingsException("Arrival Time error");
        }
        $this->_arrivalTime = $arrivalTime;
    }
    public function setBookingDate($bookingDate)
    {
        if (($bookingDate !== null) && date_format(date_create_from_format('d/m/Y H:i', $bookingDate), 'd/m/Y H:i') != $bookingDate) {
            throw new BookingsException("Booking Date error");
        }
        $this->_bookingDate = $bookingDate;
    }
    public function setSeatNumber($seatNumber)
    {
        if (strlen($seatNumber) < 0 || strlen($seatNumber) > 255) {
            throw new BookingsException("Seat Number Error");
        }
        $this->_seatNumber = $seatNumber;
    }
    public function setTotalPassenger($totalPassenger)
    {
        if (($totalPassenger !== null) && (!is_numeric($totalPassenger) || $totalPassenger < 0)) {
            throw new BookingsException("Total Passenger Error");
        }
        $this->_totalPassenger = $totalPassenger;
    }
    public function setTotalPrice($totalPrice)
    {
        if (($totalPrice !== null) && (!is_numeric($totalPrice) || $totalPrice < 0)) {
            throw new BookingsException("Total Price Error");
        }
        $this->_totalPrice = $totalPrice;
    }
    public function returnBookingsArray()
    {
        $Bookings = array();
        $Bookings['booking_id'] = $this->getBookingID();
        $Bookings['fullname'] = $this->getFullname();
        $Bookings['airline_name'] = $this->getAirlineName();
        $Bookings['origin'] = $this->getOrigin();
        $Bookings['destination'] = $this->getDestination();
        $Bookings['departure_time'] = $this->getDepartureTime();
        $Bookings['arrival_time'] = $this->getArrivalTime();
        $Bookings['booking_date'] = $this->getBookingDate();
        $Bookings['seat_number'] = $this->getSeatNumber();
        $Bookings['total_passenger'] = $this->getTotalPassenger();
        $Bookings['total_price'] = $this->getTotalPrice();
        return $Bookings;
    }
}
?>

