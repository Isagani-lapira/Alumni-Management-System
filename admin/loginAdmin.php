<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <title>University Admin Login</title>

    <style>
        body {
            background-image: url('../assets/bg.jpg');
            background-color: rgba(0, 0, 0, 0.5);
            /* Adjust the alpha value between 0 and 1 for desired opacity */
            background-blend-mode: multiply;
            background-size: cover;
        }
    </style>
</head>

<body>
    <div class="w-full h-screen flex justify-center items-center">
        <div class="md:h-max sm:h-14 w-1/2 rounded-xle md:grid grid-cols-2 sm:block text-gray-600">
            <div class="bg-accent md:rounded-l-2xl text-white pt-10 h-full relative">
                <h2 class="Lobster text-xl text-center">"We Still Connected"</h2>
                <img id="graduateLogo" class="relative" src="../assets/graduate_logo.png" alt=""> <!--make it relative-->
            </div>

            <!-- login -->
            <div id="loginPanel" class="p-3 rounded-r-2xl relative bg-white">
                <h1 class="text-2xl font-bold text-greyish_black text-center w-2/3 block mx-auto">Welcome back Admin!
                </h1>
                <div>
                    <form id="loginForm" class="mt-2 p-3 flex flex-col gap-1">
                        <p id="errorMsg" class="text-sm text-accent hidden"><span class="font-bold">login Failed:</span>
                            Incorrect
                            username/password</p>
                        <label>Username</label>
                        <input name="username" class="logInput border border-gray-400 rounded-md p-2 outline-none" placeholder="e.g patrickPron625" type="text">
                        <label>Password</label>
                        <div class="pass_details border border-gray-400 rounded-md p-2 flex items-center">
                            <input name="password" class="logInput flex-1 outline-none" placeholder="e.g patrickPron625" type="password">
                            <span class="fa-regular fa-eye-slash cursor-pointer" style="color: #969696;"></span>
                        </div>
                        <p class="italic text-accent text-sm text-end">Forgot password?</p>
                        <button type="submit" class="rounded-md bg-accent text-white py-3 mt-3 hover:bg-darkAccent">
                            Sign in
                        </button>
                    </form>

                    <p id="registerBtn" class="text-center absolute bottom-1 w-full cursor-pointer md:text-base sm:text-xs">Don't have an account? <span class="text-accent font-semibold">Register here</span></p>
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
                            <input id="usernameField" class="border border-gray-400 rounded-md p-2 outline-none block w-full" type="text">
                            <p id="usernameWarning" class="text-xs text-red-400 italic hidden">Username is already existing. Try another username</p>
                        </div>

                        <div>
                            <label class="font-semibold">Password</label>
                            <input id="password" class="password border border-gray-400 rounded-md p-2 outline-none block w-full" type="password">
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


        </div>
    </div>

    <script src="../js/login.js"></script>
</body>

</html>