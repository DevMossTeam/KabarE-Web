<?php
include '../header & footer/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Statistik Review</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-gray-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Total Pengajuan</h2>
                <p class="text-4xl font-semibold" style="margin-bottom: 0.5rem;">20 <span class="text-sm italic" style="color: #000000; margin-top: -1rem; display: inline-block; font-size: 16px;">Menunggu Tindakan</span></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-red-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Pengajuan ditolak <span class="inline-block w-2 h-2" style="background-color: #FF0000; border-radius: 50%;"></span></h2>
                <p class="text-2xl" style="margin-bottom: 0;">20 <span class="text-sm italic" style="color: #000000; font-size: 16px;">ditolak</span></p>
                <p class="text-2xl mt-0">2 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Kembali diajukan</span></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-green-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Revisi Minor <span class="inline-block w-2 h-2" style="background-color: #32FF00; border-radius: 50%;"></span></h2>
                <p class="text-2xl" style="margin-bottom: 0;">20 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Membutuhkan Revisi</span></p>
                <p class="text-2xl mt-0">2 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Kembali diajukan</span></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-yellow-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Revisi Mayor <span class="inline-block w-2 h-2" style="background-color: #FFF500; border-radius: 50%;"></span></h2>
                <p class="text-2xl" style="margin-bottom: 0;">20 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Membutuhkan Revisi</span></p>
                <p class="text-2xl mt-0">2 <span class="text-sm italic" style="color: #000000; font-size: 16px;">Kembali diajukan</span></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center">
            <div class="text-blue-500 text-3xl mr-2 flex-shrink-0" style="font-weight: bold; height: 300%; display: flex; align-items: center; justify-content: center;">|</div>
            <div>
                <h2 class="text-xl font-semibold" style="color: #BFBFBF; font-style: italic; font-size: 16px; margin-top: -0.5rem;">Publikasi <span class="inline-block w-2 h-2" style="background-color: #4A99FF; border-radius: 50%;"></span></h2>
                <p class="text-4xl font-semibold" style="margin-bottom: 0.5rem;">20 <span class="text-sm italic" style="color: #000000; margin-top: -1rem; display: inline-block; font-size: 16px;">Terpublikasi</span></p>
            </div>
        </div>
    </div>

    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden border border-gray-300">
        <thead class="bg-gray-200 border-b border-gray-300">
            <tr>
                <th class="py-2 px-4 text-left" colspan="8">
                    <label for="filter" class="mr-2">Tampilkan</label>
                    <select id="filter" class="border border-gray-300 rounded p-1">
                        <option>All Documents</option>
                        <!-- Opsi lainnya -->
                    </select>
                </th>
                <th class="py-2 px-4 text-right">
                    <div class="relative flex items-center justify-end">
                        <input type="text" id="search" placeholder="Search" class="border border-gray-300 rounded p-1 transition-all duration-300 ease-in-out transform origin-right" style="width: 0; opacity: 0; position: absolute; right: 3rem;">
                        <button id="searchBtn" class="bg-blue-500 text-white p-2" style="border-radius: 50%; width: 40px; height: 40px;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </th>
            </tr>
            <tr>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Icon</th>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Username</th>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Posisi</th>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Email</th>
                <th class="py-2 px-4 text-center w-56 border-r border-gray-300 bg-white">Tanggal Pengajuan</th>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Judul Artikel</th>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Reviewer</th>
                <th class="py-2 px-4 text-center border-r border-gray-300 bg-white">Status</th>
                <th class="py-2 px-4 text-center w-40 bg-white">Action</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">
                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.2983 6.14888L13.4628 1.4377M13.4628 1.4377L8.7516 1.27318M13.4628 1.4377L1.27355 12.8043" stroke="#4A99FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">User1</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">Reviewer</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">user1@example.com</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">15 Maret 2025</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Artikel Teknologi Baru</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Reviewer1</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white"><span class="inline-block w-3 h-3 bg-gray-500 rounded-full"></span></td>
                <td class="py-2 px-4 text-center bg-white">
                    <button class="text-gray-500 hover:underline flex items-center">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2">
                            <path d="M1.71429 0H10.2857C10.7404 0 11.1764 0.180612 11.4979 0.502103C11.8194 0.823594 12 1.25963 12 1.71429V10.2857C12 10.7404 11.8194 11.1764 11.4979 11.4979C11.1764 11.8194 10.7404 12 10.2857 12H1.71429C1.25963 12 0.823594 11.8194 0.502103 11.4979C0.180612 11.1764 0 10.7404 0 10.2857V1.71429C0 1.25963 0.180612 0.823594 0.502103 0.502103C0.823594 0.180612 1.25963 0 1.71429 0ZM1.71429 0.857143C1.48696 0.857143 1.26894 0.947449 1.10819 1.10819C0.947449 1.26894 0.857143 1.48696 0.857143 1.71429V10.2857C0.857143 10.513 0.947449 10.7311 1.10819 10.8918C1.26894 11.0526 1.48696 11.1429 1.71429 11.1429H10.2857C10.513 11.1429 10.7311 11.0526 10.8918 10.8918C11.0526 10.7311 11.1429 10.513 11.1429 10.2857V1.71429C11.1429 1.48696 11.0526 1.26894 10.8918 1.10819C10.7311 0.947449 10.513 0.857143 10.2857 0.857143H1.71429Z" fill="#959595"/>
                            <path d="M1.71484 3.85631C1.71484 3.74264 1.76 3.63363 1.84037 3.55326C1.92074 3.47289 2.02975 3.42773 2.14342 3.42773H9.8577C9.97136 3.42773 10.0804 3.47289 10.1607 3.55326C10.2411 3.63363 10.2863 3.74264 10.2863 3.85631V9.85631C10.2863 9.96997 10.2411 10.079 10.1607 10.1594C10.0804 10.2397 9.97136 10.2849 9.8577 10.2849H2.14342C2.02975 10.2849 1.92074 10.2397 1.84037 10.1594C1.76 10.079 1.71484 9.96997 1.71484 9.85631V3.85631Z" fill="#959595"/>
                        </svg>
                        Ambil
                    </button>
                </td>
            </tr>
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">
                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.43807 8.09331L1.27355 12.8045M1.27355 12.8045L5.98473 12.969M1.27355 12.8045L13.4628 1.43785" stroke="#AAAAAA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">User2</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">Reviewer</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">user2@example.com</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">20 April 2025</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Ekonomi Digital</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Reviewer2</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white"><span class="inline-block w-3 h-3 bg-gray-500 rounded-full"></span></td>
                <td class="py-2 px-4 text-center bg-white">
                    <button class="text-gray-500 hover:underline flex items-center">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2">
                            <path d="M1.71429 0H10.2857C10.7404 0 11.1764 0.180612 11.4979 0.502103C11.8194 0.823594 12 1.25963 12 1.71429V10.2857C12 10.7404 11.8194 11.1764 11.4979 11.4979C11.1764 11.8194 10.7404 12 10.2857 12H1.71429C1.25963 12 0.823594 11.8194 0.502103 11.4979C0.180612 11.1764 0 10.7404 0 10.2857V1.71429C0 1.25963 0.180612 0.823594 0.502103 0.502103C0.823594 0.180612 1.25963 0 1.71429 0ZM1.71429 0.857143C1.48696 0.857143 1.26894 0.947449 1.10819 1.10819C0.947449 1.26894 0.857143 1.48696 0.857143 1.71429V10.2857C0.857143 10.513 0.947449 10.7311 1.10819 10.8918C1.26894 11.0526 1.48696 11.1429 1.71429 11.1429H10.2857C10.513 11.1429 10.7311 11.0526 10.8918 10.8918C11.0526 10.7311 11.1429 10.513 11.1429 10.2857V1.71429C11.1429 1.48696 11.0526 1.26894 10.8918 1.10819C10.7311 0.947449 10.513 0.857143 10.2857 0.857143H1.71429Z" fill="#959595"/>
                            <path d="M1.71484 3.85631C1.71484 3.74264 1.76 3.63363 1.84037 3.55326C1.92074 3.47289 2.02975 3.42773 2.14342 3.42773H9.8577C9.97136 3.42773 10.0804 3.47289 10.1607 3.55326C10.2411 3.63363 10.2863 3.74264 10.2863 3.85631V9.85631C10.2863 9.96997 10.2411 10.079 10.1607 10.1594C10.0804 10.2397 9.97136 10.2849 9.8577 10.2849H2.14342C2.02975 10.2849 1.92074 10.2397 1.84037 10.1594C1.76 10.079 1.71484 9.96997 1.71484 9.85631V3.85631Z" fill="#959595"/>
                        </svg>
                        Ambil
                    </button>
                </td>
            </tr>
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">
                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.2983 6.14888L13.4628 1.4377M13.4628 1.4377L8.7516 1.27318M13.4628 1.4377L1.27355 12.8043" stroke="#4A99FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">User3</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">Penulis</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">user3@example.com</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">5 Januari 2025</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Pengembangan AI</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Reviewer3</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white"><span class="inline-block w-3 h-3 bg-blue-500 rounded-full"></span></td>
                <td class="py-2 px-4 text-center bg-white">
                    <button class="text-blue-500 hover:underline flex items-center">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2">
                            <path d="M7 7.3325L8.33 8.1375C8.45833 8.21916 8.58667 8.21636 8.715 8.1291C8.84333 8.04183 8.89 7.92213 8.855 7.77L8.505 6.2475L9.695 5.215C9.81167 5.11 9.84667 4.9847 9.8 4.8391C9.75333 4.6935 9.64833 4.61463 9.485 4.6025L7.9275 4.48L7.315 3.045C7.25667 2.905 7.15167 2.835 7 2.835C6.84833 2.835 6.74333 2.905 6.685 3.045L6.0725 4.48L4.515 4.6025C4.35167 4.61417 4.24667 4.69303 4.2 4.8391C4.15333 4.98517 4.18833 5.11047 4.305 5.215L5.495 6.2475L5.145 7.77C5.11 7.92167 5.15667 8.04136 5.285 8.1291C5.41333 8.21683 5.54167 8.21963 5.67 8.1375L7 7.3325ZM2.8 11.2L1.19 12.81C0.968334 13.0317 0.714468 13.0814 0.428401 12.9591C0.142334 12.8368 -0.000465527 12.618 1.14007e-06 12.3025V1.4C1.14007e-06 1.015 0.137201 0.685533 0.411601 0.4116C0.686001 0.137667 1.01547 0.000466667 1.4 0H12.6C12.985 0 13.3147 0.1372 13.5891 0.4116C13.8635 0.686 14.0005 1.01547 14 1.4V9.8C14 10.185 13.863 10.5147 13.5891 10.7891C13.3152 11.0635 12.9855 11.2005 12.6 11.2H2.8ZM2.205 9.8H12.6V1.4H1.4V10.5875L2.205 9.8Z" fill="#4A99FF"/>
                        </svg>
                        Edit
                    </button>
                </td>
            </tr>
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">
                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.43807 8.09331L1.27355 12.8045M1.27355 12.8045L5.98473 12.969M1.27355 12.8045L13.4628 1.43785" stroke="#AAAAAA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">User4</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">Reviewer</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">user4@example.com</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">20 April 2025</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Ekonomi Digital</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Reviewer4</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white"><span class="inline-block w-3 h-3 bg-yellow-500 rounded-full"></span></td>
                <td class="py-2 px-4 text-center bg-white">
                    <button class="text-yellow-500 hover:underline flex items-center">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2">
                            <path d="M7 7.3325L8.33 8.1375C8.45833 8.21916 8.58667 8.21636 8.715 8.1291C8.84333 8.04183 8.89 7.92213 8.855 7.77L8.505 6.2475L9.695 5.215C9.81167 5.11 9.84667 4.9847 9.8 4.8391C9.75333 4.6935 9.64833 4.61463 9.485 4.6025L7.9275 4.48L7.315 3.045C7.25667 2.905 7.15167 2.835 7 2.835C6.84833 2.835 6.74333 2.905 6.685 3.045L6.0725 4.48L4.515 4.6025C4.35167 4.61417 4.24667 4.69303 4.2 4.8391C4.15333 4.98517 4.18833 5.11047 4.305 5.215L5.495 6.2475L5.145 7.77C5.11 7.92167 5.15667 8.04136 5.285 8.1291C5.41333 8.21683 5.54167 8.21963 5.67 8.1375L7 7.3325ZM2.8 11.2L1.19 12.81C0.968334 13.0317 0.714468 13.0814 0.428401 12.9591C0.142334 12.8368 -0.000465527 12.618 1.14007e-06 12.3025V1.4C1.14007e-06 1.015 0.137201 0.685533 0.411601 0.4116C0.686001 0.137667 1.01547 0.000466667 1.4 0H12.6C12.985 0 13.3147 0.1372 13.5891 0.4116C13.8635 0.686 14.0005 1.01547 14 1.4V9.8C14 10.185 13.863 10.5147 13.5891 10.7891C13.3152 11.0635 12.9855 11.2005 12.6 11.2H2.8ZM2.205 9.8H12.6V1.4H1.4V10.5875L2.205 9.8Z" fill="#FFF500"/>
                        </svg>
                        Edit
                    </button>
                </td>
            </tr>
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">
                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.2983 6.14888L13.4628 1.4377M13.4628 1.4377L8.7516 1.27318M13.4628 1.4377L1.27355 12.8043" stroke="#4A99FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">User5</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">Penulis</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">user5@example.com</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">5 Januari 2025</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Pengembangan AI</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Reviewer5</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white"><span class="inline-block w-3 h-3 bg-green-500 rounded-full"></span></td>
                <td class="py-2 px-4 text-center bg-white">
                    <button class="text-green-500 hover:underline flex items-center">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2">
                            <path d="M7 7.3325L8.33 8.1375C8.45833 8.21916 8.58667 8.21636 8.715 8.1291C8.84333 8.04183 8.89 7.92213 8.855 7.77L8.505 6.2475L9.695 5.215C9.81167 5.11 9.84667 4.9847 9.8 4.8391C9.75333 4.6935 9.64833 4.61463 9.485 4.6025L7.9275 4.48L7.315 3.045C7.25667 2.905 7.15167 2.835 7 2.835C6.84833 2.835 6.74333 2.905 6.685 3.045L6.0725 4.48L4.515 4.6025C4.35167 4.61417 4.24667 4.69303 4.2 4.8391C4.15333 4.98517 4.18833 5.11047 4.305 5.215L5.495 6.2475L5.145 7.77C5.11 7.92167 5.15667 8.04136 5.285 8.1291C5.41333 8.21683 5.54167 8.21963 5.67 8.1375L7 7.3325ZM2.8 11.2L1.19 12.81C0.968334 13.0317 0.714468 13.0814 0.428401 12.9591C0.142334 12.8368 -0.000465527 12.618 1.14007e-06 12.3025V1.4C1.14007e-06 1.015 0.137201 0.685533 0.411601 0.4116C0.686001 0.137667 1.01547 0.000466667 1.4 0H12.6C12.985 0 13.3147 0.1372 13.5891 0.4116C13.8635 0.686 14.0005 1.01547 14 1.4V9.8C14 10.185 13.863 10.5147 13.5891 10.7891C13.3152 11.0635 12.9855 11.2005 12.6 11.2H2.8ZM2.205 9.8H12.6V1.4H1.4V10.5875L2.205 9.8Z" fill="#32FF00"/>
                        </svg>
                        Edit
                    </button>
                </td>
            </tr>
            <tr class="border-b border-gray-300">
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">
                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.43807 8.09331L1.27355 12.8045M1.27355 12.8045L5.98473 12.969M1.27355 12.8045L13.4628 1.43785" stroke="#AAAAAA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">User6</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">Reviewer</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">user6@example.com</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white">20 April 2025</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Ekonomi Digital</td>
                <td class="py-2 px-4 text-left border-r border-gray-300 bg-white">Reviewer6</td>
                <td class="py-2 px-4 text-center border-r border-gray-300 bg-white"><span class="inline-block w-3 h-3 bg-red-500 rounded-full"></span></td>
                <td class="py-2 px-4 text-center bg-white">
                    <button class="text-red-500 hover:underline flex items-center">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2">
                            <path d="M7 7.3325L8.33 8.1375C8.45833 8.21916 8.58667 8.21636 8.715 8.1291C8.84333 8.04183 8.89 7.92213 8.855 7.77L8.505 6.2475L9.695 5.215C9.81167 5.11 9.84667 4.9847 9.8 4.8391C9.75333 4.6935 9.64833 4.61463 9.485 4.6025L7.9275 4.48L7.315 3.045C7.25667 2.905 7.15167 2.835 7 2.835C6.84833 2.835 6.74333 2.905 6.685 3.045L6.0725 4.48L4.515 4.6025C4.35167 4.61417 4.24667 4.69303 4.2 4.8391C4.15333 4.98517 4.18833 5.11047 4.305 5.215L5.495 6.2475L5.145 7.77C5.11 7.92167 5.15667 8.04136 5.285 8.1291C5.41333 8.21683 5.54167 8.21963 5.67 8.1375L7 7.3325ZM2.8 11.2L1.19 12.81C0.968334 13.0317 0.714468 13.0814 0.428401 12.9591C0.142334 12.8368 -0.000465527 12.618 1.14007e-06 12.3025V1.4C1.14007e-06 1.015 0.137201 0.685533 0.411601 0.4116C0.686001 0.137667 1.01547 0.000466667 1.4 0H12.6C12.985 0 13.3147 0.1372 13.5891 0.4116C13.8635 0.686 14.0005 1.01547 14 1.4V9.8C14 10.185 13.863 10.5147 13.5891 10.7891C13.3152 11.0635 12.9855 11.2005 12.6 11.2H2.8ZM2.205 9.8H12.6V1.4H1.4V10.5875L2.205 9.8Z" fill="#FF0000"/>
                        </svg>
                        Edit
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('searchBtn').addEventListener('click', function() {
        const searchInput = document.getElementById('search');
        if (searchInput.style.width === '0px' || searchInput.style.width === '') {
            searchInput.style.width = '200px';
            searchInput.style.opacity = '1';
        } else {
            searchInput.style.width = '0';
            searchInput.style.opacity = '0';
        }
    });

    document.getElementById('search').addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(filter));
            row.style.display = match ? '' : 'none';
        });
    });
</script>

<?php
include '../header & footer/footer.php';
?>