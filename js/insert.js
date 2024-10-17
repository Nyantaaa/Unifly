// Input dan Button
// Airlines
const airline_name = document.getElementById('airline_name');
const airline_code = document.getElementById('airline_code');
const destination = document.getElementById('destination');
const sendAirlines = document.getElementById('sendAirlines');

// Bookings
const fullnameBookings = document.getElementById('fullnameBookings');
const destinationBookings = document.getElementById('destinationBookings');
const seat_number = document.getElementById('seat_number');
const sendBookings = document.getElementById('sendBookings');

// Users
const username = document.getElementById('username');
const password = document.getElementById('password');
const email = document.getElementById('email');
const fullnameUsers = document.getElementById('fullnameUsers');
const address = document.getElementById('address');
const telephone = document.getElementById('telephone');
const sendUsers = document.getElementById('sendUsers');


// Menu Form
const airlinesMenuForm = document.getElementById('airlinesMenuForm');
const bookingsMenuForm = document.getElementById('bookingsMenuForm');
const usersMenuForm = document.getElementById('usersMenuForm');

// Mobile Menu Form
const airlinesMobileMenuForm = document.getElementById('airlinesMobileMenuForm');
const bookingsMobileMenuForm = document.getElementById('bookingsMobileMenuForm');
const usersMobileMenuForm = document.getElementById('usersMobileMenuForm');

// Form
const airlinesForm = document.getElementById('airlinesForm');
const bookingsForm = document.getElementById('bookingsForm');
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

