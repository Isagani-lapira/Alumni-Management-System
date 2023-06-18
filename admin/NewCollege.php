<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="../css/main.css" rel="stylesheet" />
    <link href="../style/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <script src="https://code.jquery.com/jquery-2.2.4.js"
        integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <title>Create New College</title>
</head>

<body>
    <div class="p-10 text-greyish_black w-screen">
        <div>
            <h1 class="text-4xl font-bold">CREATE NEW COLLEGE</h1>
            <p class="text-grayish">Here you can add a new College that will be available in the University</p>
            <img class="-top-32 -right-32 h-1/2 opacity-50 clip-right fixed logo-bulsu" src="../assets/bulsu-logo.png"
                alt="">
        </div>

        <div>
            <form id="collegeForm">
                <!-- creation of college content -->
                <div id="fillUpCol">
                    <div class="flex justify-center items-center rounded h-40 p-3 bulsu-logo hover:cursor-pointer w-40 mx-auto">
                        <label class="lblLogo block text-center text-lg font-semibold text-grayish"
                            for="collegeLogo">ADD
                            LOGO</label>
                        <input class="hidden" id="collegeLogo" name="collegeLogo" type="file">
                        <img id="imgAddLogo" class="h-full w-full hidden" src="" alt="">
                    </div>
                    <p id="errorExtMsg" class="text-sm text-center text-accent hidden">File extension not supported</p>
                    <button type="button" id="btnBrowse"
                        class="bg-postButton hover:bg-postHoverButton block mx-auto px-4 py-1 rounded-lg mt-2 text-white">Browse
                        file</button>


                    <div class="flex h-max mt-5">

                        <div class="w-full flex flex-col justify-center items-center">
                            <!-- College Name -->
                            <label class="font-bold block w-3/4 text-start text-greyish_black" for="">College
                                Name</label>
                            <input id="colName" name="colName" class="block p-2 border border-grayish w-3/4 outline-none rounded-lg mb-3"
                                type="text" placeholder="e.g College of Information in Communication Technology">

                            <!-- College Email -->
                            <div class="grid grid-cols-2 w-3/4 gap-2">
                                <div>
                                    <label class="font-bold text-greyish_black block w-3/4 text-start" for="">College
                                        Code</label>
                                    <input id="colCode" name="colCode" class="block p-2 border border-grayish w-full outline-none rounded-lg"
                                        type="text" placeholder="e.g CICT">
                                </div>

                                <div>
                                    <label class="font-bold block w-3/4 text-start text-greyish_black" for="">College
                                        Email
                                        Address</label>
                                    <input id="colEmail" name="colEmail" class="block p-2 border border-grayish w-full outline-none rounded-lg"
                                        type="text"
                                        placeholder="e.g College of Information in Communication Technology">
                                </div>
                            </div>


                            <!-- contact and website link -->
                            <div class="grid grid-cols-2 w-3/4 gap-2 mt-1">
                                <div>
                                    <label class="font-bold text-greyish_black block w-3/4 text-start" for="">Contact
                                        No.</label>
                                    <input id="colContact" name="colContact" class="block p-2 border border-grayish w-full outline-none rounded-lg"
                                        type="text" placeholder="e.g 09104905330">
                                </div>

                                <div>
                                    <label class="font-bold text-greyish_black block w-3/4 text-start" for="">Webiste
                                        link</label>
                                    <input id="colLink" name="colLink" class="block p-2 border border-grayish w-full outline-none rounded-lg"
                                        type="text" placeholder="e.g https://www.bulsu-cict.com/ ">
                                </div>
                            </div>


                            <!-- Coordinator info -->
                            <p
                                class=" border text-start border-b-greyish_black w-3/4 font-bold text-greyish_black py-3">
                                College
                                Alumni
                                Coordinator Information</p>

                            <div class="grid grid-cols-2 w-3/4 gap-2 my-3">
                                <div>
                                    <label class="font-bold block w-3/4 text-start text-greyish_black" for="">First
                                        Name</label>
                                    <input id="coorFN" name="coorFN" class="block p-2 border border-grayish w-full outline-none rounded-lg"
                                        type="text" placeholder="e.g Jayson">
                                </div>

                                <div>
                                    <label class="font-bold text-greyish_black block w-3/4 text-start" for="">Last
                                        Name</label>
                                    <input id="coorLN" name="coordLN" class="block p-2 border border-grayish w-full outline-none rounded-lg"
                                        type="text" placeholder="e.g Batoon ">
                                </div>
                            </div>

                        </div>

                        <div class="w-full flex flex-col items-center">

                            <label class="font-bold text-greyish_black block w-3/4 text-start" for="">Email Address
                                (Personal)</label>
                            <input id="coorEmPersonal" name="coorEmPersonal" class="block p-2 border border-grayish w-3/4 outline-none rounded-lg mb-3"
                                type="text" placeholder="e.g jaysonbatoon@gmail.com">

                            <label class="font-bold text-greyish_black block w-3/4 text-start" for="">Email Address
                                (BulSU)</label>
                            <input id="coorEmBulsu" name="coorEmBulsu" class="block p-2 border border-grayish w-3/4 outline-none rounded-lg" type="text"
                                placeholder="e.g jaysonbatoon@bulsu.edu.ph">

                            <!-- contact no and address -->
                            <div class="grid grid-cols-2 w-3/4 gap-2 mt-1">
                                <div>
                                    <label class="font-bold text-greyish_black block w-3/4 text-start" for="">Contact
                                        No.</label>
                                    <input id="coorEmContact" name="coorEmContact" class="block p-2 border border-grayish w-full outline-none rounded-lg"
                                        type="text" placeholder="e.g 09104905330">
                                </div>

                                <div>
                                    <label class="font-bold text-greyish_black block w-3/4 text-start"
                                        for="">Address</label>
                                    <input name="coorEmAddress" id="coorEmAddress" class="block p-2 border border-grayish w-full outline-none rounded-lg"
                                        type="text" placeholder="e.g https://www.bulsu-cict.com/ ">
                                </div>
                            </div>

                            <!-- bday and gender -->
                            <div class="grid grid-cols-2 w-3/4 gap-2 mt-3">
                                <div>
                                    <label class="font-bold block w-3/4 text-start text-greyish_black"
                                        for="">Birthday</label>
                                    <input id="coorEmBday" id="coorEmBday" class="block p-2 border border-grayish w-full outline-none rounded-lg"
                                        type="date" placeholder="e.g mm/dd/yyyy">
                                </div>

                                <div>
                                    <label class="font-bold block w-3/4 text-start text-greyish_black"
                                        for="">Gender</label>
                                    <div class="flex gap-2 items-center p-2">
                                        <input name="gender" type="radio" value="male" checked />
                                        <label for="">Male</label>
                                        <input name="gender" type="radio" value="female">
                                        <label for="">Female</label>
                                    </div>

                                </div>
                            </div>

                            <div class="rounded-lg mt-3 w-3/4 flex justify-end gap-2 ">
                                <button type="button" id="btnCancelToCollege" class=" text-lg hover:font-semibold">
                                    Cancel
                                </button>

                                <button type="button" id="btntoReview"
                                    class="text-white bg-accent rounded-lg px-5 py-2 text-lg hover:bg-darkAccent">
                                    Review
                                </button>
                            </div>

                        </div>


                    </div>
                </div>

                <!-- review content -->
                <div id="reviewCol" class="hidden">
                    <!-- logo -->
                    <div
                        class="flex justify-center items-center rounded h-40 p-3 bulsu-logo hover:cursor-pointer w-40 mx-auto">
                        <label class="chosenLogo block text-center text-lg font-semibold text-grayish"></label>
                        <img id="chosenLogo" class="h-full w-full hidden" src="" alt="">
                    </div>
                    <div class="flex h-max mt-5">
                        <!-- left side -->
                        <div class="w-1/2  text-sm">
                            <p class="font-bold text-lg text-accentBlue">College details</p>

                            <div class="flex mt-2">
                                <span class="font-medium">College Name:</span>
                                <p class="answer ps-3">College of Information and Communication Technology</p>
                            </div>

                            <div class="grid grid-cols-2 w-3/4 gap-2 mt-1">
                                <div class="flex mt-2">
                                    <span class="font-medium">College Code:</span>
                                    <p class=" answer ps-3">CICT</p>
                                </div>

                                <div class="flex mt-2">
                                    <span class="font-medium">College Email:</span>
                                    <p class="answer ps-3">cictbulsu@gmail.com</p>
                                </div>

                            </div>

                            <div class="grid grid-cols-2 w-3/4 gap-2 mt-1">
                                <div class="flex mt-2">
                                    <span class="font-medium">College No:</span>
                                    <p class="answer ps-3">09104905440</p>
                                </div>

                                <div class="flex mt-2">
                                    <span class="font-medium">Website link:</span>
                                    <p class="answer ps-3">https://bulsucict.com/</p>
                                </div>

                            </div>

                            <p class="font-bold text-lg mt-2 text-accentBlue">College Alumni Coordinator</p>
                            <div class="flex mt-2">
                                <span class="font-medium">Fullname:</span>
                                <p class="answer ps-3">Jayson Batoon</p>
                            </div>

                            <div class="flex mt-2">
                                <span class="font-medium">Personal email:</span>
                                <p class="answer ps-3">jaysonbatoon@gmail.com</p>
                            </div>

                            <div class="flex mt-2">
                                <span class="font-medium">Bulsu email:</span>
                                <p class="answer ps-3">jaysonbatoon@bulsu.edu.ph</p>
                            </div>

                            <div class="grid grid-cols-2 w-3/4 gap-2 mt-1">
                                <div class="flex mt-2">
                                    <span class="font-medium">Contact No:</span>
                                    <p class="answer ps-3">093341930012</p>
                                </div>

                                <div class="flex mt-2">
                                    <span class="font-medium">Address:</span>
                                    <p class="answer ps-3">Santa Rosa, Malolos Bulacan</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 w-3/4 gap-2 mt-1">
                                <div class="flex mt-2">
                                    <span class="font-medium">Birthday:</span>
                                    <p class="answer ps-3">01/22/1998</p>
                                </div>

                                <div class="flex mt-2">
                                    <span class="font-medium">Gender:</span>
                                    <p class="answer ps-3">Male</p>
                                </div>
                            </div>

                        </div>

                        <!-- right side -->
                        <div class="w-1/2  text-sm">
                            <p class="font-bold text-lg text-accentBlue">College coordinator account</p>
                            <!-- username -->
                            <div class="flex mt-2 items-center">
                                <span class="font-medium">Username:</span>
                                <p class="px-3">jaysonbatoonBULSU-CICT</p>
                                <span class="fa-solid fa-circle-question" style="color: #6f7071;"
                                    title="Autogenerated by the system"></span>
                            </div>

                            <!-- password -->
                            <div class="flex mt-2 items-center">
                                <span class="font-medium">Password:</span>
                                <p class="px-3">jaysonbatoonBULSU-CICT</p>
                                <span class="fa-solid fa-circle-question" style="color: #6f7071;"
                                    title="Autogenerated by the system"></span>
                            </div>

                        </div>

                    </div>

                    <button class="py-3 w-2/5 block mx-auto bg-accent rounded-lg text-white mt-10 font-semibold">Create
                        College</button>
                    <button type="button" id="btnBackFill"
                        class=" text-lg hover:font-semibold py-3 w-2/5 block mx-auto">
                        Cancel
                    </button>
                </div>
            </form>

        </div>

    </div>

    <script src="../js/newCollege.js"></script>
</body>

</html>