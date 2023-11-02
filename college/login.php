<?php

session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Stylesheets -->
    <!--   Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <!-- Icons -->
    <!-- Font-awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- System Tailwind stylesheet -->
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="./assets/css/login.css">

    <!-- End Stylesheets -->

    <!-- Javascript Scripts -->
    <!-- JS Plugins -->
    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Lodash Utility Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Date Range Picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Jquery Validation Plugin -->
    <script src=" https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js "></script>

    <!-- End JS Plugins -->
    <!-- System Script -->
    <script src="./scripts/core.js" defer></script>
    <script src="./scripts/login.js" defer></script>
    <!-- End JS Scripts -->



    <title>University College Login</title>


</head>

<body>
    <div class="w-full h-screen flex justify-center items-center">
        <div class="md:h-max sm:h-14 w-1/2 rounded-xl md:grid grid-cols-2 sm:block text-gray-600 ">
            <!-- Still Connected -->
            <div id="stillConnected" class="bg-accent md:rounded-l-2xl text-white pt-10 h-full relative z-20 translate-y-1/2 lg:translate-y-0  lg:translate-x-1/2 transition-transform duration-1000 delay-500">
                <h2 class="italic text-xl text-center">We're Still Connected</h2>
                <img id="graduateLogo" class="relative" src="../assets/graduate_logo.png" alt="">
            </div>

            <!-- Login Card -->
            <div id="loginPanel" class="p-3 rounded-r-2xl relative bg-white shadow-2xl z-10 -translate-y-1/2 lg:translate-y-0 lg:-translate-x-1/2 transition-transform  duration-1000 delay-500 ">
                <h1 class="text-2xl font-bold text-greyish_black text-center w-2/3 block mx-auto">Welcome back Admin!
                </h1>
                <div>
                    <form id="loginForm" class="mt-2 p-3 flex flex-col gap-1">
                        <p id="errorMsg" class="text-sm text-accent hidden"><span class="font-bold">Login failed:</span>
                            Incorrect
                            username/password</p>
                        <div class="form-control">
                            <label class='block'>Username</label>
                            <input name="username" class="add-focus input-text w-full logInput" placeholder="JaysonBatoonBulSU-CICT" type="text">
                            <span class=" input-msg">Hello</span>
                        </div>
                        <div class="form-control">
                            <label class="block">Password</label>
                            <div>
                                <input name="password" class="logInput w-full add-focus input-text" placeholder="JaysonBatoonBulSU-CICT" type="password" id="password">
                                <i class="far fa-eye -ml-8" id="togglePassword"></i>
                                <span class=" input-msg">Hello</span>
                            </div>
                            <a class="italic text-accent text-sm text-end py-2 hover:underline cursor-pointer">Forgot password?</a>
                        </div>
                        <button type="submit" class="rounded-md bg-accent text-white py-3 mt-3 hover:bg-darkAccent add-focus ">
                            Sign in
                        </button>
                    </form>

                    <!-- <p id="registerBtn" class="text-center absolute bottom-1 w-full cursor-pointer md:text-base sm:text-xs">Don't have an account? <span class="text-accent font-semibold">Register here</span></p> -->
                </div>
            </div>

            <!-- registration -->
            <div id="registrationPanel" class="w-full p-2 bg-white rounded-r-2xl hidden">
                <h1 class="text-2xl font-bold text-greyish_black text-center w-2/3 block mx-auto">Registration</h1>
                <form id="registerForm" class="mt-2 p-2 ">

                    <div id="personalInfoPanel" class="">
                        <!-- first name and last name -->
                        <div class="grid grid-cols-2 gap-1">
                            <div>
                                <label class="font-medium">First Name</label>
                                <input name="fname" class="personalInput border border-gray-400 rounded-md w-full p-2 outline-none" placeholder="e.g Jayson" type="text">
                            </div>
                            <div>
                                <label class="font-semibold">Last Name</label>
                                <input name="lname" class="personalInput border border-gray-400 rounded-md p-2 outline-none w-full" placeholder="e.g Batoon" type="text">
                            </div>
                        </div>

                        <!-- bday and age -->
                        <div class="grid grid-cols-2 gap-1">
                            <div>
                                <label class="font-semibold">Birthday</label>
                                <input name="bday" class="personalInput border border-gray-400 rounded-md p-2 outline-none w-full" type="date">
                            </div>
                            <div>
                                <label class="font-semibold">Age</label>
                                <input name="age" class="personalInput border border-gray-400 rounded-md p-2 outline-none w-full" value="0" type="number">
                            </div>
                        </div>

                        <!-- gender and contact number -->
                        <div class="grid grid-cols-2 gap-1">
                            <div>
                                <label class="font-semibold">Gender</label>
                                <div class="flex justify-evenly items-center mt-2 w-full">
                                    <input name="gender" type="radio" checked>Male
                                    <input name="gender" type="radio">Female
                                </div>
                            </div>
                            <div>
                                <label class="font-semibold">Contact Number</label>
                                <input name="contactNo" class="personalInput border border-gray-400 rounded-md p-2 outline-none block w-full" placeholder="e.g 09104905440" type="text">
                            </div>
                        </div>

                        <div>
                            <label class="font-semibold">Address</label>
                            <input name="address" class="personalInput border border-gray-400 rounded-md p-2 outline-none block w-full" type="text">
                        </div>

                        <div>
                            <label class="font-semibold">Email Address (Personal)</label>
                            <input name="personalEmail" class="personalInput border border-gray-400 rounded-md p-2 outline-none block w-full" type="text">
                        </div>

                        <div>
                            <label class="font-semibold">Email Address (BulSU)</label>
                            <input name="bulsuEmail" class="personalInput border border-gray-400 rounded-md p-2 outline-none block w-full" type="text">
                        </div>


                        <div class="flex justify-end mt-2 gap-2">
                            <button type="button" id="registerBtnBack">Go back</button>
                            <button type="button" id="registerBtnNext" class="py-2 px-4 rounded-md bg-accent hover:bg-darkAccent text-white">Next</button>
                        </div>
                    </div>

                    <div id="userAccountPanel" class="hidden">
                        <div>
                            <label class="font-semibold">Username</label>
                            <input id="usernameField" name="username" class="border border-gray-400 rounded-md p-2 outline-none block w-full" type="text">
                            <p id="usernameWarning" class="text-xs text-red-400 italic hidden">Username is already existing. Try another username</p>
                        </div>

                        <div>
                            <label class="font-semibold">Password</label>
                            <input id="password" name="password" class="password border border-gray-400 rounded-md p-2 outline-none block w-full" type="password">
                            <p>Password must contain the following: </p>
                            <div class="text-xs flex flex-col text-red-400">
                                <span id="addLowerCase">A lower case letter</span>
                                <span id="addUpperCase">A capital(uppercase) letter</span>
                                <span id="addNumber">A number</span>
                                <span id="minChar">Minimum 8 characters</span>
                            </div>
                        </div>

                        <div>
                            <label class="font-semibold">Confirm Password</label>
                            <input id="confirmpassword" class="password border border-gray-400 rounded-md p-2 outline-none block w-full" type="password">
                            <p id="passwordWarning" class="text-xs text-red-400 italic hidden">Password does not matched</p>
                        </div>


                        <div class="flex justify-end mt-2 gap-2">
                            <button type="button" id="backToPersonInfo">Go back</button>
                            <button type="submit" class="p-2 rounded-md bg-accent hover:bg-darkAccent text-white">Register</button>
                        </div>
                    </div>
                </form>
            </div>


            <!-- modal promp -->
            <div id="promptMessage" class="modal fixed inset-0 h-full w-full  items-start justify-center 
                 text-grayish  top-0 left-0 hidden">
                <div class="modal-container w-1/3 bg-white rounded-lg p-3 mt-2">
                    <div class="modal-header py-5">
                        <p id="insertionMsg" class="text-greyish_black font-bold text-lg text-center w-1/2 mx-auto"></p>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer flex items-end flex-row-reverse px-3 mt-3">
                        <button id="goBack" class="bg-accent py-2 rounded px-5 text-white ms-3 hover:bg-darkAccent 
                        hover:font-semibold">
                            Go back
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script>
        $(document).ready(function() {

            // Toggles eye for password show toggle
            $('#togglePassword').click(function(e) {

                // toggle password type
                const type = $('#password').get(0).type === 'password' ? 'text' : 'password';
                $('#password').attr('type', type);
                $(this).toggleClass("fa-eye-slash");
                $(this).toggleClass("fa-eye");

            })
        })
    </script>
</body>

</html>