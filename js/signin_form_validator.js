// Add an event listener to the form with the ID 'login'
document.getElementById('login').addEventListener('submit', function(event) {
  // Retrieve values of email and password input fields
  var email = document.getElementById('email').value;
  var password = document.getElementById('password').value;

  // Regular expression for validating email format
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // Check if the email is in a valid format using the regex
  if (!emailRegex.test(email)) {
    // Prevent form submission
    event.preventDefault();

    // Display error message for invalid email
    document.getElementById('error').innerHTML = "Invalid email address";

    // Add 'err' class to the email input field for styling
    document.getElementById('email').classList.add('err');

    // Clear the password field
    document.getElementById('password').value = '';

    // Exit the function
    return;
  }

  // Check if the password is empty
  if (password == '') {
    // Prevent form submission
    event.preventDefault();

    // Display error message for empty password
    document.getElementById('error').innerHTML = "Password cannot be empty";

    // Add 'err' class to the password input field for styling
    document.getElementById('password').classList.add('err');

    // Exit the function
    return;
  }
});
