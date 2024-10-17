<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    header("Location: login.php");
} else {
    if (isset($_GET['airline_id'])) {
        $selected_airline_id = $_GET['airline_id'];
    } else if (isset($_GET['flight_id'])) {
        $selected_flight_id = $_GET['flight_id'];
    } else if (isset($_GET['seat_id'])) {
        $selected_seat_id = $_GET['seat_id'];
    } else if (isset($_GET['user_id'])) {
        $selected_user_id = $_GET['user_id'];
    }
    $session_id = $_SESSION['session_id'];
    $user_id = $_SESSION['user_id'];
    $level = $_SESSION['level'];
    $access_token = $_SESSION['access_token'];
    $access_token_expires_in = $_SESSION['access_token_expires_in'];
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Unifly</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="css/final.css" <?php echo time() ?> rel="stylesheet" />
        <link href="css/unifly.css" <?php echo time() ?> rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.7.0.js"
        integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Loading -->
    <div id="loading-bg" class="hidden fixed w-full h-screen bg-white justify-center">
        <span id="loading" class="loading loading-spinner loading-lg"></span>
    </div>

    <!-- Alert Success -->
    <div id="alertSuccess" class="hidden fixed px-48 w-full h-screen bg-white justify-center items-center z-50">
        <div class="flex justify-center alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span id="successMessage"></span>
        </div>
    </div>

    <!-- Session Expired Message -->
    <div id="session-expired-bg" class="hidden fixed w-full h-screen bg-slate-600 opacity-30 z-50"></div>
    <div id="session-expired" class="hidden items-center fixed w-full h-full z-60">
        <div class="flex w-96 mx-auto">
            <div class="flex flex-col px-28 py-16 rounded-3xl bg-sky-600">
                <img src="../assets/img/Unifly-SadBee.png" />
                <h1 class="my-5 text-white text-center text-base font-medium ">Session Expired</h1>
                <a id="session-expired-login"
                    class="flex w-full justify-center rounded-md cursor-pointer bg-sky-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700">Login</a>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="fixed bg-white z-40 w-full shadow-md">
        <div id="navbarContainer" class="px-2 md:px-8 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <!-- Logo -->
                <div class="flex flex-1 items-center justify-center pl-2 md:items-stretch md:justify-start">
                    <div class="flex flex-shrink-0 items-center">
                        <a id="logo" href="index.php" tabindex="-1">
                            <img class="h-12 w-auto" src="../assets/img/Unifly-Logo.png" alt="Unifly" />
                        </a>
                    </div>

                    <!-- Navbar Menu Form -->
                    <div class="hidden md:ml-6 md:block">
                        <div class="flex space-x-4 pl-3.5">
                            <a id="dashboardMenuForm"
                                class="text-sky-700 cursor-pointer border-b-2 border-sky-500 px-3 py-5.5 text-sm font-medium">Dashboard</a>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div
                    class="hidden absolute inset-y-0 right-0 pr-2 mb-0.75 md:block md:static md:inset-auto md:ml-6 md:pr-0">
                    <a id="back" href="index.php" class="logout-button text-sm font-medium" tabindex="-1">
                        Back
                    </a>
                </div>

                <!-- Mobile Back Button -->
                <div class="absolute ml-2 inset-y-0 right-0 flex items-center md:hidden">
                    <a href="index.php"
                        class="inline-flex items-center justify-center rounded-md px-3 py-5 text-sky-500 hover:bg-sky-700 hover:text-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <!-- Back Icon -->
                        <i class="fa-solid fa-arrow-left-long fa-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard -->
    <section id="dashboard" class="flex fixed bg-white w-full h-screen pt-16">
        <!-- Sidebar -->
        <div class="hidden menu py-4 px-4 bg-sky-500 text-base-content md:block overflow-auto z-30">
            <a id="airlinesMenuForm"
                class="text-white cursor-pointer rounded-md block pr-24 py-3 pl-4 my-3 hover:bg-sky-700 text-base font-medium">Airlines</a>
            <a id="flightsMenuForm"
                class="text-white cursor-pointer rounded-md block pr-24 py-3 pl-4 my-3 hover:bg-sky-700 text-base font-medium">Flights</a>
            <a id="seatsMenuForm"
                class="text-white cursor-pointer rounded-md block pr-24 py-3 pl-4 my-3 hover:bg-sky-700 text-base font-medium">Seats</a>
            <a id="usersMenuForm"
                class="text-white cursor-pointer rounded-md block pr-24 py-3 pl-4 my-3 hover:bg-sky-700 text-base font-medium">Users</a>
        </div>
        <!-- Mobile Sidebar -->
        <div class="md:hidden menu p-4 bg-sky-500 text-base-content block overflow-auto z-30">
            <a id="airlinesMobileMenuForm" href="#"
                class="text-white rounded-md text-center block py-3 my-3 hover:bg-sky-700 text-base font-medium"><i
                    class="fa-solid fa-plane fa-lg"></i></a>
            <a id="flightsMobileMenuForm" href="#"
                class="text-white rounded-md text-center block py-3 my-3 hover:bg-sky-700 text-base font-medium"><i
                    class="fa-solid fa-plane-departure fa-lg"></i></a>
            <a id="seatsMobileMenuForm" href="#"
                class="text-white rounded-md text-center block py-3 my-3 hover:bg-sky-700 text-base font-medium"><i
                    class="fa-solid fa-couch fa-lg"></i></a>
            <a id="usersMobileMenuForm" href="#"
                class="text-white rounded-md text-center block py-3 my-3 hover:bg-sky-700 text-base font-medium"><i
                    class="fa-solid fa-user-large fa-lg"></i></a>
        </div>

        <!-- Container Form -->
        <div class="w-full h-full">
            <!-- Loading Form -->
            <div id="loading-form" class="hidden w-full h-full bg-white justify-center z-50">
                <span class="loading loading-spinner loading-lg"></span>
            </div>

            <!-- Airlines Form -->
            <div id="airlinesForm" class="hidden w-full h-full px-4 py-4 overflow-auto z-20">
                <!-- Alert Error -->
                <div id="alertErrorAirlines" class="hidden alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="errorMessageAirlines"></span>
                </div>

                <!-- Form -->
                <div class="flex min-h-full flex-col px-6 py-12 lg:px-8">
                    <div class="my-6 w-full mx-auto max-w-sm sm:mx-auto sm:w-full sm:max-w-sm">
                        <div class="mb-4">
                            <label for="airline_id" class="block text-sm font-medium leading-6 text-gray-900">Airline
                                ID</label>
                            <div class="mt-2">
                                <input id="airline_id" name="airline_id" type="airline_id"
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6"
                                    disabled />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="airline_name" class="block text-sm font-medium leading-6 text-gray-900">Airline
                                Name</label>
                            <div class="mt-2">
                                <input id="airline_name" name="airline_name" type="airline_name"
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="airline_code" class="block text-sm font-medium leading-6 text-gray-900">Airline
                                Code</label>
                            <div class="mt-2">
                                <input id="airline_code" name="airline_code" type="airline_code"
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="total_seats" class="block text-sm font-medium leading-6 text-gray-900">Total
                                Seats</label>
                            <div class="mt-2">
                                <input id="total_seats" name="total_seats" type="total_seats"
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6"
                                    disabled />
                            </div>
                        </div>

                        <div>
                            <button id="sendAirlines"
                                class="flex w-full justify-center rounded-md bg-sky-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700">
                                Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flights Form -->
            <div id="flightsForm" class="hidden w-full h-full px-4 py-4 overflow-auto z-20">
                <!-- Alert Error -->
                <div id="alertErrorFlights" class="hidden alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="errorMessageFlights"></span>
                </div>

                <!-- Form -->
                <div id="flightsFormContainer" class="flex min-h-full flex-col px-20 pt-12 lg:px-48">
                    <div class="flex flex-row">
                        <div id="flightsFormLeft" class="mt-6 w-full mr-7 max-w-sm">
                            <div class="mb-4">
                                <label for="airline_name_flights"
                                    class="block text-sm font-medium leading-6 text-gray-900">Airline Name</label>
                                <div class="mt-2">
                                    <input id="airline_name_flights" name="airline_name_flights"
                                        type="airline_name_flights"
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6"
                                        disabled />
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="origin"
                                    class="block text-sm font-medium leading-6 text-gray-900">Origin</label>
                                <div class="mt-2">
                                    <input id="origin" name="origin" type="origin"
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6"
                                        disabled />
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="destination"
                                    class="block text-sm font-medium leading-6 text-gray-900">Destination</label>
                                <div class="mt-2">
                                    <input id="destination" name="destination" type="destination"
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                                </div>
                            </div>
                        </div>
                        <div id="flightsFormRight" class="mt-6 w-full ml-7 max-w-sm">
                            <div class="mb-4">
                                <label for="departure_time"
                                    class="block text-sm font-medium leading-6 text-gray-900">Departure Time</label>
                                <div class="mt-2">
                                    <input id="departure_time" name="departure_time" type="datetime-local"
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="arrival_time"
                                    class="block text-sm font-medium leading-6 text-gray-900">Arrival Time</label>
                                <div class="mt-2">
                                    <input id="arrival_time" name="arrival_time" type="datetime-local"
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="price"
                                    class="block text-sm font-medium leading-6 text-gray-900">Price</label>
                                <div class="mt-2">
                                    <input id="price" name="price" type="text"
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="flightsFormSend" class="w-full max-w-lg mx-auto">
                        <button id="sendFlights"
                            class="flex w-full justify-center rounded-md bg-sky-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700">
                            Send
                        </button>
                    </div>
                </div>
            </div>

            <!-- Seats Form -->
            <div id="seatsForm" class="hidden w-full h-full px-4 py-4 overflow-auto z-20">
                <!-- Alert Error -->
                <div id="alertErrorSeats" class="hidden alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="errorMessageSeats"></span>
                </div>

                <!-- Form -->
                <div class="flex min-h-full flex-col px-6 py-12 lg:px-8">
                    <div class="my-6 w-full mx-auto max-w-sm sm:mx-auto sm:w-full sm:max-w-sm">
                        <div class="mb-4">
                            <label for="originSeats"
                                class="block text-sm font-medium leading-6 text-gray-900">Origin</label>
                            <div class="mt-2">
                                <input id="originSeats" name="originSeats" type="originSeats" value=""
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6"
                                    disabled />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="destinationSeats"
                                class="block text-sm font-medium leading-6 text-gray-900">Destination</label>
                            <div class="mt-2">
                                <input id="destinationSeats" name="destinationSeats" type="destinationSeats" value=""
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6"
                                    disabled />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="seat_number" class="block text-sm font-medium leading-6 text-gray-900">Seat
                                Number</label>
                            <div class="mt-2">
                                <input id="seat_number" name="seat_number" type="seat_number" value=""
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6"
                                    disabled />
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="is_available" class="block text-sm font-medium leading-6 text-gray-900">Is
                                Available</label>
                            <select id="is_available"
                                class="select border-1.4 border-sky-500 w-full mt-2 focus:outline-none">
                                <option value="0">0</option>
                                <option value="1">1</option>
                            </select>
                        </div>

                        <div>
                            <button id="sendSeats"
                                class="flex w-full justify-center rounded-md bg-sky-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700">
                                Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Form -->
            <div id="usersForm" class="hidden w-full h-full px-4 py-4 overflow-auto z-20">
                <!-- Alert Error -->
                <div id="alertErrorUsers" class="hidden alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="errorMessageUsers"></span>
                </div>

                <!-- Form -->
                <div id="usersFormContainer" class="flex min-h-full flex-col px-20 pt-12 lg:px-48">
                    <div class="flex flex-row">
                        <div id="usersFormLeft" class="mt-6 w-full mr-7 max-w-sm">
                            <div class="mb-4">
                                <label for="username"
                                    class="block text-sm font-medium leading-6 text-gray-900">Username</label>
                                <div class="mt-2">
                                    <input id="username" name="username" type="username" value=""
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" 
                                        <?php if ($level == 1){ echo 'disabled'; } ?> />
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password"
                                    class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                                <div class="mt-2">
                                    <input id="password" name="password" type="text" value=""
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="email"
                                    class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                                <div class="mt-2">
                                    <input id="email" name="email" type="email" value=""
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                                </div>
                            </div>
                        </div>
                        <div id="usersFormRight" class="mt-6 w-full ml-7 max-w-sm">
                            <div class="mb-4">
                                <label for="fullname"
                                    class="block text-sm font-medium leading-6 text-gray-900">Fullname</label>
                                <div class="mt-2">
                                    <input id="fullname" name="fullname" type="text" value=""
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="address"
                                    class="block text-sm font-medium leading-6 text-gray-900">Address</label>
                                <div class="mt-2">
                                    <input id="address" name="address" type="text" value=""
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="telephone"
                                    class="block text-sm font-medium leading-6 text-gray-900">Telephone</label>
                                <div class="mt-2">
                                    <input id="telephone" name="telephone" type="text" value=""
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="usersFormSend" class="w-full max-w-lg mx-auto">
                        <button id="sendUsers"
                            class="flex w-full justify-center rounded-md bg-sky-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700">
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        <?php if (isset($selected_airline_id)) { ?>
            var selectedAirlineID = "<?php echo $selected_airline_id ?>";
            var selectedFlightID = "";
            var selectedSeatID = "";
            var selectedUserID = "";
        <?php } else if (isset($selected_flight_id)) { ?>
                var selectedAirlineID = "";
                var selectedFlightID = "<?php echo $selected_flight_id ?>";
                var selectedSeatID = "";
                var selectedUserID = "";
            <?php } else if (isset($selected_seat_id)) { ?>
                    var selectedAirlineID = "";
                    var selectedFlightID = "";
                    var selectedSeatID = "<?php echo $selected_seat_id ?>";
                    var selectedUserID = "";
                <?php } else if (isset($selected_user_id)) { ?>
                        var selectedAirlineID = "";
                        var selectedFlightID = "";
                        var selectedSeatID = "";
                        var selectedUserID = "<?php echo $selected_user_id ?>";
                    <?php } ?>

        var sessionID = "<?php echo $session_id ?>";
        var userID = "<?php echo $user_id ?>";
        var level = "<?php echo $level ?>";
        var accessToken = "<?php echo $access_token ?>";
        var accessTokenExpiresIn = "<?php echo $access_token_expires_in ?>";
    </script>
    <script src="../js/edit.js"></script>
</body>

</html>