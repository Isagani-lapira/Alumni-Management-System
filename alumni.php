<?php

$title = 'Alumni of the Year';
require('./partials/head.php');
?>


<!-- heading image -->
<section class="relative">
    <!-- Image heading -->
    <img class="w-full max-w-full" src="./assets/alumni-heading.png" alt="">
    <!-- Dropdown menu -->
    <button class="absolute font-bold right-12 top-12 text-white bg-accent hover:bg-secondary focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center" type="button" data-dropdown-toggle="dropdown">
        2023
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>

    </button>
</section>

<!-- quotation -->
<section class="container mx-auto py-12 text-center ">
    <q class=" italic text-2xl">
        There are no secrets to success. It is the result of preparation,
        hard work, and learning from failure.
    </q>
    <p class="text-blue-600 italic">Orlando Pimentel 2013</p>
</section>

<!-- testimonials -->
<section class="bg-accent py-12">
    <div class="container mx-auto  space-y-12">
        <h2 class="text-4xl text-white font-bold ">Testimonials</h2>
        <div class="grid gap-3 justify-center md:grid-flow-col md:auto-cols-fr">
            <!-- testimonial card -->
            <div class="bg-white text-black p-8 rounded-lg max-w-sm">
                <q class="max-w-prose text-2xl">Very responsible person especially on work. A type of person who's eager to learn</q>
                <div class="flex items-center">
                    <img src="" alt="">
                    <div>
                        <div class="text-lg font-bold text-black">Wilhelm Miranda</div>
                        <p class="text-slate-500">Full Stack Developer</p>
                    </div>

                </div>
            </div>
            <!-- testimonial card -->
            <div class="bg-white text-black p-8 rounded-lg max-w-sm">
                <q class="max-w-prose text-2xl">Very responsible person especially on work. A type of person who's eager to learn</q>
                <div class="flex items-center">
                    <img src="" alt="">
                    <div>
                        <div class="text-lg font-bold text-black">Wilhelm Miranda</div>
                        <p class="text-slate-500">Full Stack Developer</p>
                    </div>

                </div>
            </div>
            <!-- testimonial card -->
            <div class="bg-white text-black p-8 rounded-lg max-w-sm">
                <q class="max-w-prose text-2xl">Very responsible person especially on work. A type of person who's eager to learn</q>
                <div class="flex items-center">
                    <img src="" alt="">
                    <div>
                        <div class="text-lg font-bold text-black">Wilhelm Miranda</div>
                        <p class="text-slate-500">Full Stack Developer</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <h2>Achievements</h2>
</section>

<section>
    <h2>Skills</h2>
</section>
<section>
    <h2>Education</h2>
</section>

<section>
    <h2>For more details you may visit me at: </h2>
</section>
<?php
require('./partials/footer.php');
