const username = document.getElementById('username');
const password = document.getElementById('password');
const login = document.getElementById('login');
const loadingLogin = document.getElementById('loading-login');

function Login() {
  loadingLogin.classList.add('z-50', 'flex');
  loadingLogin.classList.remove('hidden');

  var data = {
    username: $('#username').val(),
    password: $('#password').val(),
  };

  $.ajax({
    url: '../api/v1/sessions.php',
    method: 'POST',
    headers: {
      "Content-Type": "application/json"
    },
    data: JSON.stringify(data),
    success: function (result) {
      var { session_id } = result.data;
      var { user_id } = result.data;
      var { level } = result.data;
      var { access_token } = result.data;
      var { access_token_expires_in } = result.data;
      $.ajax({
        url: 'login.php',
        method: 'POST',
        headers: {
          "Content-Type": "application/json"
        },
        data: JSON.stringify({
          session_id: session_id,
          user_id: user_id,
          level: level,
          access_token: access_token,
          access_token_expires_in: access_token_expires_in,
        }),
        dataType: 'json',
      });
      window.location.href = 'index.php';
    },
    error: function (result) {
      var { messages } = result.responseJSON;
      setTimeout(function () {
        $('#loading-login').addClass('hidden');
        $('#loading-login').removeClass('z-50', 'flex');
        $('#alertError').removeClass('hidden');
        $('#errorMessage').empty();
        $('#errorMessage').append(messages);
      }, 1000)
    }
  });
}

username.addEventListener('keypress', function (event) {
  if (event.key == 'Enter') {
    Login();
  }
});
password.addEventListener('keypress', function (event) {
  if (event.key == 'Enter') {
    Login();
  }
});

login.addEventListener('keypress', function (event) {
  if (event.key == 'Enter') {
    Login();
  }
});

login.addEventListener('click', Login);
