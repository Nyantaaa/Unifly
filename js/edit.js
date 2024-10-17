// Input dan Button
// Airlines
const airline_name = document.getElementById('airline_name');
const airline_code = document.getElementById('airline_code');
const sendAirlines = document.getElementById('sendAirlines');

// Flights
const destination = document.getElementById('destination');
const departure_time = document.getElementById('departure_time');
const arrival_time = document.getElementById('arrival_time');
const price = document.getElementById('price');
const sendFlights = document.getElementById('sendFlights');

// Seats
const is_available = document.getElementById('is_available');
const sendSeats = document.getElementById('sendSeats');

// Users
const username = document.getElementById('username');
const password = document.getElementById('password');
const email = document.getElementById('email');
const fullname = document.getElementById('fullname');
const address = document.getElementById('address');
const telephone = document.getElementById('telephone');
const sendUsers = document.getElementById('sendUsers');


// Menu Form
const airlinesMenuForm = document.getElementById('airlinesMenuForm');
const flightsMenuForm = document.getElementById('flightsMenuForm');
const seatsMenuForm = document.getElementById('seatsMenuForm');
const usersMenuForm = document.getElementById('usersMenuForm');

// Mobile Menu Form
const airlinesMobileMenuForm = document.getElementById('airlinesMobileMenuForm');
const flightsMobileMenuForm = document.getElementById('flightsMobileMenuForm');
const seatsMobileMenuForm = document.getElementById('seatsMobileMenuForm');
const usersMobileMenuForm = document.getElementById('usersMobileMenuForm');

// Form
const airlinesForm = document.getElementById('airlinesForm');
const flightsForm = document.getElementById('flightsForm');
const seatsForm = document.getElementById('seatsForm');
const usersForm = document.getElementById('usersForm');
const loadingForm = document.getElementById('loading-form');

// Fitur
const loadingBackground = document.getElementById('loading-bg');
const loading = document.getElementById('loading');

// Loading
function showLoading() {
    loadingBackground.classList.add('active-loading-bg', 'z-50', 'flex');
    loadingBackground.classList.remove('hidden');
    loading.classList.add('active-loading');
    setTimeout(function () {
        loadingBackground.classList.add('hidden');
        loadingBackground.classList.remove('active-loading-bg', 'flex', 'z-50');
        loading.classList.remove('active-loading');
    }, 750);
}

// Airlines Menu Form Selected
function airlinesMenuFormSelected() {
    airlinesMenuForm.classList.add('bg-sky-700');
    airlinesMenuForm.classList.remove('hover:bg-sky-700');
    airlinesForm.classList.add('block');
    airlinesForm.classList.remove('hidden');
}

// Flights Menu Form Selected
function flightsMenuFormSelected() {
    flightsMenuForm.classList.add('bg-sky-700');
    flightsMenuForm.classList.remove('hover:bg-sky-700');
    flightsForm.classList.add('block');
    flightsForm.classList.remove('hidden');
}

// Seats Menu Form Selected
function seatsMenuFormSelected() {
    seatsMenuForm.classList.add('bg-sky-700');
    seatsMenuForm.classList.remove('hover:bg-sky-700');
    seatsForm.classList.add('block');
    seatsForm.classList.remove('hidden');
}

// Users Menu Form Selected
function usersMenuFormSelected() {
    usersMenuForm.classList.add('bg-sky-700');
    usersMenuForm.classList.remove('hover:bg-sky-700');
    usersForm.classList.add('block');
    usersForm.classList.remove('hidden');
}

// Airlines Menu Form Not Selected
function removeAirlinesMenuFormSelected() {
    airlinesMenuForm.classList.add('hover:bg-sky-700');
    airlinesMenuForm.classList.remove('bg-sky-700');
    airlinesForm.classList.add('hidden');
    airlinesForm.classList.remove('block');
}

// Flights Menu Form Not Selected
function removeFlightsMenuFormSelected() {
    flightsMenuForm.classList.add('hover:bg-sky-700');
    flightsMenuForm.classList.remove('bg-sky-700');
    flightsForm.classList.add('hidden');
    flightsForm.classList.remove('block');
}

// Seats Menu Form Not Selected
function removeSeatsMenuFormSelected() {
    seatsMenuForm.classList.add('hover:bg-sky-700');
    seatsMenuForm.classList.remove('bg-sky-700');
    seatsForm.classList.add('hidden');
    seatsForm.classList.remove('block');
}

