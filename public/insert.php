<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    header("Location: login.php");
} else {
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

                <!-- Logout Button -->
                <div
                    class="hidden absolute inset-y-0 right-0 pr-2 mb-0.75 md:block md:static md:inset-auto md:ml-6 md:pr-0">
                    <a id="back" href="index.php" class="logout-button text-sm font-medium" tabindex="-1">
                        Back
                    </a>
                </div>

                <!-- Back Button -->
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
                class="hidden text-white cursor-pointer rounded-md pr-24 py-3 pl-4 my-3 hover:bg-sky-700 text-base font-medium">Airlines</a>
            <a id="bookingsMenuForm"
                class="hidden text-white cursor-pointer rounded-md pr-24 py-3 pl-4 my-3 hover:bg-sky-700 text-base font-medium">Bookings</a>
            <a id="usersMenuForm"
                class="hidden text-white cursor-pointer rounded-md pr-24 py-3 pl-4 my-3 hover:bg-sky-700 text-base font-medium">Users</a>
        </div>
        <!-- Mobile Sidebar Form -->
        <div class="md:hidden menu p-4 bg-sky-500 text-base-content block overflow-auto z-30">
            <a id="airlinesMobileMenuForm" href="#"
                class="text-white rounded-md text-center block py-3 my-3 hover:bg-sky-700 text-base font-medium"><i
                    class="fa-solid fa-plane fa-lg"></i></a>
            <a id="bookingsMobileMenuForm" href="#"
                class="text-white rounded-md text-center block py-3 my-3 hover:bg-sky-700 text-base font-medium"><i
                    class="fa-solid fa-calendar-check fa-lg"></i></a>
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
                            <label for="airline_name" class="block text-sm font-medium leading-6 text-gray-900">Airline
                                Name</label>
                            <div class="mt-2">
                                <input id="airline_name" name="airline_name" type="airline_name" value=""
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="airline_code" class="block text-sm font-medium leading-6 text-gray-900">Airline
                                Code</label>
                            <div class="mt-2">
                                <input id="airline_code" name="airline_code" type="airline_code" value=""
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="destination"
                                class="block text-sm font-medium leading-6 text-gray-900">Destination</label>
                            <div class="mt-2">
                                <input id="destination" name="destination" type="destination" value=""
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-sky-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
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

            <!-- Bookings Form -->
            <div id="bookingsForm" class="hidden w-full h-full px-4 py-4 overflow-auto z-20">
                <!-- Alert Error -->
                <div id="alertErrorBookings" class="hidden alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="errorMessageBookings"></span>
                </div>

                <!-- Form -->
                <div class="flex min-h-full flex-col px-6 py-12 lg:px-8">
                    <div class="my-6 w-full mx-auto max-w-sm sm:mx-auto sm:w-full sm:max-w-sm">
                        <div class="mb-4">
                            <label for="fullnameBookings"
                                class="block text-sm font-medium leading-6 text-gray-900">Fullname</label>
                            <div class="mt-2">
                                <input id="fullnameBookings" name="fullnameBookings" type="text" value=""
                                    class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="destinationBookings"
                                class="block text-sm font-medium leading-6 text-gray-900">Destination</label>
                            <select id="destinationBookings" value="" class="select border-1.4 border-sky-500 w-full mt-2 focus:outline-none">
                                <option disabled selected>Select Destination</option>
                                <option value="Aceh">Aceh</option>
                                <option value="Balikpapan">Balikpapan</option>
                                <option value="Bandung">Bandung</option>
                                <option value="Banjarbaru">Banjarbaru</option>
                                <option value="Batam">Batam</option>
                                <option value="Manado">Manado</option>
                                <option value="Padang">Padang</option>
                                <option value="Palembang">Palembang</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="seat_number" class="block text-sm font-medium leading-6 text-gray-900">Seat
                                Number</label>
                            <select id="seat_number"
                                class="select border-1.4 border-sky-500 w-full mt-2 focus:outline-none">
                                <option disabled selected>Select Seat Number</option>
                                <option value="A1">A1</option>
                                <option value="A2">A2</option>
                                <option value="A3">A3</option>
                                <option value="B1">B1</option>
                                <option value="B2">B2</option>
                                <option value="B3">B3</option>
                                <option value="C1">C1</option>
                                <option value="C2">C2</option>
                                <option value="C3">C3</option>
                            </select>
                        </div>

                        <div>
                            <button id="sendBookings"
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
                                        class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password"
                                    class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                                <div class="mt-2">
                                    <input id="password" name="password" type="password" value=""
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
                                <label for="fullnameUsers"
                                    class="block text-sm font-medium leading-6 text-gray-900">Fullname</label>
                                <div class="mt-2">
                                    <input id="fullnameUsers" name="fullnameUsers" type="text" value=""
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
        var sessionID = "<?php echo $session_id ?>";
        var userID = "<?php echo $user_id ?>";
        var level = "<?php echo $level ?>";
        var accessToken = "<?php echo $access_token ?>";
        var accessTokenExpiresIn = "<?php echo $access_token_expires_in ?>";
    </script>
    <script src="../js/insert.js"></script>
</body>

</html>