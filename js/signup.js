// Users
const username = document.getElementById('username');
const password = document.getElementById('password');
const email = document.getElementById('email');
const fullname = document.getElementById('fullname');
const address = document.getElementById('address');
const telephone = document.getElementById('telephone');
const signup = document.getElementById('signup');
const login = document.getElementById('login');
const loadingSignUp = document.getElementById('loading-signup');

function SignUp() {
    loadingSignUp.classList.add('z-50', 'flex');
    loadingSignUp.classList.remove('hidden');

    var data = {
        username: $('#username').val(),
        password: $('#password').val(),
        email: $('#email').val(),
        fullname: $('#fullname').val(),
        address: $('#address').val(),
        telephone: $('#telephone').val(),
    };

    $.ajax({
        url: '../api/v1/users.php',
        method: 'POST',
        headers: {
            "Content-Type": "application/json"
        },
        data: JSON.stringify(data),
        success: function (result) {
            console.log(result);
            var { messages } = result;
            setTimeout(function () {
                $('#loading-signup').addClass('hidden');
                $('#loading-signup').removeClass('z-50', 'flex');
                $('#alertSuccess').removeClass('hidden');
                $('#alertSuccess').addClass('flex');
                $('#alertErrorAirlines').addClass('hidden');
                $('#successMessage').append(messages);
            }, 1000);
        },
        error: function (result) {
            var { messages } = result.responseJSON;
            setTimeout(function () {
                $('#loading-signup').addClass('hidden');
                $('#loading-signup').removeClass('z-50', 'flex');
                $('#alertError').removeClass('hidden');
                $('#errorMessage').empty();
                $('#errorMessage').append(messages);
            }, 1000)
        }
    });
}




// Users
username.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        SignUp();
    }
});
password.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        SignUp();
    }
});
email.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        SignUp();
    }
});
fullname.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        SignUp();
    }
});
address.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        SignUp();
    }
});
telephone.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        SignUp();
    }
});
signup.addEventListener('keypress', function (event) {
    if (event.key == 'Enter') {
        SignUp();
    }
});

signup.addEventListener('click', SignUp)
