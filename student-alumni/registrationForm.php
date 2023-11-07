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
    <!--   Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
</head>

<body class="">
    <div class="bg-gray-100 min-h-screen flex flex-col justify-center items-center">
        <header class="flex gap-2 items-center p-4 w-full text-center">
            <a href="../student-alumni/login.php">
                <img src="../assets/bulsu_connect_img/bulsu_connect_logo.png" alt="Logo" class=" w-32 h-16" />
            </a>
        </header>

        <!-- selection -->
        <div class="flex px-12 p-4 w-full text-center flex-1  flex-col justify-center items-center shadow-lg selectionStatus">
            <div class="bg-white border px-14 py-4  rounded w-6/12  h-1/3 max-h-[80%]  flex flex-col justify-between ">
                <h2 class="text-left text-lg md:text-xl font-bold  py-10 text-greyish_black tracking-wide">PLEASE SPECIFY YOUR STATUS:
                </h2>
                <!-- selection -->
                <div class="flex flex-col md:flex-row  justify-between [&>*]:flex-1 gap-6  py-4 h-44">
                    <label for="terms-modal" id="alumniStatus" class="p-5 bg-gray-300 center-shadow cursor-pointer text-greyish_black hover:bg-accent transition duration-300 delay-200 ease-in-out hover:text-white rounded-lg flex items-center justify-center">
                        <span class="font-bold text-sm md:text-xl">ALUMNI</span>
                    </label>
                    <label for="terms-modal" id="studentStatus" class="p-5 cursor-pointer bg-gray-300 center-shadow text-greyish_black hover:bg-accent transition duration-300 delay-200 ease-in-out hover:text-white rounded-lg flex items-center justify-center">
                        <span class="font-bold text-sm md:text-xl">STUDENT</span>
                    </label>
                </div>
                <p class="inline-block text-left">Already have an account?
                    <a href="../student-alumni/login.php" class=" daisy-link daisy-link-hover daisy-link-primary text-accent">
                        <span class="">Login Here</span>
                    </a>
                </p>
            </div>
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
                        <span class="studExistingMsg italic text-red-400 text-sm hidden">This student number is already in used</span>
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
                            <input type="radio" id="male" name="gender" value="male" checked>
                            <label for="male">Male</label>

                            <input type="radio" id="female" name="gender" value="female">
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
                    <span class="emailExistingMsg italic text-red-400 text-sm hidden">This email is already used</span>
                    <span class="emailInvalidMsg italic text-red-400 text-sm hidden">This email is invalid</span>
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

                <!-- course -->
                <div class="w-full flex flex-col">
                    <label for="courses">Course</label>
                    <select name="course" id="courses" class="p-3 rounded-lg border border-gray-400 requiredAlumni2">
                        <option value="" selected>Your course</option>
                    </select>
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

        <!-- fields for student -->
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
                        <span class="studExistingMsg italic text-red-400 text-sm hidden">This student number is already in used</span>
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
                    <span class="emailExistingMsg italic text-red-400 text-sm hidden">This email is already used</span>
                    <span class="emailInvalidMsg italic text-red-400 text-sm hidden">This email is invalid</span>
                </div>

                <!-- email bulsu -->
                <div class="flex flex-col gap-2">
                    <label for="studbulsuEmail">Email Address (BulSU)</label>
                    <input id="studbulsuEmail" name="bulsuEmail" class="p-3 rounded-lg border border-gray-400 flex justify-center gap-2 requiredStudenField" placeholder="ex: juandelacruz@bulsu.edu.ph">
                    <span class="emailExistingMsgBulsu italic text-red-400 text-sm hidden">This email is already used</span>
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
                        <label for="studCollege">College</label>
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

                <!-- course -->
                <div class="w-full flex flex-col">
                    <label for="courses">Course</label>
                    <select name="course" id="courseStudent" class="p-3 rounded-lg border border-gray-400 requiredStudent2">
                        <option value="" selected>Your course</option>
                    </select>
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
                            <input id="studAccountPass" type="password" class="p-3 flex-1 rounded-lg border border-gray-400 flex justify-center gap-2 requiredStudent2" id="password" name="password" placeholder="********">
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
                            <input id="studConfirmPass" type="password" class="p-3 flex-1 rounded-lg border border-gray-400 flex justify-center gap-2 requiredStudent2" id="password" name="password">
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


    <!-- Put this part before </body> tag -->
    <!-- terms and conditions  -->
    <input type="checkbox" id="terms-modal" class="daisy-modal-toggle" />
    <div class="daisy-modal">1
        <div class="daisy-modal-box  daisy-modal-bottom sm:daisy-modal-middle w-11/12 max-w-5xl">
            <div class="overflow-y-auto">
                <div class="flex ">
                    <h1 class="font-bold text-xl text-accent mt-2">Privacy Policy</h1>
                </div>
                <hr class="my-4">
                <div class="flex flex-col justify-center  px-10 ">
                    <!-- Introduction -->
                    <p class="text-justify">
                        Welcome to BulSU Connect, your Alumni Management System.
                        At Bulacan State University (BulSU), we are committed to protecting your privacy and ensuring the security of your personal information.
                        This Data Privacy Notice explains how we collect, use, and safeguard your data in accordance with the Data Privacy Act of 2012 (Republic Act No. 10173).
                        <br><br>
                        BulSU Connect will collect personal information that you provide in this MEMBERSHIP APPLICATION FORM solely for the purpose of authenticating
                        respondents within our alumni community. We may securely dispose or delete any personal information that is no longer necessary for these purposes.
                    </p>
                    <br>
                    <!-- How Your Information is Used -->
                    <h3 class="font-bold text-lg text-accent">How Your Information is Used:</h3>
                    <ul class="text-justify list-disc ml-4">
                        <li><b class="font-bold ">Authenticating Respondents:</b> We use your personal information to verify your identity within the BulSU alumni community.</li>
                        <li><b class="font-bold ">Communication:</b> We may use your contact details to send you relevant updates, newsletters, and event invitations related to BulSU alumni activities.</li>
                        <li><b class="font-bold ">Third-Party Sharing:</b> We may share your personal information with BulSU affiliates, business partners, service providers, and contractors or subcontractors for authentication and communication purposes. All shared information is protected by appropriate agreements to ensure your privacy.</li>
                    </ul>
                    <br>
                    <!-- Your Consent -->
                    <h3 class="font-bold text-lg text-accent">Your Consent:</h3>
                    <p class="text-justify">By submitting this form, you consent to the internal processing of your personal information by BulSU Connect. Any external sharing of your information beyond our community will require your explicit written consent.</p>
                    <br>
                    <!-- Your Rights as a Data Subject -->
                    <h3 class="font-bold text-lg text-accent">Your Rights as a Data Subject:</h3>
                    <ul class="text-justify list-disc ml-4">
                        <li><b class="font-bold ">Right to be Informed:</b> You have the right to know how your personal information will be processed.</li>
                        <li><b class="font-bold ">Right to Object:</b> You can object to the processing of your personal information for specific purposes.</li>
                        <li><b class="font-bold ">Right to Access:</b> You can request access to the personal information we hold about you.</li>
                        <li><b class="font-bold ">Right to Rectify:</b> You can request corrections to your personal information if it is inaccurate or incomplete.</li>
                        <li><b class="font-bold ">Right to Erasure or Blocking:</b> You can request the deletion or blocking of your personal information under certain circumstances.</li>
                        <li><b class="font-bold ">Right to Data Portability:</b> You can obtain and reuse your personal information for your own purposes.</li>
                        <li><b class="font-bold ">Right to File a Complaint:</b> If you believe your data protection rights have been violated, you have the right to file a complaint.</li>
                        <li><b class="font-bold ">Right to Compensation:</b> You have the right to claim compensation for damages caused by the violation of your data protection rights.</li>
                    </ul>
                    <br>
                    <!-- Contact Information -->
                    <h3 class="font-bold text-lg text-accent">Contact Information:</h3>
                    <p><b class="font-bold">Data Protection Officer: </b>Patrick Joseph Pronuevo</p>
                    <p> <b class="font-bold">Email:</b> <a href="mailto:patrickjoseph.pronuevo.a@bulsu.edu.ph">patrickjoseph.pronuevo.a@bulsu.edu.ph</a></p>
                    <p><b class="font-bold">Phone:</b> +639764127324</p>
                    <p><b class="font-bold">Website: </b><a href="https://eliamarg.slarenasitsolutions.com/Alumni-Management-System/student-alumni/lgin.php?fbclid=IwAR31O4RRbd1HKkGsWFedwL3z6VQ9Tb7dd1lfLJIfSc_3yHAvOhIyDxIsky0" class="text-accent">BulSU Connect</a></p>
                    <p><b class="font-bold">Date: </b>11/7/2023</p>
                    <br>
                    <p class="italic text-accent font-bold">Thank you for being a part of the BulSU alumni community. Your privacy matters to us.</p>

                </div>

                <div class="flex mt-8">
                    <h1 class="font-bold text-xl text-accent mt-2">Terms & Conditions</h1>
                </div>
                <hr class="my-4">


                <div class="flex flex-col gap-4 px-10 justify-center">
                    <div>
                        <p class="font-bold text-accent">1. Acceptance of Terms</p>
                        <p>
                            1.1 By registering on BulSU Connect, you agree to comply with these Terms and Conditions, our Privacy Policy, and any other rules or guidelines posted on the app.</p>
                    </div>
                    <div class="terms-text">
                        <p class="font-bold text-accent"> 2. Registration
                        </p>
                        <p>2.1 To use BulSU Connect, you must complete the registration process. You agree to provide accurate, current, and complete information during the registration process.</p>
                        <p>2.2 You are responsible for maintaining the confidentiality of your account credentials, including your username and password. You are also responsible for all activities that occur under your account.</p>

                        <p>2.3 You agree to immediately notify us of any unauthorized use of your account or any other breach of security.</p>
                    </div>





                    <div class="terms-text">

                        <p class="font-bold text-accent">3. User Content</p>

                        <p>
                            3.1 You are solely responsible for any content you post, share, or upload on BulSU Connect.
                        </p>

                        <p>
                            3.2 You agree not to post any content that is illegal, defamatory, abusive, obscene, offensive, or violates the rights of others.
                        </p>
                    </div>
                    <div class="terms-text">
                        <p class="font-bold text-accent">
                            4. App Usage
                        </p>
                        <p>
                            4.1 You agree to use BulSU Connect for lawful purposes only and in compliance with all applicable laws and regulations.</p>
                        <p>
                            4.2 You will not use the app for any unauthorized or illegal activities, including but not limited to hacking, spamming, or distributing malware.
                        </p>
                    </div>
                    <div class="terms-text">
                        <p class="font-bold text-accent">
                            5. Termination
                        </p>
                        <p>
                            5.1 We reserve the right to suspend or terminate your account at our discretion, without prior notice, if you violate these Terms and Conditions.
                        </p>
                    </div>
                    <div class="terms-text">
                        <p class="font-bold text-accent">
                            6. Privacy
                        </p>
                        <p>
                            6.1 Your use of BulSU Connect is also governed by our Privacy Policy, which outlines how we collect, use, and protect your personal information.
                        </p>
                    </div>
                    <div class="terms-text">
                        <p class="font-bold text-accent">
                            7. Changes to Terms and Conditions
                        </p>
                        <p>
                            7.1 We may update these Terms and Conditions from time to time. You will be notified of any material changes, and continued use of the app will be considered as your acceptance of the updated terms.
                        </p>
                    </div>
                    <div class="terms-text">
                        <p class="font-bold text-accent">
                            8. Disclaimer of Warranties
                        </p>
                        8.1 BulSU Connect is provided "as is" without any warranties. We do not guarantee that the app will be error-free or uninterrupted.
                        </p>
                    </div>
                    <div class="terms-text">
                        <p class="font-bold text-accent">
                            9. Limitation of Liability
                        </p>
                        <p>
                            9.1 We are not liable for any direct or indirect damages, including but not limited to loss of data, profits, or business opportunities, arising from your use of BulSU Connect.
                        </p>
                    </div>
                    <div class="terms-text">
                        <p class="font-bold text-accent">
                            10. Governing Law
                        <p>
                            10.1 These Terms and Conditions are governed by the laws of Philippine Constitution.
                        </p>
                    </div>
                </div>

            </div>

            <hr class="my-2">
            <div class="daisy-modal-action">


                <div class="flex flex-col  w-full gap-4 ">
                    <div class="daisy-form-control px-10 items-start">
                        <label for="privacyPolicyCheckbox" class="daisy-label cursor-pointer">
                            <input type="checkbox" class="daisy-checkbox daisy-checkbox-primary" id="privacyPolicyCheckbox">
                            <span class="daisy-label-text ml-3">I have read and understand all BulSU Connect's privacy policy and terms and conditions.</span> </label>

                    </div>
                    <div class="flex justify-end space-x-4 w-full px-10">
                        <!-- if there is a button in the form, it will close the modal -->
                        <form method="dialog">
                            <label for="terms-modal" class="daisy-btn daisy-btn-outline">CANCEL</label>
                        </form>
                        <button id="acceptButton" class="daisy-btn daisy-btn-primary" data-selected="" disabled>
                            I ACCEPT
                        </button>

                    </div>
                </div>


            </div>
        </div>
    </div>



    <script src="../student-alumni/js/login-register.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>



</body>

</html>