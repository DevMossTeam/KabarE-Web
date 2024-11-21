<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal Example</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom styles for modal and overlay */
        .hidden {
            display: none;
        }

        .block {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">
<!-- Table -->
<div class="flex flex-col">
    <div class="overflow-x-auto relative"> <!-- Add relative here to help position dropdown correctly -->
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">
                <table class="min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gray-100 text-sm">
                        <tr>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase ">
                                Id
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Username
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Role
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase">
                                Terakhir Login
                            </th>
                            <th scope="col" class="p-4 text-xs font-medium text-center text-gray-500 uppercase">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="pengguna-table-body" class="text-gray-600 text-sm">
                        <!-- Example row, repeat for dynamic data -->
                        <tr>
                            <td class="py-4 px-6 border-b text-left text-base font-semibold text-gray-900">1</td>
                            <td class="py-4 px-6 border-b">
                                <div class="text-sm font-normal text-gray-500">
                                    <div class="text-base font-semibold text-gray-900">john_doe</div>
                                    <div class="text-sm font-normal text-gray-500">john@example.com</div>
                                </div>
                            </td>
                            <td class="py-4 px-6 border-b text-base text-left font-medium text-gray-900 whitespace-nowrap">Admin</td>
                            <td class="py-4 px-6 border-b text-base text-left text-gray-600 whitespace-nowrap">2024-11-12 10:30</td>
                            <td class="py-4 px-6 border-b text-center">
                                <div class="relative inline-block text-left">
                                    <button 
                                        type="button" 
                                        class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 focus:ring-1 ring-inset ring-blue-300 hover:bg-gray-50 focus:outline-none"
                                        id="menu-button-1" 
                                        aria-expanded="false" 
                                        aria-haspopup="true" 
                                        onclick="toggleDropdown(event, 1, 'john_doe')">
                                        <img src="../asset/elipses.svg" class="h-8" alt="dropdown" />
                                    </button>

                                    <!-- Dropdown menu -->
                                    <div id="dropdown-menu-1" class="hidden absolute right-0 z-20 mt-2 w-56 origin-top-right rounded-md bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button-1" tabindex="-1">
                                        <div class="py-1" role="none">
                                            <a href="#" class="flex items-center gap-2 block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" onclick="getEditPengguna(1)">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Edit</span>
                                            </a>

                                            <a href="#" class="flex items-center gap-2 block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" onclick="deletePengguna(1)">
                                                <svg aria-hidden="true" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Delete</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    // Function to handle dropdown actions
function toggleDropdown(event, index, penggunaName) {
    const dropdown = document.getElementById(`dropdown-menu-${index}`);
    const isVisible = !dropdown.classList.contains('hidden');

    // Hide all dropdowns first
    const allDropdowns = document.querySelectorAll('[id^="dropdown-menu-"]');
    allDropdowns.forEach(drop => drop.classList.add('hidden'));

    // If the dropdown wasn't visible, show it
    if (!isVisible) {
        dropdown.classList.remove('hidden');
    }

    // Add event listener to close the dropdown when clicking outside
    document.addEventListener('click', function closeDropdown(event) {
        // Check if the click is outside the dropdown and button
        if (!dropdown.contains(event.target) && !document.getElementById(`menu-button-${index}`).contains(event.target)) {
            dropdown.classList.add('hidden');
            document.removeEventListener('click', closeDropdown); // Remove the event listener after closing
        }
    });
}

</script>


</body>
</html>