// Bookings Menu Form Selected
function bookingsMenuFormSelected() {
    bookingsMenuForm.classList.add('bg-sky-700');
    bookingsMenuForm.classList.remove('hover:bg-sky-700');
    bookingsForm.classList.add('block');
    bookingsForm.classList.remove('hidden');
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

// Bookings Menu Form Not Selected
function removeBookingsMenuFormSelected() {
    bookingsMenuForm.classList.add('hover:bg-sky-700');
    bookingsMenuForm.classList.remove('bg-sky-700');
    bookingsForm.classList.add('hidden');
    bookingsForm.classList.remove('block');
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
    removeBookingsMenuFormSelected();
    removeUsersMenuFormSelected();
    airlinesMobileMenuFormSelected();
    removeBookingsMobileMenuFormSelected();
    removeUsersMobileMenuFormSelected();
}

// Bookings Menu Form Clicked
function bookingsMenuFormClicked() {
    localStorage.setItem('selectedSidebarMenuForm', 'bookings');
    localStorage.setItem('selectedMobileSidebarMenuForm', 'bookings');
    localStorage.setItem('selectedSidebarMenu', 'bookings');
    localStorage.setItem('selectedMobileSidebarMenu', 'bookings');
    bookingsMenuFormSelected();
    removeAirlinesMenuFormSelected();
    removeUsersMenuFormSelected();
    bookingsMobileMenuFormSelected();
    removeAirlinesMobileMenuFormSelected();
    removeUsersMobileMenuFormSelected();
}


// Users Menu Form Clicked
function usersMenuFormClicked() {
    localStorage.setItem('selectedSidebarMenuForm', 'users');
    localStorage.setItem('selectedMobileSidebarMenuForm', 'users');
    localStorage.setItem('selectedSidebarMenu', 'users');
    localStorage.setItem('selectedMobileSidebarMenu', 'users');
    usersMenuFormSelected();
    removeBookingsMenuFormSelected();
    removeAirlinesMenuFormSelected();
    usersMobileMenuFormSelected();
    removeBookingsMobileMenuFormSelected();
    removeAirlinesMobileMenuFormSelected();
}

// Airlines Mobile Menu Form Selected
function airlinesMobileMenuFormSelected() {
    airlinesMobileMenuForm.classList.add('bg-sky-700');
    airlinesMobileMenuForm.classList.remove('hover:bg-sky-700');
    airlinesForm.classList.add('block');
    airlinesForm.classList.remove('hidden');
}

// Bookings Menu Form Selected
function bookingsMobileMenuFormSelected() {
    bookingsMobileMenuForm.classList.add('bg-sky-700');
    bookingsMobileMenuForm.classList.remove('hover:bg-sky-700');
    bookingsForm.classList.add('block');
    bookingsForm.classList.remove('hidden');
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

// Bookings Mobile Menu Form Not Selected
function removeBookingsMobileMenuFormSelected() {
    bookingsMobileMenuForm.classList.add('hover:bg-sky-700');
    bookingsMobileMenuForm.classList.remove('bg-sky-700');
    bookingsForm.classList.add('hidden');
    bookingsForm.classList.remove('block');
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
    removeBookingsMobileMenuFormSelected();
    removeUsersMobileMenuFormSelected();
    airlinesMenuFormSelected();
    removeBookingsMenuFormSelected();
    removeUsersMenuFormSelected();
}

// Bookings Menu Form Clicked
function bookingsMobileMenuFormClicked() {
    localStorage.setItem('selectedSidebarMenuForm', 'bookings');
    localStorage.setItem('selectedMobileSidebarMenuForm', 'bookings');
    localStorage.setItem('selectedSidebarMenu', 'bookings');
    localStorage.setItem('selectedMobileSidebarMenu', 'bookings');
    bookingsMobileMenuFormSelected();
    removeAirlinesMobileMenuFormSelected();
    removeUsersMobileMenuFormSelected();
    bookingsMenuFormSelected();
    removeAirlinesMenuFormSelected();
    removeUsersMenuFormSelected();
}

// Users Menu Form Clicked
function usersMobileMenuFormClicked() {
    localStorage.setItem('selectedSidebarMenuForm', 'users');
    localStorage.setItem('selectedMobileSidebarMenuForm', 'users');
    localStorage.setItem('selectedSidebarMenu', 'users');
    localStorage.setItem('selectedMobileSidebarMenu', 'users');
    usersMobileMenuFormSelected();
    removeBookingsMobileMenuFormSelected();
    removeAirlinesMobileMenuFormSelected();
    usersMenuFormSelected();
    removeBookingsMenuFormSelected();
    removeAirlinesMenuFormSelected();
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
    } else if (selectedSidebarMenu === 'bookings' && selectedMobileSidebarMenu === 'bookings') {
        bookingsMenuFormClicked();
        bookingsMobileMenuFormClicked();
    } else if (selectedSidebarMenu === 'users' && selectedMobileSidebarMenu === 'users') {
        usersMenuFormClicked();
        usersMobileMenuFormClicked();
    } else if (selectedSidebarMenuForm === 'airlines' && selectedMobileSidebarMenuForm === 'airlines') {
        airlinesMenuFormClicked();
        airlinesMobileMenuFormClicked();
    } else if (selectedSidebarMenuForm === 'bookings' && selectedMobileSidebarMenuForm === 'bookings') {
        bookingsMenuFormClicked();
        bookingsMobileMenuFormClicked();
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
bookingsMenuForm.addEventListener('click', bookingsMenuFormClicked);
usersMenuForm.addEventListener('click', usersMenuFormClicked);

// Event Click Mobile Menu Form
airlinesMobileMenuForm.addEventListener('click', airlinesMobileMenuFormClicked);
bookingsMobileMenuForm.addEventListener('click', bookingsMobileMenuFormClicked);
usersMobileMenuForm.addEventListener('click', usersMobileMenuFormClicked);

if (level == 0){
    $('#airlinesMenuForm').removeClass('hidden');
    $('#airlinesMobileMenuForm').removeClass('hidden');
    $('#airlinesMenuForm').addClass('block');
    $('#airlinesMobileMenuForm').addClass('block');
    $('#bookingsMenuForm').removeClass('hidden');
    $('#bookingsMobileMenuForm').removeClass('hidden');
    $('#bookingsMenuForm').addClass('block');
    $('#bookingsMobileMenuForm').addClass('block');
    $('#usersMenuForm').removeClass('hidden');
    $('#usersMobileMenuForm').removeClass('hidden');
    $('#usersMenuForm').addClass('block');
    $('#usersMobileMenuForm').addClass('block');

    $.ajax({
        url: '../api/v1/airlines.php',
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
            "Authorization": accessToken
        },
        error: function () {
            $('#airlinesForm').addClass('hidden');
            $('#bookingsForm').addClass('hidden');
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
} else if (level == 1){
    $('#airlinesMenuForm').addClass('hidden');
    $('#airlinesMobileMenuForm').addClass('hidden');
    $('#airlinesMenuForm').removeClass('block');
    $('#airlinesMobileMenuForm').removeClass('block');
    $('#bookingsMenuForm').removeClass('hidden');
    $('#bookingsMobileMenuForm').removeClass('hidden');
    $('#bookingsMenuForm').addClass('block');
    $('#bookingsMobileMenuForm').addClass('block');
    $('#usersMenuForm').addClass('hidden');
    $('#usersMobileMenuForm').addClass('hidden');
    $('#usersMenuForm').removeClass('block');
    $('#usersMobileMenuForm').removeClass('block');

    $.ajax({
        url: '../api/v1/airlines.php',
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
            "Authorization": accessToken
        },
        error: function () {
            $('#airlinesForm').addClass('hidden');
            $('#bookingsForm').addClass('hidden');
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

sendAirlines.addEventListener('click', InsertAirlines);
sendBookings.addEventListener('click', InsertBookings);
sendUsers.addEventListener('click', InsertUsers);

// Airlines Enter Button
airline_name.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertAirlines();
    }
});
airline_code.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertAirlines();
    }
});
destination.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertAirlines();
    }
});
sendAirlines.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertAirlines();
    }
});

