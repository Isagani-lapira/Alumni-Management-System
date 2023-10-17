<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="../css/main.css" rel="stylesheet" />
    <link rel="stylesheet" href="../style/student-alumni.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <link rel="icon" href="../assets/bulsu_connect_img/bulsu_connect_icon.png" type="image/x-icon">
</head>

<body class="h-screen">
    <div class="h-full p-5 relative">
        <nav class="flex gap-2 items-center">
            <a href="../student-alumni/login.php">
                <img src="../assets/bulsu_connect_img/bulsu_connect_logo.png" alt="Logo" class=" w-32 h-16" />
            </a>
        </nav>

        <!-- selection -->
        <div class="selectionStatus" style="height: 90%;">
            <h2 class="text-center text-lg md:text-4xl font-bold py-3 text-greyish_black">PLEASE SPECIFY YOUR STATUS
            </h2>
            <!-- selection -->
            <div class="flex flex-col md:flex-row items-center justify-center gap-2 h-1/2">
                <div id="alumniStatus" class="p-5 w-1/3 h-4/5 bg-gray-300 center-shadow cursor-pointer text-greyish_black hover:bg-accent hover:text-white rounded-lg flex items-center justify-center">
                    <span class="font-bold text-sm md:text-xl">ALUMNI</span>
                </div>
                <div id="studentStatus" class="p-5 w-1/3 h-4/5 cursor-pointer bg-gray-300 center-shadow text-greyish_black hover:bg-accent hover:text-white rounded-lg flex items-center justify-center">
                    <span class="font-bold text-sm md:text-xl">STUDENT</span>
                </div>
            </div>

            <a href="../student-alumni/login.php" class="w-full text-center">
                <p class="text-gray-500">I have an account <span class="text-blue-400 hover:text-blue-500 font-bold text-center">Login</span></p>
            </a>
        </div>

        <!-- fields for alumni -->
        <form id="alumniForm" class="flex fieldFormReg hidden flex-col gap-2 mx-auto w-1/2 p-3" style="height: 90%;">
            <h3 class="font-black text-2xl text-greyish_black text-center py-3">ALUMNI ACCOUNT</h3>
            <!-- personal information  -->
            <div class="personalInfo">
                <!-- name -->
                <div class="flex gap-2">
                    <div class="w-1/2 flex flex-col">
                        <label for="fname">First name</label>
                        <input type="text" id="fname" name="fname" class="p-3 rounded-lg border border-gray-400 requiredAlumni" placeholder="ex: Juan">
                    </div>

                    <div class="w-1/2 flex flex-col">
                        <label for="lname">Last name</label>
                        <input type="text" id="lname" name="lname" class="p-3 rounded-lg border border-gray-400 requiredAlumni" placeholder="ex: Dela Cruz">
                    </div>
                </div>

                <!-- contact and student no -->
                <div class="flex gap-2">
                    <div class="w-1/2 flex flex-col">
                        <label for="contactNo">Contact Number</label>
                        <input type="text" id="contactNo" name="contactNo" class="p-3 rounded-lg border border-gray-400 requiredAlumni" placeholder="ex: 09938190220">
                    </div>

                    <div class="w-1/2 flex flex-col">
                        <label for="studNo">Student No.</label>
                        <input type="text" id="studNo" name="studNo" class="p-3 rounded-lg border border-gray-400 requiredAlumni" placeholder="ex: 2015103299">
                    </div>
                </div>

                <!-- birthday and gender -->
                <div class="flex gap-2">
                    <div class="w-1/2 flex flex-col">
                        <label for="bday">Birthday</label>
                        <div class="p-1 rounded-lg border border-gray-400 gap-2">
                            <input type="date" id="bday" name="bday" class="bdayInput w-full requiredAlumni">
                        </div>
                    </div>

                    <div class="w-1/2 flex flex-col">
                        <label for="male">Gender</label>
                        <div class="p-3 rounded-lg border border-gray-400 flex justify-center gap-2">
                            <input type="radio" id="male" name="gender" checked>
                            <label for="male">Male</label>

                            <input type="radio" id="female" name="gender">
                            <label for="female">Female</label>
                        </div>

                    </div>
                </div>

                <!-- employment status -->
                <div class="flex flex-col gap-2">
                    <label for="empStatus">Employment Status</label>
                    <select name="empStatus" id="empStatus" class="p-3 rounded-lg border border-gray-400 requiredAlumni">
                        <option value="" selected>Your employment status</option>
                        <option value="Employed">Employed</option>
                        <option value="Unemployed">Unemployed</option>
                        <option value="Self-employed">Self-employed</option>
                        <option value="Retired">Retired</option>
                    </select>
                </div>
                <!-- address -->
                <div class="flex flex-col gap-2">
                    <label for="address">Address</label>
                    <input id="address" name="address" class="p-3 rounded-lg border border-gray-400 flex justify-center gap-2 requiredAlumni" placeholder="ex: 56 Santa Monica Bulacan">
                </div>

                <!-- email personal -->
                <div class="flex flex-col gap-2">
                    <label for="personalEmail">Email Address (Personal)</label>
                    <input id="personalEmail" name="personalEmail" class="p-3 rounded-lg border border-gray-400 flex justify-center gap-2 requiredAlumni" placeholder="ex: juandelacruz@gmail.com">
                </div>

                <!-- navigation -->
                <div class="flex w-full justify-end my-2 gap-2">
                    <button type="button" class="cancelBtnReg">Cancel</button>
                    <button type="button" id="nextAlumni" class="px-4 py-2 rounded-md text-white font-bold bg-blue-400 hover:bg-blue-500">Next</button>
                </div>

            </div>

            <!-- account information -->
            <div id="accountInfoAlumni" class="flex flex-col gap-2 hidden">
                <!-- college and batch -->
                <div class="flex gap-2">
                    <div class="w-1/2 flex flex-col">
                        <label for="college">Year</label>
                        <select name="college" id="college" class="p-3 rounded-lg border border-gray-400 requiredAlumni2">
                            <option value="" selected>Your college</option>
                            <?php
                            require_once '../PHP_process/connection.php';
                            $query = "SELECT * FROM `college`";
                            $result = mysqli_query($mysql_con, $query);
                            $rows = mysqli_num_rows($result);

                            if ($rows > 0) {
                                while ($data = mysqli_fetch_assoc($result)) {
                                    $colCode = $data['colCode'];
                                    $colName = $data['colname'];

                                    echo '<option value="' . $colCode . '">' . $colName . '</option>';
                                }
                            } else echo '<option>No college available</option>';
                            ?>
                        </select>
                    </div>

                    <div class="w-1/2 flex flex-col">
                        <label for="batch">Batch</label>
                        <select name="batch" id="batchAlumni" class="p-3 rounded-lg border border-gray-400 requiredAlumni2">
                            <option value="" selected>Year you graduated</option>
                        </select>
                    </div>
                </div>

                <!-- username -->
                <div>

                    <div class="flex flex-col gap-2">
                        <label for="username">Username</label>
                        <input class="p-3 rounded-lg border border-gray-400 flex justify-center gap-2 requiredAlumni2" name="username" id="username" placeholder="ex: juandelacruz03">
                    </div>
                </div>
                <span class="text-sm text-red-400 italic usernameMsg hidden">Username is already exist!</span>

                <!-- password -->
                <div>
                    <div class="flex flex-col gap-2">
                        <label for="password">Password</label>
                        <div class="flex justify-evenly items-center gap-2">
                            <input id="accountPass" type="password" class="p-3 flex-1 rounded-lg border border-gray-400 flex justify-center gap-2 requiredAlumni2" id="password" name="password">
                            <iconify-icon id="alumniPassEye" class="cursor-pointer text-gray-500 hover:text-accent" icon="bi:eye-fill" width="18" height="18"></iconify-icon>
                        </div>

                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-between italic">
                    <span class="text-xs">Note: Password must contain both upper and lowercase and special character</span>
                    <span class="text-red-500 font-bold text-sm passwordStatus">Weak Password</span>
                </div>

                <!-- confirm password -->
                <div>
                    <div class="flex flex-col gap-2 w-full">
                        <label for="confirmPass">Confirm Password</label>
                        <div class="flex justify-evenly items-center gap-2">
                            <input id="confirmPass" type="password" class="p-3 flex-1 rounded-lg border border-gray-400 flex justify-center gap-2 requiredAlumni2" id="password" name="password">
                            <iconify-icon id="alumniConfirmPassEye" class="cursor-pointer text-gray-500 hover:text-accent" icon="bi:eye-fill" width="18" height="18"></iconify-icon>
                        </div>

                    </div>
                </div>

                <p class="text-sm italic text-red-400 errorPassNotMatch hidden">Password did not match</p>
                <div class="flex gap-2">
                    <div class="flex justify-end w-full gap-2">
                        <button id="backAlumni" type="button" class="text-gray-400 hover:text-gray-500">Back</button>
                        <button type="submit" id="registerAlumni" class="px-3 py-2 rounded-md text-white font-bold bg-green-400 hover:bg-green-500">Register</button>
                    </div>
                </div>
            </div>

        </form>

        <!-- fields for alumni -->
        <form id="studentForm" class="flex fieldFormReg hidden flex-col gap-2 mx-auto w-1/2 p-3" style="height: 90%;">
            <h3 class="font-black text-2xl text-greyish_black text-center py-3">STUDENT ACCOUNT</h3>
            <!-- personal information  -->
            <div class="personalInfo">
                <!-- name -->
                <div class="flex gap-2">
                    <div class="w-1/2 flex flex-col">
                        <label for="studFname">First name</label>
                        <input type="text" id="studFname" name="fname" class="p-3 rounded-lg border border-gray-400 requiredStudenField" placeholder="ex: Juan">
                    </div>

                    <div class="w-1/2 flex flex-col">
                        <label for="studlname">Last name</label>
                        <input type="text" id="studlname" name="lname" class="p-3 rounded-lg border border-gray-400 requiredStudenField" placeholder="ex: Dela Cruz">
                    </div>
                </div>

                <!-- contact and student no -->
                <div class="flex gap-2">
                    <div class="w-1/2 flex flex-col">
                        <label for="studcontactNo">Contact Number</label>
                        <input type="text" id="studcontactNo" name="contactNo" class="p-3 rounded-lg border border-gray-400 requiredStudenField" placeholder="ex: 09193846220">
                    </div>

                    <div class="w-1/2 flex flex-col">
                        <label for="studstudNo">Student No.</label>
                        <input type="text" id="studstudNo" name="studNo" class="p-3 rounded-lg border border-gray-400 requiredStudenField" placeholder="ex: 2020931822">
                    </div>
                </div>

                <!-- birthday and gender -->
                <div class="flex gap-2">
                    <div class="w-1/2 flex flex-col">
                        <label for="studBday">Birthday</label>
                        <div class="p-1 rounded-lg border border-gray-400 gap-2">
                            <input type="date" id="studBday" name="bday" class="bdayInput w-full requiredStudenField">
                        </div>
                    </div>

                    <div class="w-1/2 flex flex-col">
                        <label for="male">Gender</label>
                        <div class="p-3 rounded-lg border border-gray-400 flex justify-center gap-2">
                            <input type="radio" id="studMale" name="gender" checked>
                            <label for="studMale">Male</label>

                            <input type="radio" id="studFemale" name="gender">
                            <label for="studFemale">Female</label>
                        </div>

                    </div>
                </div>

                <!-- address -->
                <div class="flex flex-col gap-2">
                    <label for="studAddress">Address</label>
                    <input id="studAddress" name="address" class="p-3 rounded-lg border border-gray-400 flex justify-center gap-2 requiredStudenField" placeholder="ex: 56 Santa Monica Bulacan">
                </div>

                <!-- email personal -->
                <div class="flex flex-col gap-2">
                    <label for="studPersonalEmail">Email Address (Personal)</label>
                    <input id="studPersonalEmail" name="personalEmail" class="p-3 rounded-lg border border-gray-400 flex justify-center gap-2 requiredStudenField" placeholder="ex: juandelacruz@gmail.com">
                </div>

                <!-- email personal -->
                <div class="flex flex-col gap-2">
                    <label for="studbulsuEmail">Email Address (BulSU)</label>
                    <input id="studbulsuEmail" name="bulsuEmail" class="p-3 rounded-lg border border-gray-400 flex justify-center gap-2 requiredStudenField" placeholder="ex: juandelacruz@bulsu.edu.ph">
                </div>
                <p id="bulsuEmailError" class="text-sm my-1 text-red-400 italic hidden">Error: Your BulSU email is invalid</p>

                <!-- navigation -->
                <div class="flex w-full justify-end my-2 gap-2">
                    <button type="button" class="cancelBtnReg">Cancel</button>
                    <button type="button" id="nextStudent" class="px-4 py-2 rounded-md text-white font-bold bg-blue-400 hover:bg-blue-500">Next</button>
                </div>

            </div>

            <!-- account information -->
            <div id="accountInfoStudent" class="flex flex-col gap-2 hidden">
                <!-- college and batch -->
                <div class="flex gap-2">
                    <div class="w-1/2 flex flex-col">
                        <label for="studCollege">Year</label>
                        <select name="college" id="studCollege" class="p-3 rounded-lg border border-gray-400 requiredStudent2">
                            <option value="" selected>Your college</option>
                            <?php
                            require_once '../PHP_process/connection.php';
                            $query = "SELECT * FROM `college`";
                            $result = mysqli_query($mysql_con, $query);
                            $rows = mysqli_num_rows($result);

                            if ($rows > 0) {
                                while ($data = mysqli_fetch_assoc($result)) {
                                    $colCode = $data['colCode'];
                                    $colName = $data['colname'];

                                    echo '<option value="' . $colCode . '">' . $colName . '</option>';
                                }
                            } else echo '<option>No college available</option>';
                            ?>
                        </select>
                    </div>

                    <!-- current year status -->
                    <div class="w-1/2 flex flex-col">
                        <label for="currentYr">Current Year</label>
                        <select name="batch" id="currentYr" class="p-3 rounded-lg border border-gray-400">
                            <option value="" selected>Current Year</option>
                            <option value=1>1st Year</option>
                            <option value=2>2nd Year</option>
                            <option value=3>3rd Year</option>
                            <option value=4>4th Year</option>
                        </select>
                    </div>
                </div>

                <!-- username -->
                <div>
                    <div class="flex flex-col gap-2">
                        <label for="studUsername">Username</label>
                        <input class="p-3 rounded-lg border border-gray-400 flex justify-center gap-2 requiredStudent2" name="username" id="studUsername" placeholder="ex: juandelacruz">
                    </div>
                </div>
                <span class="text-sm text-red-400 italic usernameMsg hidden">Username is already exist!</span>

                <!-- password -->
                <div>
                    <div class="flex flex-col gap-2">
                        <label for="studAccountPass">Password</label>
                        <div class="flex justify-evenly items-center gap-2">
                            <input id="studAccountPass" type="password" class="p-3 flex-1 rounded-lg border border-gray-400 flex justify-center gap-2" id="password" name="password" placeholder="********">
                            <iconify-icon id="studentPassEye" class="cursor-pointer text-gray-500 hover:text-accent" icon="bi:eye-fill" width="18" height="18"></iconify-icon>
                        </div>

                    </div>
                </div>
                <div class="flex flex-col md:flex-row justify-between italic">
                    <span class="text-xs">Note: Password must contain both upper and lowercase and special character</span>
                    <span class="text-red-500 font-bold text-sm passwordStatus">Weak Password</span>
                </div>


                <!-- confirm password -->
                <div>
                    <div class="flex flex-col gap-2">
                        <label for="studConfirmPass">Confirm Password</label>
                        <div class="flex justify-evenly items-center gap-2">
                            <input id="studConfirmPass" type="password" class="p-3 flex-1 rounded-lg border border-gray-400 flex justify-center gap-2" id="password" name="password">
                            <iconify-icon id="studentConfirmPassEye" class="cursor-pointer text-gray-500 hover:text-accent" icon="bi:eye-fill" width="18" height="18"></iconify-icon>
                        </div>
                    </div>
                </div>

                <p class="text-sm italic text-red-400 errorPassNotMatch hidden">Password did not match</p>
                <div class="flex gap-2">
                    <div class="flex justify-end w-full gap-2">
                        <button id="backStudent" type="button" class="text-gray-400 hover:text-gray-500">Back</button>
                        <button type="submit" id="registerStudent" class="px-3 py-2 rounded-md text-white font-bold bg-green-400 hover:bg-green-500">Register</button>
                    </div>
                </div>
            </div>

        </form>

        <!-- success prompt -->
        <div id="successJobModal" class="fixed inset-0 z-50 flex items-center justify-center p-3 bg-black bg-opacity-25 hidden">
            <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2">
                <svg class="block mx-auto" width="115px" height="115px" viewBox="0 0 133 133" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g id="check-group" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <circle id="filled-circle" fill="#47CF73" cx="66.5" cy="66.5" r="54.5" />
                        <circle id="white-circle" fill="#FFFFFF" cx="66.5" cy="66.5" r="55.5" />
                        <circle id="outline" stroke="#47CF73" stroke-width="4" cx="66.5" cy="66.5" r="54.5" />
                        <polyline id="check" stroke="#FFFFFF" stroke-width="5.5" points="41 70 56 85 92 49" />
                    </g>
                </svg>
                <h1 class=" text-3xl font-bold text-green-500 text-center">Thank you</h1>
                <p class=" text-lg text-center text-gray-500">Your account is now ready, login now!</p>
                <a href="../student-alumni/login.php" class="font-bold m-3 px-5 py-3 rounded-md w-4/5 text-center hover:bg-blue-500 block mx-auto bg-blue-400 text-white">Go back</a>

            </div>
        </div>

    </div>


    <script src="../student-alumni/js/login-register.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</body>

</html>