// Users Menu Form Not Selected
function removeUsersMenuFormSelected() {
    usersMenuForm.classList.add('hover:bg-sky-700');
    usersMenuForm.classList.remove('bg-sky-700');
    usersForm.classList.add('hidden');
    usersForm.classList.remove('block');
}

// Airlines Menu Form Clicked
function airlinesMenuFormClicked() {
    localStorage.setItem('selectedSidebarMenuForm', 'airlines');
    localStorage.setItem('selectedMobileSidebarMenuForm', 'airlines');
    localStorage.setItem('selectedSidebarMenu', 'airlines');
    localStorage.setItem('selectedMobileSidebarMenu', 'airlines');
    airlinesMenuFormSelected();
    removeFlightsMenuFormSelected();
    removeSeatsMenuFormSelected();
    removeUsersMenuFormSelected();
    airlinesMobileMenuFormSelected();
    removeFlightsMobileMenuFormSelected();
    removeSeatsMobileMenuFormSelected();
    removeUsersMobileMenuFormSelected();
}

// Flights Menu Form Clicked
function flightsMenuFormClicked() {
    localStorage.setItem('selectedSidebarMenuForm', 'flights');
    localStorage.setItem('selectedMobileSidebarMenuForm', 'flights');
    localStorage.setItem('selectedSidebarMenu', 'flights');
    localStorage.setItem('selectedMobileSidebarMenu', 'flights');
    flightsMenuFormSelected();
    removeAirlinesMenuFormSelected();
    removeSeatsMenuFormSelected();
    removeUsersMenuFormSelected();
    flightsMobileMenuFormSelected();
    removeAirlinesMobileMenuFormSelected();
    removeSeatsMobileMenuFormSelected();
    removeUsersMobileMenuFormSelected();
}

// Seats Menu Form Clicked
function seatsMenuFormClicked() {
    localStorage.setItem('selectedSidebarMenuForm', 'seats');
    localStorage.setItem('selectedMobileSidebarMenuForm', 'seats');
    localStorage.setItem('selectedSidebarMenu', 'seats');
    localStorage.setItem('selectedMobileSidebarMenu', 'seats');
    seatsMenuFormSelected();
    removeAirlinesMenuFormSelected();
    removeFlightsMenuFormSelected();
    removeUsersMenuFormSelected();
    seatsMobileMenuFormSelected();
    removeAirlinesMobileMenuFormSelected();
    removeFlightsMobileMenuFormSelected();
    removeUsersMobileMenuFormSelected();
}

// Users Menu Form Clicked
function usersMenuFormClicked() {
    localStorage.setItem('selectedSidebarMenuForm', 'users');
    localStorage.setItem('selectedMobileSidebarMenuForm', 'users');
    localStorage.setItem('selectedSidebarMenu', 'users');
    localStorage.setItem('selectedMobileSidebarMenu', 'users');
    usersMenuFormSelected();
    removeAirlinesMenuFormSelected();
    removeFlightsMenuFormSelected();
    removeSeatsMenuFormSelected();
    usersMobileMenuFormSelected();
    removeAirlinesMobileMenuFormSelected();
    removeFlightsMobileMenuFormSelected();
    removeSeatsMobileMenuFormSelected();
}

// Airlines Mobile Menu Form Selected
function airlinesMobileMenuFormSelected() {
    airlinesMobileMenuForm.classList.add('bg-sky-700');
    airlinesMobileMenuForm.classList.remove('hover:bg-sky-700');
    airlinesForm.classList.add('block');
    airlinesForm.classList.remove('hidden');
}

// Flights Menu Form Selected
function flightsMobileMenuFormSelected() {
    flightsMobileMenuForm.classList.add('bg-sky-700');
    flightsMobileMenuForm.classList.remove('hover:bg-sky-700');
    flightsForm.classList.add('block');
    flightsForm.classList.remove('hidden');
}

// Seats Menu Form Selected
function seatsMobileMenuFormSelected() {
    seatsMobileMenuForm.classList.add('bg-sky-700');
    seatsMobileMenuForm.classList.remove('hover:bg-sky-700');
    seatsForm.classList.add('block');
    seatsForm.classList.remove('hidden');
}

// Users Menu Form Selected
function usersMobileMenuFormSelected() {
    usersMobileMenuForm.classList.add('bg-sky-700');
    usersMobileMenuForm.classList.remove('hover:bg-sky-700');
    usersForm.classList.add('block');
    usersForm.classList.remove('hidden');
}

// Airlines Mobile Menu Form Not Selected
function removeAirlinesMobileMenuFormSelected() {
    airlinesMobileMenuForm.classList.add('hover:bg-sky-700');
    airlinesMobileMenuForm.classList.remove('bg-sky-700');
    airlinesForm.classList.add('hidden');
    airlinesForm.classList.remove('block');
}

