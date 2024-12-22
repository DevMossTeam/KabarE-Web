 <aside id="sidebar-multi-level-sidebar"
    class="fixed top-[80px] left-0 z-50 w-64 h-full transition-transform -translate-x-full md:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-blue-50 dark:bg-[#1C2434]">
       <ul class="space-y-2 font-medium">
          <li>
             <a href="../home/index.php"
                class="flex items-center p-2 text-blue-900 rounded-lg dark:text-white hover:bg-blue-100 dark:hover:bg-indigo-700 group">
                <svg
                   class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-500 dark:group-hover:text-white"
                   aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                   <path
                      d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                   <path
                      d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                </svg>
                <span class="ms-3 text-xl">Dashboard</span>
             </a>
          </li>
          <li>
             <input type="checkbox" id="dropdown-toggle" class="hidden peer" />

             <label for="dropdown-toggle"
                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700 cursor-pointer">
                <svg
                   class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                   xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 107 107" aria-hidden="true">
                   <path
                      d="M17.8346 93.625C15.3826 93.625 13.2842 92.7527 11.5395 91.008C9.79477 89.2633 8.92094 87.1634 8.91797 84.7083V22.2917C8.91797 19.8396 9.7918 17.7412 11.5395 15.9965C13.2871 14.2518 15.3855 13.378 17.8346 13.375H89.168C91.62 13.375 93.7199 14.2488 95.4676 15.9965C97.2153 17.7442 98.0876 19.8426 98.0846 22.2917V84.7083C98.0846 87.1604 97.2123 89.2603 95.4676 91.008C93.7229 92.7556 91.623 93.628 89.168 93.625H17.8346ZM26.7513 75.7917H80.2513V66.875H26.7513V75.7917ZM26.7513 57.9583H44.5846V31.2083H26.7513V57.9583ZM53.5013 57.9583H80.2513V49.0417H53.5013V57.9583ZM53.5013 40.125H80.2513V31.2083H53.5013V40.125Z" />
                </svg>
                <span class="flex-1 ms-3 text-xl text-left rtl:text-right whitespace-nowrap">Berita</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                   viewBox="0 0 10 6">
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m1 1 4 4 4-4" />
                </svg>
             </label>

             <ul id="dropdown-example" class="hidden py-2 space-y-2 peer-checked:block">
                <li>
                   <a href="../menu/berita.php"
                      class="flex items-center w-full p-1 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700">Berita</a>
                </li>
                <li>
                   <a href="../menu/tag.php"
                      class="flex items-center w-full p-1 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-indigo-100 dark:text-white dark:hover:bg-indigo-700">Tag</a>
                </li>
             </ul>
          </li>
          <li>
             <a href="../menu/pengguna.php"
                class="flex items-center p-2 text-blue-900 rounded-lg dark:text-white hover:bg-blue-100 dark:hover:bg-indigo-700 group">
                <svg
                   class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                   aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                   <path
                      d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                </svg>
                <span class="ms-3 text-xl">Pengguna</span>
             </a>
          </li>
          <li>
             <a href="../menu/newsletter.php"
                class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-indigo-100 dark:hover:bg-indigo-700 group text-xl">
                <svg
                   class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                   aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                   <path
                      d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap text-xl">Newsletter</span>
             </a>
          </li>
          <li>
             <a href="../menu/inbox.php"
                class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-indigo-100 dark:hover:bg-indigo-700 group text-xl">
                <svg
                   class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                   aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 112 112">
                   <path
                      d="M23.3333 98C20.7667 98 18.5702 97.0869 16.744 95.2607C14.9178 93.4344 14.0031 91.2364 14 88.6667V23.3333C14 20.7667 14.9147 18.5702 16.744 16.744C18.5733 14.9178 20.7698 14.0031 23.3333 14H88.6667C91.2333 14 93.4313 14.9147 95.2607 16.744C97.09 18.5733 98.0031 20.7698 98 23.3333V88.6667C98 91.2333 97.0869 93.4313 95.2607 95.2607C93.4344 97.09 91.2364 98.0031 88.6667 98H23.3333ZM56 74.6667C58.9556 74.6667 61.6389 73.8111 64.05 72.1C66.4611 70.3889 68.1333 68.1333 69.0667 65.3333H88.6667V23.3333H23.3333V65.3333H42.9333C43.8667 68.1333 45.5389 70.3889 47.95 72.1C50.3611 73.8111 53.0444 74.6667 56 74.6667Z" />
                </svg>

                <span class="flex-1 ms-3 whitespace-nowrap text-xl">Kotak Masuk</span>
                <span id="notification-badge"
                   class="inline-flex items-center justify-center w-8 h-8 p-1 ms-3 text-lg font-medium bg-blue-100 rounded-full dark:bg-blue-900 text-blue-300">0</span>
             </a>
          </li>

          <li>
             <a href="../menu/pengaturan.php"
                class="flex items-center p-2 text-blue-900 rounded-lg dark:text-white hover:bg-blue-100 dark:hover:bg-indigo-700 group">
                <svg
                   class="flex-shrink-0 w-6 h-6 fill-current text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                   width="102" height="107" viewBox="0 0 102 107" xmlns="http://www.w3.org/2000/svg">
                   <path
                      d="M51.0196 38.9319C47.4208 38.9319 44.0507 40.3191 41.4991 42.8544C38.9595 45.3897 37.5513 48.7382 37.5513 52.3138C37.5513 55.8895 38.9595 59.238 41.4991 61.7733C44.0507 64.2966 47.4208 65.6958 51.0196 65.6958C54.6183 65.6958 57.9884 64.2966 60.5401 61.7733C63.0797 59.238 64.4879 55.8895 64.4879 52.3138C64.4879 48.7382 63.0797 45.3897 60.5401 42.8544C59.2938 41.6066 57.8104 40.6173 56.176 39.9439C54.5416 39.2705 52.7889 38.9265 51.0196 38.9319ZM100.644 67.0949L92.7725 60.41C93.1456 58.1378 93.3382 55.8178 93.3382 53.5097C93.3382 51.2017 93.1456 48.8697 92.7725 46.6095L100.644 39.9245C101.239 39.4187 101.664 38.7451 101.864 37.9931C102.064 37.2412 102.029 36.4466 101.763 35.715L101.655 35.4041C99.4887 29.3851 96.2428 23.8059 92.0744 18.9368L91.8578 18.6856C91.3516 18.0943 90.677 17.6692 89.9228 17.4664C89.1687 17.2636 88.3704 17.2926 87.6331 17.5496L77.8599 21.0057C74.2491 18.0638 70.2291 15.7438 65.872 14.1293L63.9824 3.97631C63.8398 3.21145 63.4664 2.50779 62.9117 1.95883C62.3571 1.40986 61.6474 1.04157 60.8771 0.902891L60.5521 0.843096C54.2934 -0.281032 47.6976 -0.281032 41.4389 0.843096L41.1139 0.902891C40.3436 1.04157 39.634 1.40986 39.0793 1.95883C38.5246 2.50779 38.1512 3.21145 38.0086 3.97631L36.107 14.1772C31.7912 15.8044 27.7707 18.1188 24.2033 21.0296L14.3579 17.5496C13.6209 17.2906 12.822 17.2605 12.0674 17.4635C11.3128 17.6664 10.6382 18.0927 10.1333 18.6856L9.9166 18.9368C5.7556 23.811 2.51043 29.3889 0.335938 35.4041L0.227614 35.715C-0.314006 37.2099 0.131326 38.8841 1.34696 39.9245L9.3148 46.6812C8.94168 48.9295 8.76114 51.2256 8.76114 53.4978C8.76114 55.7939 8.94168 58.09 9.3148 60.3143L1.37103 67.071C0.776404 67.5768 0.35082 68.2505 0.150872 69.0024C-0.0490759 69.7543 -0.0139132 70.5489 0.251686 71.2805L0.36001 71.5915C2.53853 77.6067 5.75214 83.1676 9.94067 88.0587L10.1573 88.3099C10.6635 88.9012 11.3381 89.3263 12.0923 89.5291C12.8464 89.7319 13.6447 89.7029 14.382 89.446L24.2274 85.966C27.8141 88.8959 31.8101 91.2159 36.131 92.8184L38.0327 103.019C38.1753 103.784 38.5487 104.488 39.1034 105.037C39.658 105.586 40.3677 105.954 41.138 106.093L41.463 106.152C47.7833 107.283 54.2559 107.283 60.5762 106.152L60.9011 106.093C61.6715 105.954 62.3811 105.586 62.9358 105.037C63.4904 104.488 63.8639 103.784 64.0064 103.019L65.8961 92.8662C70.2531 91.2398 74.2731 88.9317 77.884 85.9899L87.6572 89.446C88.3942 89.705 89.1931 89.735 89.9477 89.5321C90.7023 89.3291 91.3769 88.9029 91.8818 88.3099L92.0985 88.0587C96.287 83.1437 99.5006 77.6067 101.679 71.5915L101.787 71.2805C102.305 69.7976 101.86 68.1354 100.644 67.0949ZM51.0196 73.3374C39.3326 73.3374 29.8603 63.9259 29.8603 52.3138C29.8603 40.7018 39.3326 31.2902 51.0196 31.2902C62.7065 31.2902 72.1789 40.7018 72.1789 52.3138C72.1789 63.9259 62.7065 73.3374 51.0196 73.3374Z" />
                </svg>
                <span class="ms-3 text-xl">Pengaturan</span>
             </a>
          </li> 
       </ul>
    </div>
 </aside>

 <script>
    document.getElementById('sidebarToggle').addEventListener('click', function () {
       const sidebar = document.getElementById('sidebar-multi-level-sidebar');
       sidebar.classList.toggle('-translate-x-full');
    });

    fetch('http://localhost/KabarE-Web/api/pesan.php')
       .then(response => response.json())
       .then(data => {
          inboxData = data.data || [];
          totalItems = inboxData.length;
          // console.log(inboxData);

          // Update the badge with the total number of items
          document.getElementById('notification-badge').textContent = totalItems;
       })
       .catch(error => {
          console.error('Error fetching data:', error);
       });
 </script>