// Bookings Enter Button
fullnameBookings.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertBookings();
    }
});
destinationBookings.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertBookings();
    }
});
seat_number.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertBookings();
    }
});
sendBookings.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertBookings();
    }
});

// Users
username.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertUsers();
    }
});
password.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertUsers();
    }
});
email.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertUsers();
    }
});
fullnameUsers.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertUsers();
    }
});
address.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertUsers();
    }
});
telephone.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertUsers();
    }
});
sendUsers.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        InsertUsers();
    }
});

function InsertAirlines() {
    loadingForm.classList.add('z-50', 'flex');
    loadingForm.classList.remove('hidden');
    // Airlines
    var airlines = {
        airline_name: $('#airline_name').val(),
        airline_code: $('#airline_code').val(),
        destination: $('#destination').val(),
        total_seats: '9',
    };

    $.ajax({
        url: '../api/v1/airlines.php',
        method: 'POST',
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

function InsertBookings() {
    loadingForm.classList.add('z-50', 'flex');
    loadingForm.classList.remove('hidden');
    // Bookings
    var bookings = {
        fullname: $('#fullnameBookings').val(),
        destination: $('#destinationBookings').val(),
        seat_number: $('#seat_number').val(),
    };

    $.ajax({
        url: '../api/v1/bookings.php',
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
            "Authorization": accessToken
        },
        data: JSON.stringify(bookings),
        success: function (result) {
            var { messages } = result;
            setTimeout(function () {
                $('#loading-form').addClass('hidden');
                $('#loading-form').removeClass('z-50', 'flex');
                $('#alertSuccess').removeClass('hidden');
                $('#alertSuccess').addClass('flex');
                $('#alertErrorBookings').addClass('hidden');
                $('#successMessage').append(messages);
            }, 1000);
            setTimeout(function () {
                window.location.href = 'index.php';
            }, 2500);
        },
        error: function (result) {
            var { messages } = result.responseJSON;
            if (messages == 'Access token has expired') {
                $('#bookingsForm').addClass('hidden');
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
                    $('#alertErrorBookings').removeClass('hidden');
                    $('#errorMessageBookings').empty();
                    $('#errorMessageBookings').append(messages);
                }, 1000)
            }
        }
    });
}

function InsertUsers() {
    loadingForm.classList.add('z-50', 'flex');
    loadingForm.classList.remove('hidden');
    // Users
    var users = {
        username: $('#username').val(),
        password: $('#password').val(),
        email: $('#email').val(),
        fullname: $('#fullnameUsers').val(),
        address: $('#address').val(),
        telephone: $('#telephone').val(),
    };

    $.ajax({
        url: '../api/v1/users.php',
        method: 'POST',
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