// Flights Mobile Menu Form Not Selected
function removeFlightsMobileMenuFormSelected() {
    flightsMobileMenuForm.classList.add('hover:bg-sky-700');
    flightsMobileMenuForm.classList.remove('bg-sky-700');
    flightsForm.classList.add('hidden');
    flightsForm.classList.remove('block');
}

// Seats Mobile Menu Form Not Selected
function removeSeatsMobileMenuFormSelected() {
    seatsMobileMenuForm.classList.add('hover:bg-sky-700');
    seatsMobileMenuForm.classList.remove('bg-sky-700');
    seatsForm.classList.add('hidden');
    seatsForm.classList.remove('block');
}

// Users Mobile Menu Form Not Selected
function removeUsersMobileMenuFormSelected() {
    usersMobileMenuForm.classList.add('hover:bg-sky-700');
    usersMobileMenuForm.classList.remove('bg-sky-700');
    usersForm.classList.add('hidden');
    usersForm.classList.remove('block');
}

// Airlines Mobile Menu Form Clicked
function airlinesMobileMenuFormClicked() {
    localStorage.setItem('selectedSidebarMenuForm', 'airlines');
    localStorage.setItem('selectedMobileSidebarMenuForm', 'airlines');
    localStorage.setItem('selectedSidebarMenu', 'airlines');
    localStorage.setItem('selectedMobileSidebarMenu', 'airlines');
    airlinesMobileMenuFormSelected();
    removeFlightsMobileMenuFormSelected();
    removeSeatsMobileMenuFormSelected();
    removeUsersMobileMenuFormSelected();
    airlinesMenuFormSelected();
    removeFlightsMenuFormSelected();
    removeSeatsMenuFormSelected();
    removeUsersMenuFormSelected();
}

// Flights Menu Form Clicked
function flightsMobileMenuFormClicked() {
    localStorage.setItem('selectedSidebarMenuForm', 'flights');
    localStorage.setItem('selectedMobileSidebarMenuForm', 'flights');
    localStorage.setItem('selectedSidebarMenu', 'flights');
    localStorage.setItem('selectedMobileSidebarMenu', 'flights');
    flightsMobileMenuFormSelected();
    removeAirlinesMobileMenuFormSelected();
    removeSeatsMobileMenuFormSelected();
    removeUsersMobileMenuFormSelected();
    flightsMenuFormSelected();
    removeAirlinesMenuFormSelected();
    removeSeatsMenuFormSelected();
    removeUsersMenuFormSelected();
}

// Seats Menu Form Clicked
function seatsMobileMenuFormClicked() {
    localStorage.setItem('selectedSidebarMenuForm', 'seats');
    localStorage.setItem('selectedMobileSidebarMenuForm', 'seats');
    localStorage.setItem('selectedSidebarMenu', 'seats');
    localStorage.setItem('selectedMobileSidebarMenu', 'seats');
    seatsMobileMenuFormSelected();
    removeAirlinesMobileMenuFormSelected();
    removeFlightsMobileMenuFormSelected();
    removeUsersMobileMenuFormSelected();
    seatsMenuFormSelected();
    removeAirlinesMenuFormSelected();
    removeFlightsMenuFormSelected();
    removeUsersMenuFormSelected();
}

// Users Menu Form Clicked
function usersMobileMenuFormClicked() {
    localStorage.setItem('selectedSidebarMenuForm', 'users');
    localStorage.setItem('selectedMobileSidebarMenuForm', 'users');
    localStorage.setItem('selectedSidebarMenu', 'users');
    localStorage.setItem('selectedMobileSidebarMenu', 'users');
    usersMobileMenuFormSelected();
    removeAirlinesMobileMenuFormSelected();
    removeFlightsMobileMenuFormSelected();
    removeSeatsMobileMenuFormSelected();
    usersMenuFormSelected();
    removeAirlinesMenuFormSelected();
    removeFlightsMenuFormSelected();
    removeSeatsMenuFormSelected();
}

// Navbar Menu Tetap Terpilih Saat Halaman Direfresh
document.addEventListener("DOMContentLoaded", function () {
    let selectedNavbarMenu = localStorage.getItem('selectedNavbarMenu');
    if (selectedNavbarMenu === 'dashboardMenu') {
        showLoading();
    } else {
        localStorage.setItem('selectedNavbarMenu', 'dashboardMenu');
        showLoading();
    }
});

