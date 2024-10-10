<?php

if (session_status() == PHP_SESSION_NONE) {
  session_name("GYM_session_no_one_knows");
  session_start();
}

if(isset($_SESSION['user_id'])){
    header("Location: account.php");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign up</title>
    <base href="/~vakhodan/">
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
<body>
    <header class="sing-up-header">
        <div class="container sing-up-header-container flex">
            <a class="sing-up-header-logo" href="index.php">
                <img class="sign-up-header-logo" src="./img/logo.svg" alt="Logo">
            </a>
            <h1 class="sign-up-title uppercase">
                Create an account
            </h1>
        </div>
    </header>
    <div class="container flex sign-up-form-container">
    <div class="red error" id="error"><?php if (isset($error_msg) && !empty($error_msg) && $error_msg != '') echo $error_msg?></div>
        <form id="register" method="post" action="signup_validator.php">
            <div class="label-container flex">
                <label class="uppercase sign-up-input-title" for="email">
                    Email*:
                </label>
                <input class="sign-up-input" id="email" type="email" name="email" value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email'])?>" required>
            </div>
            <div class="label-container flex">
                <label class="uppercase sign-up-input-title" for="password">
                    Password*:
                </label>
                <input class="sign-up-input" id="password" type="password" name="password">
            </div>
            <div class="label-container flex">
                <label class="uppercase sign-up-input-title" for="confirm_password">
                    Password Again*:
                </label>
                <input class="sign-up-input" id="confirm_password" type="password" name="confirm_password">

            </div>
            <div class="flex sign-up-btn-container">
                <button class="uppercase btn btn-reset sign-up-btn" id="registerBtn" type="submit">register</button>
            </div>
        </form>
       <script src="js/signup_ajax.js"></script>
        <p>Already have one?</p>
        <a href="signin.php">Sign in</a>
    </div>
</body>
</html>

