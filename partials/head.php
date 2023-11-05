<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?> </title>
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- jquery cdn -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <link href="css/main.css" rel="stylesheet" />
    <link href="style/homepage.css" rel="stylesheet" />
    <link rel="icon" href="./assets/bulsu_connect_img/bulsu_connect_icon.png" type="image/x-icon">
</head>
<!-- TODO Make it responsive -->

<body class="relative">
    <header id="navbar" class="daisy-navbar bg-base-100 fixed top-0 inset-x-0 transition-all ease-in-out z-30 ">
        <div class="daisy-navbar-start">
            <div class="daisy-dropdown">
                <label tabindex="0" class="daisy-btn daisy-btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </label>
                <ul tabindex="0" class="daisy-menu daisy-menu-sm daisy-dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#events">Events</a></li>
                    <li><a href="#community">Community</a></li>
                    <li><a href="#donation">Donation</a></li>
                    <li><a href="#calendar">Calendar</a></li>

                    <li><a href="alumni.php">Alumni of the Year</a></li>
                </ul>
            </div>

            <a class="daisy-btn daisy-btn-ghost normal-case text-xl" href="index.php">
                <img class="w-20 h-full" src="./assets/bulsu_connect_img/bulsu_connect_logo.png" alt="Bulcan State University Logo" />

            </a>
        </div>
        <div class="daisy-navbar-center hidden lg:flex">
            <ul class="daisy-menu daisy-menu-horizontal px-1 [&>*:hover]:font-semibold [&>*:hover]:font-accent">
                <li><a href="#home">Home</a></li>
                <li><a href="#events">Events</a></li>
                <li><a href="#community">Community</a></li>
                <li><a href="#donation">Donation</a></li>
                <li><a href="#calendar">Calendar</a></li>

                <li><a href="alumni.php">Alumni of the Year</a></li>
                </details>
                </li>
            </ul>
        </div>
        <div class="daisy-navbar-end">
            <a class="daisy-btn">Sign In</a>
        </div>
    </header>
    <!-- header -->
    <!-- <header class="bg-white flex justify-between items-center px-12 py-6">
        <img class="w-20 h-full" src="./assets/bulsu-logo.png" alt="Bulcan State University Logo" />
        TODO Add dropdown on mobile
    <nav>
        <div class="dropdown">
            <ul class="flex gap-4 text-slate-500 font-bold">
                <li><a href="./index.php" href="">Home</a></li>
                <li><a href="./donation.php">Donation</a></li>
                <li><a href="./alumni.php">Alumni of the Year</a></li>
            </ul>
        </div>
    </nav>
    </header> -->
    <script src="./js/header-link.js"></script>