// Navbar Menu Tetap Terpilih Saat Halaman Direfresh
document.addEventListener("DOMContentLoaded", function () {
    let selectedSidebarMenuForm = localStorage.getItem('selectedSidebarMenuForm');
    let selectedMobileSidebarMenuForm = localStorage.getItem('selectedMobileSidebarMenuForm');
    let selectedSidebarMenu = localStorage.getItem('selectedSidebarMenu');
    let selectedMobileSidebarMenu = localStorage.getItem('selectedMobileSidebarMenu');
    if (selectedSidebarMenu === 'airlines' && selectedMobileSidebarMenu === 'airlines') {
        airlinesMenuFormClicked();
        airlinesMobileMenuFormClicked();
    } else if (selectedSidebarMenu === 'flights' && selectedMobileSidebarMenu === 'flights') {
        flightsMenuFormClicked();
        flightsMobileMenuFormClicked();
    } else if (selectedSidebarMenu === 'seats' && selectedMobileSidebarMenu === 'seats') {
        seatsMenuFormClicked();
        seatsMobileMenuFormClicked();
    } else if (selectedSidebarMenu === 'users' && selectedMobileSidebarMenu === 'users') {
        usersMenuFormClicked();
        usersMobileMenuFormClicked();
    } else if (selectedSidebarMenuForm === 'airlines' && selectedMobileSidebarMenuForm === 'airlines') {
        airlinesMenuFormClicked();
        airlinesMobileMenuFormClicked();
    } else if (selectedSidebarMenuForm === 'flights' && selectedMobileSidebarMenuForm === 'flights') {
        flightsMenuFormClicked();
        flightsMobileMenuFormClicked();
    } else if (selectedSidebarMenuForm === 'seats' && selectedMobileSidebarMenuForm === 'seats') {
        seatsMenuFormClicked();
        seatsMobileMenuFormClicked();
    } else if (selectedSidebarMenuForm === 'users' && selectedMobileSidebarMenuForm === 'users') {
        usersMenuFormClicked();
        usersMobileMenuFormClicked();
    } else {
        localStorage.setItem('selectedSidebarMenuForm', 'airlines');
        localStorage.setItem('selectedMobileSidebarMenuForm', 'airlines');
        airlinesMenuFormSelected();
        airlinesMobileMenuFormSelected();
    }
});

// Event Click Menu Form
airlinesMenuForm.addEventListener('click', airlinesMenuFormClicked);
flightsMenuForm.addEventListener('click', flightsMenuFormClicked);
seatsMenuForm.addEventListener('click', seatsMenuFormClicked);
usersMenuForm.addEventListener('click', usersMenuFormClicked);

// Event Click Mobile Menu Form
airlinesMobileMenuForm.addEventListener('click', airlinesMobileMenuFormClicked);
flightsMobileMenuForm.addEventListener('click', flightsMobileMenuFormClicked);
seatsMobileMenuForm.addEventListener('click', seatsMobileMenuFormClicked);
usersMobileMenuForm.addEventListener('click', usersMobileMenuFormClicked);

