<section class="container p-4">
    <h1 class="font-bold text-2xl">Account Settings</h1>
    <p class="text-gray-400 font-bold text-sm">Manage your Account and Contact Information</p>

    <div class="flex flex-wrap justify-end gap-4">

    </div>
    <hr class="h-px my-5 bg-grayish border-0 dark\:bg-gray-700">
    <!-- Filter  -->

    <div class="grid grid-cols-6 grid-flow-row h-full">
        <div class="col-span-6 lg:col-span-1  p-4  space-y-2">
            <a class="block py-2 px-4  text-slate-600 font-bold rounded-full mb-2 cursor-pointer" id="accountButton">Account</a>

            <hr>
            <a class="block py-2 px-4  text-slate-600 font-bold rounded-full cursor-pointer" id="profileButton">Profile</a>
        </div>
        <div class="text-slate-600 col-span-5 p-4 bg-gray-100" id="content">
            <!-- Content will be displayed here when buttons are clicked -->

            <section id="account-content">
                <h2 class="font-bold text-xl">Manage Account Settings</h2>
                <!-- Form for email, change password section with password and confirm password and update button  -->
                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="email" class="font-bold daisy-label font-label-text ">Email</label>
                            <input type="email" name="email" id="email" class="border border-gray-300 rounded-md p-2 form-input daisy-input-bordered">

                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <div class="daisy-form-control w-full max-w-xs">
                        <label class=" font-bold daisy-label">
                            <span class="daisy-label-text">Password</span>
                        </label>
                        <input type="password" name="password" id="password" class="form-input font-bold  daisy-input-bordered w-full max-w-xs">
                        <!-- <input type="text"  class="daisy-input daisy-input-bordered w-full max-w-xs" /> -->
                        <label class="daisy-label">
                            <span class="daisy-label-text-alt"> Password must contain both upper and lowercase and special character
                            </span>
                            <span class="daisy-label-text-alt hidden">Bottom Right label</span>
                        </label>
                    </div>

                </div>

                <div class="flex flex-col gap-2">
                    <div class="daisy-form-control w-full max-w-xs">
                        <label for="confirmPassword" class="font-bold daisy-label">
                            <span class="daisy-label-text">Confirm Password</span>
                        </label>
                        <input type="password" name="confirmPassword" id="confirmPassword" class="form-input font-bold  daisy-input-bordered w-full max-w-xs">

                        <label class="daisy-label">
                            <span class="daisy-label-text-alt">
                            </span>
                            <span class="daisy-label-text-alt hidden">Bottom Right label</span>
                        </label>
                    </div>

                </div>

                <!-- Update Button -->
                <div class="flex flex-col gap-4 ">
                    <div class="flex flex-wrap gap-4 justify-end">
                        <div class="flex flex-col gap-2">
                            <button class=" btn-primary">Update Account</button>
                        </div>
                    </div>
                </div>



            </section>




            <section id="profile-content" class="hidden">
                <h2 class="font-bold text-xl">Manage Personal Information</h2>

                <!-- Profile Picture -->
                <div class="flex flex-wrap justify-between gap-4">
                    <div class="flex flex-wrap gap-4">
                        <div class="w-24 h-24 rounded-full bg-gray-300"></div>
                        <div class="flex flex-col justify-center">
                            <h3 class="font-bold text-lg">Profile Picture</h3>
                            <p class="text-gray-400">Upload a profile picture to be displayed on your profile</p>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center">
                        <button class="bg-slate-600 text-white px-4 py-2 rounded-full">Upload</button>
                    </div>




                </div>

                <!-- Form that has first name, last name, birth date, gender, address -->
                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="firstName" class="font-bold">First Name</label>
                            <input type="text" name="firstName" id="firstName" class="border border-gray-300 rounded-md p-2">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="lastName" class="font-bold">Last Name</label>
                            <input type="text" name="lastName" id="lastName" class="border border-gray-300 rounded-md p-2">
                        </div>
                    </div>
                </div>

                <!-- birth date form input -->

                <div class="flex flex-row gap-4">
                    <div class="flex flex-col gap-2">
                        <label for="birthday" class="font-bold">Birth Day</label>
                        <input type="date" name="birthday" id="firstName" class="form-input border border-gray-300 rounded-md p-2">
                    </div>


                </div>
                <p class="daisy-label font-bold">Gender</p>
                <div class="flex flex-row gap-2">
                    <label for="maleRadio" class="daisy-label">
                        <input type="radio" name="gender" value="male" class="daisy-radio" id="maleRadio" />

                        Male
                    </label>
                    <label for="femaleRadio" class="daisy-label">
                        <input type="radio" name="gender" value="female" class="daisy-radio" id="femaleRadio" />
                        Female
                    </label>
                </div>


                <!-- Old Address -->
                <!-- <div class="flex flex-col gap-4">
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col gap-2">
                        <label for="street" class="font-bold">Street</label>
                        <input type="text" name="street" id="street" class="border border-gray-300 rounded-md p-2">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="city" class="font-bold">City</label>
                        <input type="text" name="city" id="city" class="border border-gray-300 rounded-md p-2">
                    </div>
                </div>
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col gap-2">
                        <label for="state" class="font-bold">State</label>
                        <input type="text" name="state" id="state" class="border border-gray-300 rounded-md p-2">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="zip" class="font-bold">Zip</label>
                        <input type="text" name="zip" id="zip" class="border border-gray-300 rounded-md p-2">
                    </div>
                </div>
            </div> -->

                <!-- Address -->
                <div class="flex flex-row gap-4">
                    <div class="flex flex-col gap-2">
                        <label for="birthday" class="font-bold">Address</label>
                        <input type="text" name="address" id="address" class="form-input border border-gray-300 rounded-md p-2">
                    </div>
                </div>

                <!-- Facebook, Instagram, Twitter, and LinkedIn Profile Input -->
                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="facebook" class="font-bold">Facebook</label>
                            <input type="text" name="facebook" id="facebook" class="border border-gray-300 rounded-md p-2">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="instagram" class="font-bold">Instagram</label>
                            <input type="text" name="instagram" id="instagram" class="border border-gray-300 rounded-md p-2">
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="twitter" class="font-bold">Twitter</label>
                            <input type="text" name="twitter" id="twitter" class="border border-gray-300 rounded-md p-2">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="linkedin" class="font-bold">LinkedIn</label>
                            <input type="text" name="linkedin" id="linkedin" class="border border-gray-300 rounded-md p-2">
                        </div>
                    </div>

                </div>
            </section>

        </div>


</section>

<!-- <script type="module" src="./account-settings/settings.js"></script> -->