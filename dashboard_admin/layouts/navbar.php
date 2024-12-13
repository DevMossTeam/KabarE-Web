<nav class="fixed top-0 z-30 w-full bg-white border-b border-gray-200 h-20"> <!-- Added height class here -->
    <div class="px-3 py-4 lg:px-5 lg:pl-3"> <!-- Adjusted padding here -->
      <div class="flex items-center justify-between">
        <div class="flex items-center justify-start">
          <button id="sidebarToggle" aria-controls="sidebar-multi-level-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 ">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-12 h-12" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
            </svg>
          </button>
          <a href="../home/index.php" class="flex ms-2 md:me-24">
            <img src="../asset/logo.svg" class="h-8 me-3" alt="FlowBite Logo" />
            <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap"></span>
          </a>
          <form action="#" method="GET" class="hidden lg:block lg:pl-3.5">
            <label for="topbar-search" class="sr-only">Search</label>
            <div class="relative mt-1 lg:w-96">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-500 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
              </div>
              <input type="text" name="email" id="topbar-search" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5" placeholder="Search">
            </div>
          </form>
        </div>
        <div class="flex items-center">
          <div class="flex items-center ms-3">
          <div class="hidden md:block md:mr-5">Neil Sims</div>
            <details class="relative">
              <summary class="flex text-sm bg-gray-800 rounded-full cursor-pointer focus:outline-none focus:ring-4 focus:ring-gray-300 ">
                <span class="sr-only">Open user menu</span>
                <img class="w-12 h-12 rounded-full" src="https://www.freeiconspng.com/thumbs/profile-icon-png/am-a-19-year-old-multimedia-artist-student-from-manila--21.png" alt="user photo">
              </summary>
              <div class="absolute right-0 z-50 mt-2 w-48 bg-white divide-y divide-gray-100 rounded shadow ">
                <div class="px-4 py-3">
                  <p class="text-sm text-gray-900 ">Neil Sims</p>
                  <p class="text-sm font-medium text-gray-900 truncate">neil.sims@flowbite.com</p>
                </div>
                <ul class="py-1">
                  <li><a href="../home/index.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 ">Dashboard</a></li>
                  <li><a href="../menu/pengaturan.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a></li>
                  <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 ">Earnings</a></li>
                  <li><a href="../../logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 ">Sign Out</a></li>
                </ul>
              </div>
            </details>
          </div>
        </div>
      </div>
    </div>
</nav>