if (level == 0) {
    $('#airlinesMenuForm').removeClass('hidden');
    $('#airlinesMobileMenuForm').removeClass('hidden');
    $('#airlinesMenuForm').addClass('block');
    $('#airlinesMobileMenuForm').addClass('block');
    $('#flightsMenuForm').removeClass('hidden');
    $('#flightsMobileMenuForm').removeClass('hidden');
    $('#flightsMenuForm').addClass('block');
    $('#flightsMobileMenuForm').addClass('block');
    $('#seatsMenuForm').removeClass('hidden');
    $('#seatsMobileMenuForm').removeClass('hidden');
    $('#seatsMenuForm').addClass('block');
    $('#seatsMobileMenuForm').addClass('block');
    $('#usersMenuForm').removeClass('hidden');
    $('#usersMobileMenuForm').removeClass('hidden');
    $('#usersMenuForm').addClass('block');
    $('#usersMobileMenuForm').addClass('block');

    if (selectedAirlineID != '') {
        // Airines Data
        $.ajax({
            url: '../api/v1/airlines.php?id=' + selectedAirlineID,
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": accessToken
            },
            success: function (result) {
                var { airlines } = result.data;
                $('#airline_id').val(airlines[0].airline_id);
                $('#airline_name').val(airlines[0].airline_name);
                $('#airline_code').val(airlines[0].airline_code);
                $('#total_seats').val(airlines[0].total_seats);
            },
            error: function () {
                $('#airlinesForm').addClass('hidden');
                $('#flightsForm').addClass('hidden');
                $('#seatsForm').addClass('hidden');
                $('#usersForm').addClass('hidden');
                $('#session-expired-bg').removeClass('hidden');
                $('#session-expired').removeClass('hidden');
                $('#session-expired').addClass('flex');
                $('#session-expired-login').click(function () {
                    $.ajax({
                        url: '../api/v1/sessions.php?id=' + sessionID,
                        type: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": accessToken
                        },
                        success: function () {
                            $.ajax({
                                url: 'sessionDestroy.php',
                                method: 'POST',
                                success: function () {
                                    localStorage.clear();
                                    window.location.href = "login.php";
                                }
                            });
                        },
                        error: function (result) {
                            console.log(result)
                        }
                    });
                });
            }
        });
    } else if (selectedFlightID != '') {
        // Flight Data
        $.ajax({
            url: '../api/v1/flights.php?id=' + selectedFlightID,
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": accessToken
            },
            success: function (result) {
                var { flights } = result.data;
                function formatDate(dateString) {
                    const [day, month, year, time] = dateString.split(/[\/\s:]/);
                    const formattedTime = time ? time.padStart(2, '0') + ':00' : '00:00';
                    const formattedDate = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}T${formattedTime}`;
                    return formattedDate;
                }
                const departure_time = flights[0].departure_time;
                const formattedDepartureTime = formatDate(departure_time);
                const arrival_time = flights[0].arrival_time;
                const formattedArrivalTime = formatDate(arrival_time);
                $('#airline_name_flights').val(flights[0].airline_name);
                $('#origin').val(flights[0].origin);
                $('#destination').val(flights[0].destination);
                $('#departure_time').val(formattedDepartureTime);
                $('#arrival_time').val(formattedArrivalTime);
                $('#price').val(flights[0].price);
            },
            error: function () {
                $('#airlinesForm').addClass('hidden');
                $('#flightsForm').addClass('hidden');
                $('#seatsForm').addClass('hidden');
                $('#usersForm').addClass('hidden');
                $('#session-expired-bg').removeClass('hidden');
                $('#session-expired').removeClass('hidden');
                $('#session-expired').addClass('flex');
                $('#session-expired-login').click(function () {
                    $.ajax({
                        url: '../api/v1/sessions.php?id=' + sessionID,
                        type: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": accessToken
                        },
                        success: function () {
                            $.ajax({
                                url: 'sessionDestroy.php',
                                method: 'POST',
                                success: function () {
                                    localStorage.clear();
                                    window.location.href = "login.php";
                                }
                            });
                        },
                        error: function (result) {
                            console.log(result)
                        }
                    });
                });
            }
        });
    } else if (selectedSeatID != '') {
        // Seat Data
        $.ajax({
            url: '../api/v1/seats.php?id=' + selectedSeatID,
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": accessToken
            },
            success: function (result) {
                var { seats } = result.data;
                $('#originSeats').val(seats[0].origin);
                $('#destinationSeats').val(seats[0].destination);
                $('#seat_number').val(seats[0].seat_number);
                $('#is_available').val(seats[0].is_available);
            },
            error: function () {
                $('#airlinesForm').addClass('hidden');
                $('#flightsForm').addClass('hidden');
                $('#seatsForm').addClass('hidden');
                $('#usersForm').addClass('hidden');
                $('#session-expired-bg').removeClass('hidden');
                $('#session-expired').removeClass('hidden');
                $('#session-expired').addClass('flex');
                $('#session-expired-login').click(function () {
                    $.ajax({
                        url: '../api/v1/sessions.php?id=' + sessionID,
                        type: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": accessToken
                        },
                        success: function () {
                            $.ajax({
                                url: 'sessionDestroy.php',
                                method: 'POST',
                                success: function () {
                                    localStorage.clear();
                                    window.location.href = "login.php";
                                }
                            });
                        },
                        error: function (result) {
                            console.log(result)
                        }
                    });
                });
            }
        });
    } else if (selectedUserID != '') {
        // User Data
        $.ajax({
            url: '../api/v1/users.php?id=' + selectedUserID,
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": accessToken
            },
            success: function (result) {
                var { users } = result.data;
                $('#username').val(users[0].username);
                $('#password').val(users[0].password);
                $('#email').val(users[0].email);
                $('#fullname').val(users[0].fullname);
                $('#address').val(users[0].address);
                $('#telephone').val(users[0].telephone);
            },
            error: function () {
                $('#airlinesForm').addClass('hidden');
                $('#flightsForm').addClass('hidden');
                $('#seatsForm').addClass('hidden');
                $('#usersForm').addClass('hidden');
                $('#session-expired-bg').removeClass('hidden');
                $('#session-expired').removeClass('hidden');
                $('#session-expired').addClass('flex');
                $('#session-expired-login').click(function () {
                    $.ajax({
                        url: '../api/v1/sessions.php?id=' + sessionID,
                        type: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": accessToken
                        },
                        success: function () {
                            $.ajax({
                                url: 'sessionDestroy.php',
                                method: 'POST',
                                success: function () {
                                    localStorage.clear();
                                    window.location.href = "login.php";
                                }
                            });
                        },
                        error: function (result) {
                            console.log(result)
                        }
                    });
                });
            }
        });
    }

} else if (level == 1) {
    $('#airlinesMenuForm').addClass('hidden');
    $('#airlinesMobileMenuForm').addClass('hidden');
    $('#airlinesMenuForm').removeClass('block');
    $('#airlinesMobileMenuForm').removeClass('block');
    $('#flightsMenuForm').addClass('hidden');
    $('#flightsMobileMenuForm').addClass('hidden');
    $('#flightsMenuForm').removeClass('block');
    $('#flightsMobileMenuForm').removeClass('block');
    $('#seatsMenuForm').addClass('hidden');
    $('#seatsMobileMenuForm').addClass('hidden');
    $('#seatsMenuForm').removeClass('block');
    $('#seatsMobileMenuForm').removeClass('block');
    $('#usersMenuForm').removeClass('hidden');
    $('#usersMobileMenuForm').removeClass('hidden');
    $('#usersMenuForm').addClass('block');
    $('#usersMobileMenuForm').addClass('block');

    if (selectedUserID != '') {
        // Airines Data
        $.ajax({
            url: '../api/v1/users.php?id=' + selectedUserID,
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
                "Authorization": accessToken
            },
            success: function (result) {
                var { users } = result.data;
                $('#username').val(users[0].username);
                $('#password').val(users[0].password);
                $('#email').val(users[0].email);
                $('#fullname').val(users[0].fullname);
                $('#address').val(users[0].address);
                $('#telephone').val(users[0].telephone);
            },
            error: function () {
                $('#airlinesForm').addClass('hidden');
                $('#flightsForm').addClass('hidden');
                $('#seatsForm').addClass('hidden');
                $('#usersForm').addClass('hidden');
                $('#session-expired-bg').removeClass('hidden');
                $('#session-expired').removeClass('hidden');
                $('#session-expired').addClass('flex');
                $('#session-expired-login').click(function () {
                    $.ajax({
                        url: '../api/v1/sessions.php?id=' + sessionID,
                        type: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": accessToken
                        },
                        success: function () {
                            $.ajax({
                                url: 'sessionDestroy.php',
                                method: 'POST',
                                success: function () {
                                    localStorage.clear();
                                    window.location.href = "login.php";
                                }
                            });
                        },
                        error: function (result) {
                            console.log(result)
                        }
                    });
                });
            }
        });
    }
}

// Airlines Enter Button
airline_name.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateAirlines();
    }
});
airline_code.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateAirlines();
    }
});
sendAirlines.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateAirlines();
    }
});


// Flights Enter Button
destination.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateFlights();
    }
});
departure_time.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateFlights();
    }
});
arrival_time.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateFlights();
    }
});
price.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateFlights();
    }
});
sendFlights.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateFlights();
    }
});

// Seats
is_available.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateSeats();
    }
});
sendSeats.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateSeats();
    }
});


// Users
username.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateUsers();
    }
});
password.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateUsers();
    }
});
email.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateUsers();
    }
});
fullname.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateUsers();
    }
});
address.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateUsers();
    }
});
telephone.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateUsers();
    }
});
sendUsers.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        UpdateUsers();
    }
});

sendAirlines.addEventListener('click', UpdateAirlines);
sendFlights.addEventListener('click', UpdateFlights);
sendSeats.addEventListener('click', UpdateSeats);
sendUsers.addEventListener('click', UpdateUsers);

function UpdateAirlines() {
    loadingForm.classList.add('z-50', 'flex');
    loadingForm.classList.remove('hidden');
    // Airlines
    var airlines = {
        airline_name: $('#airline_name').val(),
        airline_code: $('#airline_code').val(),
    };

    $.ajax({
        url: '../api/v1/airlines.php?id=' + selectedAirlineID,
        method: 'PATCH',
        headers: {
            "Content-Type": "application/json",
            "Authorization": accessToken
        },
        data: JSON.stringify(airlines),
        success: function (result) {
            var { messages } = result;
            setTimeout(function () {
                $('#loading-form').addClass('hidden');
                $('#loading-form').removeClass('z-50', 'flex');
                $('#alertSuccess').removeClass('hidden');
                $('#alertSuccess').addClass('flex');
                $('#alertErrorAirlines').addClass('hidden');
                $('#successMessage').append(messages);
            }, 1000);
            setTimeout(function () {
                window.location.href = 'index.php';
            }, 2500);
        },
        error: function (result) {
            var { messages } = result.responseJSON;
            if (messages == 'Access token has expired') {
                $('#airlinesForm').addClass('hidden');
                $('#flightsForm').addClass('hidden');
                $('#seatsForm').addClass('hidden');
                $('#usersForm').addClass('hidden');
                $('#session-expired-bg').removeClass('hidden');
                $('#session-expired').removeClass('hidden');
                $('#session-expired').addClass('flex');
                $('#session-expired-login').click(function () {
                    $.ajax({
                        url: '../api/v1/sessions.php?id=' + sessionID,
                        type: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": accessToken
                        },
                        success: function () {
                            $.ajax({
                                url: 'sessionDestroy.php',
                                method: 'POST',
                                success: function () {
                                    localStorage.clear();
                                    window.location.href = "login.php";
                                }
                            });
                        },
                        error: function (result) {
                            console.log(result)
                        }
                    });
                });
            } else {
                setTimeout(function () {
                    $('#loading-form').addClass('hidden');
                    $('#loading-form').removeClass('z-50', 'flex');
                    $('#alertErrorAirlines').removeClass('hidden');
                    $('#errorMessageAirlines').empty();
                    $('#errorMessageAirlines').append(messages);
                }, 1000)
            }
        }
    });
}

function UpdateFlights() {
    loadingForm.classList.add('z-50', 'flex');
    loadingForm.classList.remove('hidden');
    // Flights
    if ($('#departure_time').val() != '' && $('#arrival_time').val() != '') {
        function formatDate(datetimeString) {
            const [datePart, timePart] = datetimeString.split('T');
            const [year, month, day] = datePart.split('-');
            const [hours, minutes] = timePart.split(':');
            const formattedDate = `${day}/${month}/${year}`;
            const formattedTime = `${hours}:${minutes}`;
            return `${formattedDate} ${formattedTime}`;
        }
        var departure_time = $('#departure_time').val();
        var formattedDepartureTime = formatDate(departure_time);
        var arrival_time = $('#arrival_time').val();
        var formattedArrivalTime = formatDate(arrival_time);
    }
    var flights = {
        destination: $('#destination').val(),
        departure_time: formattedDepartureTime,
        arrival_time: formattedArrivalTime,
        price: $('#price').val(),
    };

    $.ajax({
        url: '../api/v1/flights.php?id=' + selectedFlightID,
        method: 'PATCH',
        headers: {
            "Content-Type": "application/json",
            "Authorization": accessToken
        },
        data: JSON.stringify(flights),
        success: function (result) {
            var { messages } = result;
            setTimeout(function () {
                $('#loading-form').addClass('hidden');
                $('#loading-form').removeClass('z-50', 'flex');
                $('#alertSuccess').removeClass('hidden');
                $('#alertSuccess').addClass('flex');
                $('#alertErrorFlights').addClass('hidden');
                $('#successMessage').append(messages);
            }, 1000);
            setTimeout(function () {
                window.location.href = 'index.php';
            }, 2500);
        },
        error: function (result) {
            var { messages } = result.responseJSON;
            if (messages == 'Access token has expired') {
                $('#airlinesForm').addClass('hidden');
                $('#flightsForm').addClass('hidden');
                $('#seatsForm').addClass('hidden');
                $('#usersForm').addClass('hidden');
                $('#session-expired-bg').removeClass('hidden');
                $('#session-expired').removeClass('hidden');
                $('#session-expired').addClass('flex');
                $('#session-expired-login').click(function () {
                    $.ajax({
                        url: '../api/v1/sessions.php?id=' + sessionID,
                        type: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": accessToken
                        },
                        success: function () {
                            $.ajax({
                                url: 'sessionDestroy.php',
                                method: 'POST',
                                success: function () {
                                    localStorage.clear();
                                    window.location.href = "login.php";
                                }
                            });
                        },
                        error: function (result) {
                            console.log(result)
                        }
                    });
                });
            } else {
                setTimeout(function () {
                    $('#loading-form').addClass('hidden');
                    $('#loading-form').removeClass('z-50', 'flex');
                    $('#alertErrorFlights').removeClass('hidden');
                    $('#errorMessageFlights').empty();
                    $('#errorMessageFlights').append(messages);
                }, 1000)
            }
        }
    });
}

function UpdateSeats() {
    loadingForm.classList.add('z-50', 'flex');
    loadingForm.classList.remove('hidden');
    // Seats
    var seats = {
        is_available: $('#is_available').val(),
    };

    $.ajax({
        url: '../api/v1/seats.php?id=' + selectedSeatID,
        method: 'PATCH',
        headers: {
            "Content-Type": "application/json",
            "Authorization": accessToken
        },
        data: JSON.stringify(seats),
        success: function (result) {
            var { messages } = result;
            setTimeout(function () {
                $('#loading-form').addClass('hidden');
                $('#loading-form').removeClass('z-50', 'flex');
                $('#alertSuccess').removeClass('hidden');
                $('#alertSuccess').addClass('flex');
                $('#alertErrorAirlines').addClass('hidden');
                $('#successMessage').append(messages);
            }, 1000);
            setTimeout(function () {
                window.location.href = 'index.php';
            }, 2500);
        },
        error: function (result) {
            var { messages } = result.responseJSON;
            if (messages == 'Access token has expired') {
                $('#airlinesForm').addClass('hidden');
                $('#flightsForm').addClass('hidden');
                $('#seatsForm').addClass('hidden');
                $('#usersForm').addClass('hidden');
                $('#session-expired-bg').removeClass('hidden');
                $('#session-expired').removeClass('hidden');
                $('#session-expired').addClass('flex');
                $('#session-expired-login').click(function () {
                    $.ajax({
                        url: '../api/v1/sessions.php?id=' + sessionID,
                        type: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": accessToken
                        },
                        success: function () {
                            $.ajax({
                                url: 'sessionDestroy.php',
                                method: 'POST',
                                success: function () {
                                    localStorage.clear();
                                    window.location.href = "login.php";
                                }
                            });
                        },
                        error: function (result) {
                            console.log(result)
                        }
                    });
                });
            } else {
                setTimeout(function () {
                    $('#loading-form').addClass('hidden');
                    $('#loading-form').removeClass('z-50', 'flex');
                    $('#alertErrorSeats').removeClass('hidden');
                    $('#errorMessageSeats').empty();
                    $('#errorMessageSeats').append(messages);
                }, 1000)
            }
        }
    });
}

function UpdateUsers() {
    loadingForm.classList.add('z-50', 'flex');
    loadingForm.classList.remove('hidden');
    // Users
    var users = {
        username: $('#username').val(),
        password: $('#password').val(),
        email: $('#email').val(),
        fullname: $('#fullname').val(),
        address: $('#address').val(),
        telephone: $('#telephone').val(),
    };

    $.ajax({
        url: '../api/v1/users.php?id=' + selectedUserID,
        method: 'PATCH',
        headers: {
            "Content-Type": "application/json",
            "Authorization": accessToken
        },
        data: JSON.stringify(users),
        success: function (result) {
            var { messages } = result;
            setTimeout(function () {
                $('#loading-form').addClass('hidden');
                $('#loading-form').removeClass('z-50', 'flex');
                $('#alertSuccess').removeClass('hidden');
                $('#alertSuccess').addClass('flex');
                $('#alertErrorUsers').addClass('hidden');
                $('#successMessage').append(messages);
            }, 1000);
            setTimeout(function () {
                window.location.href = 'index.php';
            }, 2500);
        },
        error: function (result) {
            var { messages } = result.responseJSON;
            if (messages == 'Access token has expired') {
                $('#seatsForm').addClass('hidden');
                $('#session-expired-bg').removeClass('hidden');
                $('#session-expired').removeClass('hidden');
                $('#session-expired').addClass('flex');
                $('#session-expired-login').click(function () {
                    $.ajax({
                        url: '../api/v1/sessions.php?id=' + sessionID,
                        type: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": accessToken
                        },
                        success: function () {
                            $.ajax({
                                url: 'sessionDestroy.php',
                                method: 'POST',
                                success: function () {
                                    localStorage.clear();
                                    window.location.href = "login.php";
                                }
                            });
                        },
                        error: function (result) {
                            console.log(result)
                        }
                    });
                });
            } else {
                setTimeout(function () {
                    $('#loading-form').addClass('hidden');
                    $('#loading-form').removeClass('z-50', 'flex');
                    $('#alertErrorUsers').removeClass('hidden');
                    $('#errorMessageUsers').empty();
                    $('#errorMessageUsers').append(messages);
                }, 1000)
            }
        }
    });
}

