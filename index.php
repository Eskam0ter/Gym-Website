<?php
ob_start();
session_name("GYM_session_no_one_knows");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gym</title>
    <base href="/~vakhodan/">
  <link rel="icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/styles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
</head>
<body>
<header class="header">
    <div class="container flex header-container">
        <a href="index.php" class="header-logo header-logo-link">
            <img src="./img/logo.svg" class="header-logo header-logo-image" alt="Logo">
        </a>
        <nav class="header-nav uppercase">
            <ul class="header-list list-reset flex">
                <li class="header-list-item">
                    <a href="#services" class="header-link">
                        services
                    </a>
                </li>
                <li class="header-list-item">
                    <a href="#memberships" class="header-link">
                        memberships
                    </a>
                </li>
                <li class="header-list-item">
                    <a href="#trainers" class="header-link">
                        trainers
                    </a>
                </li>
                <li class="header-list-item">
                    <a href="#contacts" class="header-link">
                        contacts
                    </a>
                </li>
            </ul>
        </nav>
        <?php if (!isset($_SESSION['user_id'])) {
            echo ' <div class="header-btns flex ">
            <a href="signup.php" class="header-btn button-link uppercase">
                        Sign up
            </a>
            <a href="signin.php" class="header-btn uppercase">
                        Sign in
            </a>
        </div>';
        }
        else {
            echo '<a href="account.php" class="header-btn button-link uppercase">
                        Account
                    </a>';
        }
        ?>
    </div>
</header>
<main class="main">
    <section class="fitness flex">
        <div class="container fitness-container">
            <div class="fitness-content">
                <h1 class="section-title fitness-title uppercase">
                    <span class="blue">fitness</span> is&nbsp;waiting for <span class="red">you</span>
                </h1>
                <p class="fitness-descr">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a aliquam ipsum. Donec eget ultrices
                    est. Integer convallis sapien sed tortor laoreet molestie. Nam tincidunt ullamcorper purus, a
                    aliquam odio eleifend et. Donec efficitur vehicula massa, quis molestie justo pharetra sit amet.
                    Vivamus non urna tempus, convallis ligula eget, porta augue. Sed erat est, tincidunt quis congue
                    sagittis, tristique sit amet mi. Pellentesque suscipit blandit congue. Vivamus odio nisi, ultrices
                    pretium sem in, ornare imperdiet turpis.
                    Cras pulvinar orci vitae mi iaculis imperdiet sed ut sem. Donec tincidunt pellentesque tellus, a
                    suscipit arcu mattis in. Mauris faucibus, velit id egestas.
                </p>
            </div>
        </div>
    </section>
    <section id="services" class="services section-offset flex">
        <div class="container">
            <h2 class="section-title services-title uppercase">
                Services
            </h2>
            <ul class="services-list list-reset flex">
                <li class="services-item">
                    <img src="./img/PersonalTraining2.jpg" alt="Personal training photo">
                    <h3 class="services-item-title uppercase">
                        <span class="blue">Personal</span> trainings
                    </h3>
                </li>
            </ul>
        </div>
    </section>
    <section id="memberships" class="memberships section-offset">
        <div class="container">
            <h2 class="section-title memberships-title uppercase">
                Memberships
            </h2>
            <ul class="memberships-list list-reset flex">
                <li class="memberships-item">
                    <h3 class="memberships-item-title uppercase blue">
                        Basic
                    </h3>
                    <p class="memberships-item-descr">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a aliquam ipsum. Donec eget
                        ultrices est. Integer convallis sapien sed tortor laoreet molestie. Nam tincidunt ullamcorper
                        purus, a aliquam odio eleifend et. Donec efficitur vehicula massa, quis molestie justo pharetra
                        sit amet. Vivamus non urna tempus, convallis ligula eget, porta augue.
                    </p>
                    <p class="memberships-item-price">
                        1500 CZK
                    </p>
                </li>
                <li class="memberships-item">
                    <h3 class="memberships-item-title uppercase red">
                        Pro
                    </h3>
                    <p class="memberships-item-descr">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a aliquam ipsum. Donec eget
                        ultrices est. Integer convallis sapien sed tortor laoreet molestie. Nam tincidunt ullamcorper
                        purus, a aliquam odio eleifend et. Donec efficitur vehicula massa, quis molestie justo pharetra
                        sit amet. Vivamus non urna tempus, convallis ligula eget, porta augue.
                    </p>
                    <p class="memberships-item-price">
                        2500 CZK
                    </p>
                </li>
            </ul>
        </div>
    </section>
    <section id="trainers" class="trainers section-offset">
        <div class="container">
            <h2 class="section-title trainers-title uppercase">
                Our trainers
            </h2>
                <ul class="trainers-list list-reset flex">
                    <li class="trainers-list-item">
                        <img src="./img/trainer-photo.jpg" alt="trainer photo" class="trainer-photo">
                        <p class="trainer-name">Daniil V.</p>
                        <p class="trainer-insta" >v.tren.d</p>
                    </li>
                    <li class="trainers-list-item">
                        <img src="./img/trainer-photo2.png" alt="trainer photo" class="trainer-photo">
                            <p class="trainer-name">Petr S.</p>
                            <p class="trainer-insta">example</p>
                    </li>
                </ul>
        </div>
    </section>
    <section id="contacts" class="contacts section-offset">
        <div class="container">
            <h2 class="section-title contacts-title uppercase">
                Contacts
            </h2>
            <div class="contacts-container flex">
                <address class="contacts-adress">
                    <ul class="contacts-list list-reset uppercase">
                        <li class="contacts-list-item">
                            <strong class="contacts-list-item-caption contacts-list-item-address">
                                Adress
                            </strong>
                            <a href="https://www.google.com/maps?ll=50.053127,14.428755&z=15&t=m&hl=en&gl=CZ&mapclient=embed&cid=3938131208882611730" class="contacts-list-item-link ">
                                NA LYSINE 772/12, 14700, PRAHA 4
                            </a>
                        </li>
                        <li class="contacts-list-item">
                            <strong class="contacts-list-item-caption contacts-list-item-telephone">
                                Telephone
                            </strong>
                            <a href="tel:+420777777777" class="contacts-list-item-link ">
                                +420 777 777 777
                            </a>
                        </li>
                        <li class="contacts-list-item">
                            <strong class="contacts-list-item-caption contacts-list-item-email">
                                Email
                            </strong>
                            <a href="mailto:example@example.com" class="contacts-list-item-link">
                                example@example.com
                            </a>
                        </li>
                    </ul>
                </address>
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d5122.154319387566!2d14.4196745!3d50.0661166!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470b9472183cb2ab%3A0x36a70d782853e612!2sPod-O-Gym!5e0!3m2!1sen!2scz!4v1699698255015!5m2!1sen!2scz" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
</main>
<footer>
    <div class="container footer-container">
        <a href="documentation.html">Documentation</a>
        <p>Copyright &copy; 2023 by Daniil Vakhov</p>
        <p>All rights reserved.</p>
    </div>
</footer>
</body>
</html>

