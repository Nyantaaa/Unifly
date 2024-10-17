// Logout
$.ajax({
    url: '../api/v1/sessions.php?id=' + sessionID,
    type: 'DELETE',
    headers: {
        "Content-Type": "application/json",
        "Authorization": accessToken
    },
    success: function (result) {
        console.log(result)
        var { statusCode } = result
        if (statusCode == 200) {
            window.location.href = 'login.php'
            localStorage.clear()
        }
    }
});