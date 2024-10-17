<?php
session_start();
if (isset($_SESSION['access_token'])) {
    header("Location: index.php");
} else {
    // Mendapatkan data dari permintaan AJAX
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Memasukkan data ke dalam sesi PHP
    if (isset($requestData)) {
        $_SESSION['session_id'] = $requestData['session_id'];
        $_SESSION['user_id'] = $requestData['user_id'];
        $_SESSION['level'] = $requestData['level'];
        $_SESSION['access_token'] = $requestData['access_token'];
        $_SESSION['access_token_expires_in'] = $requestData['access_token_expires_in'];
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full bg-white" data-theme="light">

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

<body class="h-full">
    <!-- Loading login -->
    <div id="loading-login" class="hidden fixed w-full h-screen bg-white justify-center z-50">
        <span class="loading loading-spinner loading-lg "></span>
    </div>

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <!-- Alert Error -->
        <div id="alertError" class="hidden mb-16">
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span id="errorMessage"></span>
            </div>
        </div>

        <!-- Login Form -->
        <div class="mx-auto w-full max-w-sm sm:mx-auto sm:w-full sm:max-w-sm">
            <img class="mx-auto h-16 w-auto" src="../assets/img/Unifly-Logo.png" alt="Unifly" />
        </div>
        <div class="my-6 w-full mx-auto max-w-sm sm:mx-auto sm:w-full sm:max-w-sm">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
                <div class="mt-2">
                    <input id="username" name="username" type="username" value="" required
                        class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                <div class="mt-2">
                    <input id="password" name="password" type="password" value="" required
                        class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-sky-500 focus:outline-none placeholder:text-sky-500 sm:text-sm sm:leading-6" />
                </div>
            </div>

            <div>
                <button id="login"
                    class="flex w-full justify-center rounded-md bg-sky-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-sky-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700">
                    Login
                </button>
            </div>


            <p class="mt-10 text-center text-sm text-gray-900">
                Dont have an account?
                <a href="signup.php" class="font-semibold leading-6 text-sky-500 hover:text-sky-700 underline" tabindex="-1">Sign
                    Up !</a>
            </p>
        </div>
    </div>
    <script src="../js/login.js"></script>
</body>

</html>