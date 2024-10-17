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

  <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
    crossorigin="anonymous"></script>
</head>

<body>
  <!-- Loading -->
  <div id="loading-bg" class="hidden fixed w-full h-screen bg-white justify-center">
    <span id="loading" class="loading loading-spinner loading-lg"></span>
  </div>

  <!-- Session Expired Message -->
  <div id="session-expired-bg" class="hidden fixed w-full h-screen bg-slate-600 opacity-30 z-50"></div>
  <div id="session-expired" class="hidden items-center fixed w-full h-full z-60">
    <div class="flex w-96 mx-auto">
      <div class="flex flex-col px-28 py-16 rounded-3xl bg-sky-600">
        <img src="../assets/img/Unifly-SadBee.png" />
        <h1 class="my-5 text-white text-center text-base font-medium ">Session Expired</h1>
        <a id="session-expired-login"
          class="flex w-full justify-center rounded-md cursor-pointer bg-sky-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-sky-700">Login</a>
      </div>
    </div>
  </div>

  <!-- Alert Delete Data -->
  <div id="delete-data-bg" class="hidden fixed w-full h-screen bg-slate-600 opacity-30 z-50"></div>
  <div id="delete-data" class="hidden items-center fixed w-full h-full z-60">
    <div class="flex w-96 mx-auto">
      <div class="flex flex-col py-16 rounded-3xl bg-sky-600">
        <div class="px-28">
          <img src="../assets/img/Unifly-MadBee.png" />
        </div>
        <h1 class="my-5 text-white text-center text-base font-medium ">Are you sure want delete this data?</h1>
        <div class="flex flex-row">
          <div class="w-full pl-7 pr-3">
            <a id="delete-data-yes"
              class="flex w-full justify-center rounded-md cursor-pointer bg-emerald-500 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-emerald-700">Yes</a>
          </div>
          <div class="w-full pr-7 pl-3">
            <a id="delete-data-no"
              class="flex w-full justify-center rounded-md cursor-pointer bg-red-500 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-700">No</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Navbar -->
  <nav class="fixed bg-white z-40 w-full shadow-md">
    <div id="navbarContainer" class="px-2 md:px-8 lg:px-8">
      <div class="relative flex h-16 items-center justify-between">
        <!-- Hamburger Menu Button -->
        <div class="absolute ml-2 inset-y-0 left-0 flex items-center md:hidden">
          <button id="hamburgerMenu" type="button"
            class="inline-flex items-center justify-center rounded-md p-2 text-sky-500 hover:bg-sky-700 hover:text-white"
            aria-controls="mobile-menu" aria-expanded="false" />
          <!-- Hamburger Icon -->
          <svg id="menuClosed" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
          <!-- Cross(X) Icon -->
          <svg id="menuOpen" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </div>

        <!-- Logo -->
        <div class="flex flex-1 items-center justify-center pl-2 md:items-stretch md:justify-start">
          <div class="flex flex-shrink-0 items-center">
            <a id="logo" href="unifly.php" tabindex="-1">
              <img class="h-12 w-auto" src="../assets/img/Unifly-Logo.png" alt="Unifly" />
            </a>
          </div>

          <!-- Navbar Menu -->
          <div class="hidden md:ml-6 md:block">
            <div class="flex space-x-4 pl-3.5">
              <a id="dashboardMenu"
                class="text-sky-500 cursor-pointer border-b-2 border-transparent px-3 py-5.5 text-sm font-medium">Dashboard</a>
              <a id="docsMenu"
                class="text-sky-500 cursor-pointer border-b-2 border-transparent hover:text-sky-700 px-3 py-5.5 text-sm font-medium">Docs</a>
              <a id="aboutMeMenu"
                class="text-sky-500 cursor-pointer border-b-2 border-transparent hover:text-sky-700 px-3 py-5.5 text-sm font-medium">About Me</a>
              <!-- <a id="postmanMenu"
                class="text-sky-500 cursor-pointer border-b-2 border-transparent hover:text-sky-700 px-3 py-5.5 text-sm font-medium">Postman</a> -->
            </div>
          </div>
        </div>

        <!-- Logout Button -->
        <div class="hidden absolute inset-y-0 right-0 pr-2 mb-0.75 md:block md:static md:inset-auto md:ml-6 md:pr-0">
          <a id="logout" href="logout.php" class="logout-button text-sm font-medium" tabindex="-1">
            Logout
          </a>
        </div>
      </div>
    </div>

    <!-- Navbar Mobile Menu -->
    <div class="md:hidden" id="mobile-menu" hidden>
      <div class="space-y-1 px-2 pb-3 pt-2 bg-sky-500">
        <a id="dashboardMobileMenu"
          class="text-white text-center cursor-pointer rounded-md block px-3 py-2 text-base font-medium">Dashboard</a>
        <a id="docsMobileMenu"
          class="text-white text-center cursor-pointer rounded-md block px-3 py-2 text-base font-medium">Docs</a>
        <a id="aboutMeMobileMenu"
          class="text-white text-center cursor-pointer rounded-md block px-3 py-2 text-base font-medium">About Me</a>
        <!-- <a id="postmanMobileMenu"
          class="text-white text-center cursor-pointer rounded-md block px-3 py-2 text-base font-medium">Postman</a> -->
        <a href="logout.php"
          class="mobile-logout-button text-white bg-sky-700 hover:text-white rounded-md block px-3 py-2 text-base font-medium">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Dashboard -->
  <section id="dashboard" class="flex fixed bg-white w-full h-screen pt-16">
    <!-- Sidebar -->
    <div class="hidden menu py-4 px-4 bg-sky-500 text-base-content md:block overflow-auto z-30">
      <a id="airlines"
        class="text-white cursor-pointer rounded-md block pr-24 py-3 pl-4 my-3 hover:bg-sky-700 text-base font-medium">Airlines</a>
      <a id="bookings"
        class="text-white cursor-pointer rounded-md block pr-24 py-3 pl-4 my-3 hover:bg-sky-700 text-base font-medium">Bookings</a>
      <a id="flights"
        class="text-white cursor-pointer rounded-md block pr-24 py-3 pl-4 my-3 hover:bg-sky-700 text-base font-medium">Flights</a>
      <a id="seats"
        class="text-white cursor-pointer rounded-md block pr-24 py-3 pl-4 my-3 hover:bg-sky-700 text-base font-medium">Seats</a>
      <a id="users"
        class="text-white cursor-pointer rounded-md block pr-24 py-3 pl-4 my-3 hover:bg-sky-700 text-base font-medium">Users</a>
    </div>
    <!-- Mobile Sidebar -->
    <div class="md:hidden menu p-4 bg-sky-500 text-base-content block overflow-auto z-30">
      <a id="airlinesMobile" href="#"
        class="text-white rounded-md text-center block py-3 my-3 hover:bg-sky-700 text-base font-medium"><i
          class="fa-solid fa-plane fa-lg"></i></a>
      <a id="bookingsMobile" href="#"
        class="text-white rounded-md text-center block py-3 my-3 hover:bg-sky-700 text-base font-medium"><i
          class="fa-solid fa-calendar-check fa-lg"></i></a>
      <a id="flightsMobile" href="#"
        class="text-white rounded-md text-center block py-3 my-3 hover:bg-sky-700 text-base font-medium"><i
          class="fa-solid fa-plane-departure fa-lg"></i></a>
      <a id="seatsMobile" href="#"
        class="text-white rounded-md text-center block py-3 my-3 hover:bg-sky-700 text-base font-medium"><i
          class="fa-solid fa-couch fa-lg"></i></a>
      <a id="usersMobile" href="#"
        class="text-white rounded-md text-center block py-3 my-3 hover:bg-sky-700 text-base font-medium"><i
          class="fa-solid fa-user-large fa-lg"></i></a>
    </div>

    <!-- Airlines Table -->
    <div id="airlinesTable" class="hidden w-full h-full px-4 py-4 overflow-auto z-20"></div>

    <!-- Bookings Table -->
    <div id="bookingsTable" class="hidden w-full h-full px-4 py-4 overflow-auto z-20"></div>

    <!-- Flights Table -->
    <div id="flightsTable" class="hidden w-full h-full px-4 py-4 overflow-auto z-20"></div>

    <!-- Seats Table -->
    <div id="seatsTable" class="hidden w-full h-full px-4 py-4 overflow-auto z-20"></div>

    <!-- Users Table -->
    <div id="usersTable" class="hidden w-full h-full px-4 py-4 overflow-auto z-20"></div>
  </section>

  <!-- About Me -->
  <section id="aboutMe" class="hidden fixed w-full h-screen pt-16 text-sky-800 justify-center items-center">
    <!-- About Me-->
    <div class="flex h-full mx-auto overflow-auto justify-center">
      <div class="px-4 pt-16 pl-8">
        <div class="card w-96 glass">
          <figure>
            <img src="../assets/img/Willy-Wijaya.jpg" alt="Willy Wijaya" />
          </figure>
          <div class="card-body">
            <h2 class="card-title justify-center">Willy Wijaya</h2>
            <table class="table">
              <tr>
                <td>Fakultas</td>
                <td>:</td>
                <td>Sains dan Teknologi</td>
              </tr>
              <tr>
                <td>Prodi</td>
                <td>:</td>
                <td>Teknik Informatika</td>
              </tr>
              <tr>
                <td>Email</td>
                <td>:</td>
                <td>willywijaya052@gmail.com</td>
              </tr>
              <tr>
                <td>No. Telp</td>
                <td>:</td>
                <td>085179552813</td>
              </tr>
            </table>
          </div>
        </div>
        <div class="h-16"></div>
      </div>
    </div>
  </section>

  <!-- Docs -->
  <section id="docs" class="hidden fixed w-full h-screen pt-16 bg-white">
    <div class="h-full overflow-auto pb-16">
      <!-- Airlines Docs -->
      <div class="py-5 mx-10 border-b-4 border-black">
        <h1 class="font-sans text-2xl font-medium text-center text-black">Documentation</h1>
      </div>
      <div class="py-5 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">Airlines</h1>
      </div>
      <div class="pb-5 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">GET</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/airlines/{id}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/airlines/{name}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/airlines/{code}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/airlines/{page}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/airlines/{pageSize}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">This will return a data of airlines in this API.</p>
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div class="border-b-2 border-black pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Airline Unique id</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">name</p>
            <p class="font-sans text-base text-start text-black">Returns a list of airlines that have a similar name to
              the value</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">code</p>
            <p class="font-sans text-base text-start text-black">Returns a list of airlines that have a similar code to
              the value</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">page</p>
            <p class="font-sans text-base text-start text-black">Used to navigate to a specific page</p>
          </div>
          <div class="pt-3">
            <p class="font-sans text-base text-start text-black font-semibold">pageSize</p>
            <p class="font-sans text-base text-start text-black">Changes the quantity of rows returned for each page</p>
          </div>
        </div>
      </div>

      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">POST</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/airlines</p>
      </div>
      
      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">PATCH</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/airlines/{id}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div>
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Airlines Unique id</p>
          </div>
        </div>
      </div>

      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">DELETE</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/airlines/{id}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div>
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Airlines Unique id</p>
          </div>
        </div>
      </div>

      <!-- Bookings Docs -->
      <div class="pb-5 pt-3 mt-10 mx-10 border-t-4 border-black">
        <h1 class="font-sans text-2xl font-medium text-start text-black">Bookings</h1>
      </div>
      <div class="pb-5 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">GET</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/bookings/{id}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/bookings/{fullname}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/bookings/{seat}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/bookings/{page}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/bookings/{pageSize}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">This will return a data of bookings in this API.</p>
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div class="border-b-2 border-black pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Bookings Unique id</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">name</p>
            <p class="font-sans text-base text-start text-black">Returns a list of bookings that have a similar name to
              the value</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">code</p>
            <p class="font-sans text-base text-start text-black">Returns a list of bookings that have a similar code to
              the value</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">page</p>
            <p class="font-sans text-base text-start text-black">Used to navigate to a specific page</p>
          </div>
          <div class="pt-3">
            <p class="font-sans text-base text-start text-black font-semibold">pageSize</p>
            <p class="font-sans text-base text-start text-black">Changes the quantity of rows returned for each page</p>
          </div>
        </div>
      </div>

      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">POST</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/bookings</p>
      </div>
      
      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">PATCH</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/bookings/{id}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div>
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Bookings Unique id</p>
          </div>
        </div>
      </div>

      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">DELETE</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/bookings/{id}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div>
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Bookings Unique id</p>
          </div>
        </div>
      </div>

      <!-- Flights Docs -->
      <div class="pb-5 pt-3 mt-10 mx-10 border-t-4 border-black">
        <h1 class="font-sans text-2xl font-medium text-start text-black">Flights</h1>
      </div>
      <div class="pb-5 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">GET</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/flights/{id}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/flights/{origin}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/flights/{destination}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/flights/{page}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/flights/{pageSize}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">This will return a data of flights in this API.</p>
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div class="border-b-2 border-black pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Flights Unique id</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">name</p>
            <p class="font-sans text-base text-start text-black">Returns a list of flights that have a similar name to
              the value</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">code</p>
            <p class="font-sans text-base text-start text-black">Returns a list of flights that have a similar code to
              the value</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">page</p>
            <p class="font-sans text-base text-start text-black">Used to navigate to a specific page</p>
          </div>
          <div class="pt-3">
            <p class="font-sans text-base text-start text-black font-semibold">pageSize</p>
            <p class="font-sans text-base text-start text-black">Changes the quantity of rows returned for each page</p>
          </div>
        </div>
      </div>

      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">POST</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/flights</p>
      </div>
      
      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">PATCH</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/flights/{id}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div>
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Flights Unique id</p>
          </div>
        </div>
      </div>

      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">DELETE</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/flights/{id}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div>
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Flights Unique id</p>
          </div>
        </div>
      </div>

      <!-- Seats Docs -->
      <div class="pb-5 pt-3 mt-10 mx-10 border-t-4 border-black">
        <h1 class="font-sans text-2xl font-medium text-start text-black">Seats</h1>
      </div>
      <div class="pb-5 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">GET</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/seats/{id}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/seats/{seat}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/seats/{available}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/seats/{page}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/seats/{pageSize}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">This will return a data of seats in this API.</p>
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div class="border-b-2 border-black pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Seats Unique id</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">name</p>
            <p class="font-sans text-base text-start text-black">Returns a list of seats that have a similar name to
              the value</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">code</p>
            <p class="font-sans text-base text-start text-black">Returns a list of seats that have a similar code to
              the value</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">page</p>
            <p class="font-sans text-base text-start text-black">Used to navigate to a specific page</p>
          </div>
          <div class="pt-3">
            <p class="font-sans text-base text-start text-black font-semibold">pageSize</p>
            <p class="font-sans text-base text-start text-black">Changes the quantity of rows returned for each page</p>
          </div>
        </div>
      </div>

      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">POST</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/seats</p>
      </div>
      
      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">PATCH</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/seats/{id}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div>
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Seats Unique id</p>
          </div>
        </div>
      </div>

      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">DELETE</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/seats/{id}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div>
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Seats Unique id</p>
          </div>
        </div>
      </div>

      <!-- Users Docs -->
      <div class="pb-5 pt-3 mt-10 mx-10 border-t-4 border-black">
        <h1 class="font-sans text-2xl font-medium text-start text-black">Users</h1>
      </div>
      <div class="pb-5 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">GET</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/users/{id}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/users/{seat}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/users/{available}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/users/{page}</p>
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/users/{pageSize}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">This will return a data of users in this API.</p>
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div class="border-b-2 border-black pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Users Unique id</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">name</p>
            <p class="font-sans text-base text-start text-black">Returns a list of users that have a similar name to
              the value</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">code</p>
            <p class="font-sans text-base text-start text-black">Returns a list of users that have a similar code to
              the value</p>
          </div>
          <div class="border-b-2 border-black pt-3 pb-4">
            <p class="font-sans text-base text-start text-black font-semibold">page</p>
            <p class="font-sans text-base text-start text-black">Used to navigate to a specific page</p>
          </div>
          <div class="pt-3">
            <p class="font-sans text-base text-start text-black font-semibold">pageSize</p>
            <p class="font-sans text-base text-start text-black">Changes the quantity of rows returned for each page</p>
          </div>
        </div>
      </div>

      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">POST</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/users</p>
      </div>
      
      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">PATCH</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/users/{id}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div>
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Users Unique id</p>
          </div>
        </div>
      </div>

      <div class="pb-5 pt-10 mx-10">
        <h1 class="font-sans text-2xl font-medium text-start text-black">DELETE</h1>
      </div>
      <div class="pb-5 mx-10">
        <p class="font-sans text-lg font-medium text-start text-black">Endpoint:
          http://localhost/Unifly/api/v1/users/{id}</p>
      </div>
      <div class="mx-10 mb-3">
        <p class="font-sans text-base text-start text-black">You can use the following query parameters:</p>
      </div>
      <div class="mx-10 border-2 border-black rounded-md">
        <div class="py-5 mx-12">
          <div>
            <p class="font-sans text-base text-start text-black font-semibold">id</p>
            <p class="font-sans text-base text-start text-black">Users Unique id</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Postman -->
  <!-- <section id="postman" class="hidden fixed w-full h-screen bg-white"></section> -->

  <script type="text/javascript">
    var sessionID = "<?php echo $session_id ?>";
    var userID = "<?php echo $user_id ?>";
    var level = "<?php echo $level ?>";
    var accessToken = "<?php echo $access_token ?>";
    var accessTokenExpiresIn = "<?php echo $access_token_expires_in ?>";
  </script>
  <script src="../js/unifly.js"></script>
</body>

</html>