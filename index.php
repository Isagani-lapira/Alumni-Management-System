  <?php
  $title = 'BulSU Connect';
  require("./partials/head.php") ?>

  <!-- Heading  -->

  <section id="home" class="daisy-hero min-h-screen bg-fixed" style="background-image: url('https://eliamarg.slarenasitsolutions.com/Alumni-Management-System/assets/heading.png');">
    <div class="daisy-hero-overlay bg-opacity-60"></div>
    <div class="daisy-hero-content text-center text-neutral-content">
      <div class="max-w-lg">
        <h1 class="mb-5 text-5xl font-bold">Empowering Connections: Uniting Graduates, Building Futures</h1>
        <p class="mb-5 text-xl">Join the Alumni Network today and be a part of something extraordinary.</p>
        <a class="daisy-btn daisy-btn-primary" href="./student-alumni/login.php">Get Started</a>
      </div>
    </div>
  </section>


  <!-- Program and Events -->
  <section id="events" class="container mx-auto py-8 min-h-screen">
    <h2 class="text-3xl font-neutral-800 font-bold">Programs and Events</h2>
    <!-- TODO configure for mobile design -->
    <div class="px-4 flex flex-col md:flex-row gap-4 p-8 justify-around">
      <div class="card w-64 mb-6 space-y-4">
        <div class="">
          <img class="object-cover" src="./assets/event-images/01.png" alt="" />
        </div>
        <div class="">
          <div class="flex flex-col sm:flex-row space-x-2">
            <p class="text-slate-800 font-bold">Homecoming</p>
            <span class="text-slate-500">November 22, 2021</span>
          </div>
        </div>
        <p class="text-slate-700">
          Let's now gather for the homecoming event for the alumni
        </p>
      </div>
      <!-- card 2 -->
      <div class="card w-64 mb-6 space-y-4">
        <div class="">
          <img class="object-cover" src="./assets/event-images/02.png" alt="" />
        </div>
        <div class="">
          <div class="flex flex-col sm:flex-row space-x-2">
            <p class="text-slate-800 font-bold">Alumni Night Out</p>
            <span class="text-slate-500">November 22, 2021</span>
          </div>
        </div>
        <p class="text-slate-700">
          Let's now gather for the homecoming event for the alumni
        </p>
      </div>
      <!-- card 3 -->

      <div class="card w-64 mb-6 space-y-4">
        <div class="">
          <img class="object-cover" src="./assets/event-images/03.png" alt="" />
        </div>
        <div class="">
          <div class="flex flex-col sm:flex-row space-x-2">
            <p class="text-slate-800 font-bold">FireFox</p>
            <span class="text-slate-500">January 05, 2023</span>
          </div>
        </div>
        <p class="text-slate-700">
          Let's now gather for the homecoming event for the alumni
        </p>
      </div>
    </div>
  </section>

  <!-- Community Section -->
  <section id="community" class="container mx-auto mb-12">
    <div class="flex flex-row justify-center">
      <img class="w-[30rem]" src="./assets/community.png" alt="" />
      <div class="space-x-20 space-y-8 text-right">
        <h2 class="font-bold text-3xl">BulSuan Community</h2>
        <p class="max-w-prose text-justify">
          No storm will make the Bulacan State University (BulSU) community
          falter, especially in this time where our brothers and sisters need
          help and care as different organizations of BulSU work hand-in-hand
          to extend their love to those in need.
        </p>
        <button class="btn-primary bg-accent px-8 py-3 rounded-lg text-white font-bold">
          View Community
        </button>
      </div>
    </div>
  </section>

  <!-- Donation Section -->
  <section id="donation" class="bg-slate-900 py-20 container">
    <div class="flex flex-row justify-around">
      <div>
        <img class="w-[30rem]" src="./assets/donation.png" alt="" />
      </div>
      <div class="text-center space-y-12">
        <h2 class="text-3xl font-bold text-white">Donations</h2>
        <p class="max-w-prose text-slate-300">
          Help us to reach more people not only our university student but
          also the one who's looking for help. Give a helping hand to
          provide better community, and better nation.You can give donation
          for your college to help them provide a better feature event for
          there student and also for other alumni. You can also donate for
          the university .
        </p>
        <p class="max-w-prose text-slate-300">
          You can give donation for your college to help them provide a
          better feature event for there student and also for other alumni.
          You can also donate for the university .
        </p>
      </div>
    </div>
  </section>

  <!-- Calendar Section -->
  <section id="calendar" class="container mx-auto text-center space-y-8 py-8">
    <div class="text my-12">
      <h2 class="text-3xl font-light text-slate-500 text-center">
        Bulacan State University Alumni
        <span class="text-accent text-3xl">Calendar</span>
      </h2>
    </div>

    <div class="flex flex-row justify-around">
      <div class="flex flex-col items-center">
        <div class="border-accent border-4 w-32 h-32 rounded-full flex items-center justify-center flex-col">
          <div class="font-bold text-5xl text-slate-700">26</div>
          <div>May</div>
        </div>
        <p class="font-bold">Alumni Homecoming</p>
      </div>

      <div class="flex flex-col items-center">
        <div class="border-accent border-4 w-32 h-32 rounded-full flex items-center justify-center flex-col">
          <div class="font-bold text-5xl text-slate-700">3</div>
          <div>March</div>
        </div>
        <p class="font-bold">Fun Run</p>
      </div>
      <div class="flex flex-col items-center">
        <div class="border-accent border-4 w-32 h-32 rounded-full flex items-center justify-center flex-col">
          <div class="font-bold text-5xl text-slate-700">5</div>
          <div>June</div>
        </div>
        <p class="font-bold">CICT Week</p>
      </div>
    </div>

    <button class="btn-secondary bg-secondary px-8 py-3 rounded-lg text-white font-bold">
      View Calendar
    </button>
  </section>

  <?php
  require("./partials/footer.php")
  ?>