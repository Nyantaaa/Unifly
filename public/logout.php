<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    header("Location: login.php");
} else {
    $session_id = $_SESSION['session_id'];
    $access_token = $_SESSION['access_token'];
}

$_SESSION['session_id'] = '';
$_SESSION['user_id'] = '';
$_SESSION['level'] = '';
$_SESSION['access_token'] = '';
$_SESSION['access_token_expires_in'] = '';
session_destroy();
?>

<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
    crossorigin="anonymous"></script>
<script>
    var sessionID = "<?php echo $session_id ?>";
    var accessToken = "<?php echo $access_token ?>";
</script>
<script src="../js/logout.js"></script>
