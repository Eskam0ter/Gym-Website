// Get DOM elements
var email = document.getElementById('email');
var password = document.getElementById('password');
var confirm_password = document.getElementById('confirm_password');
var button = document.getElementById('registerBtn');
// Submit event listener for form validation
document.getElementById('register').addEventListener('submit', function (event) {
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var confirm_password = document.getElementById('confirm_password').value;
   // Regular expression for email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    // Validate email format
    if (!emailRegex.test(email)) {
      document.getElementById('email').classList.add('err');
      document.getElementById('error').innerHTML = "Invalid email adress";
      event.preventDefault();
      return;
    }
    else {
      document.getElementById('email').classList.remove('err');
    }
    // Validate password length
    if (password.length < 8) {
      document.getElementById('error').innerHTML = "Password chould be at least 8 characters";
      document.getElementById('password').classList.add('err');
      document.getElementById('confirm_password').classList.add('err');
      document.getElementById('password').value = '';
      document.getElementById('confirm_password').value = '';
      event.preventDefault();
      return;
    }
    else {
      document.getElementById('password').classList.remove('err');
      document.getElementById('confirm_password').classList.remove('err');
    }
    // Validate password and confirm password match
    if (password != confirm_password) {
      document.getElementById('error').innerHTML = "Passwords do not match";
      document.getElementById('password').classList.add('err');
      document.getElementById('confirm_password').classList.add('err');
      document.getElementById('password').value = '';
      document.getElementById('confirm_password').value = '';
      event.preventDefault();
      return;
    }
    else {
      document.getElementById('password').classList.remove('err');
      document.getElementById('confirm_password').classList.remove('err');
    }

})


// Change event listener for email validation using fetch
email.addEventListener('keyup', function() {
  const formData = new FormData(document.getElementById('register'));
  fetch('signup_ajax.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    document.getElementById('error').innerHTML = data.message;
    if (data.message != '') {
      document.getElementById('email').classList.add('err');
      button.disabled = true;
    } 
    else {
      document.getElementById('email').classList.remove('err');
      button.disabled = false
    }

  })
  .catch(error => {
    document.getElementById('error').innerHTML = error.message;
  });

});



    // Регулярное выражение для проверки email-адреса

