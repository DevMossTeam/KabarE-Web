<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="/dashboard_admin/js/chart.js"> </script>
    <link rel="shortcut icon" href="../../assets/web-icon/Ic-main-KabarE.svg" type="KabarE">
</head>

<body class="bg-[#ECEFF5]">

    <?php
    include '../layouts/navbar.php';
    include '../layouts/sidebar.php';
    ?>

    <!-- Main Content -->
    <div class="p-4 sm:ml-64 mt-14">
        <div class="p-4 border-gray-50 mt-2">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="grid grid-cols-1 gap-4 mb-4 col-span-2">
                  <!-- Card 1 (top card) -->
                    <div class="p-4 h-[450px] rounded bg-gray-50 shadow">
                        <div class="flex items-center justify-between ">
                            <div class="flex-shrink-0">
                                <span class="text-xl font-bold leading-none text-gray-900 sm:text-2xl">+1.000</span>
                                <h3 class="text-base font-light text-gray-500 dark:text-gray-600">Statistik Pembaca Aktif Minggu ini</h3>
                            </div>
                            <div class="flex items-center justify-end flex-1 text-base font-medium text-green-500">
                                12.5%
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>

                        <div id="main-chart" class="w-full h-full"></div> <!-- Make chart container responsive -->

                        <!-- <div class="mt-4 text-sm text-gray-600">Footer Content</div> -->
                    </div>

                    <!-- Card 2 (bottom card) -->
                    <div class="flex items-center justify-center h-[150px] rounded bg-gray-50 shadow">
                        <p class="text-2xl text-gray-400">
                            <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                            </svg>
                        </p>
                    </div>
                </div>

                <!-- Card 3 (right side) -->
                <div class="h-[616px] rounded bg-gray-50 shadow p-5">
                    <div class="flex justify-between items-center">
                        <div class="text-xl font-semibold">Berita Popular</div>
                        <svg class="w-6 h-6" viewBox="0 0 77 88" fill="none" xmlns="http://www.w3.org/2000/svg">
                             <path d="M44.2093 86.1944C58.1461 83.4035 76.1667 73.3767 76.1667 47.4515C76.1667 23.8625 58.8995 8.1513 46.4831 0.933256C43.7234 -0.671744 40.5 1.43705 40.5 4.62476V12.7746C40.5 19.2035 37.7982 30.9378 30.2904 35.8197C26.4562 38.3119 22.31 34.5803 21.8463 30.0328L21.4629 26.2967C21.0171 21.9543 16.5944 19.3194 13.1258 21.9677C6.88862 26.7158 0.375 35.0529 0.375 47.447C0.375 79.1503 23.9551 87.0816 35.743 87.0816C36.4325 87.0816 37.1518 87.0593 37.9008 87.0148C32.0782 86.5199 22.6667 82.9086 22.6667 71.2278C22.6667 62.0882 29.3319 55.9134 34.3965 52.904C35.7608 52.1015 37.3524 53.1493 37.3524 54.732V57.3624C37.3524 59.3686 38.1326 62.5118 39.9828 64.6607C42.0782 67.0949 45.15 64.5448 45.3952 61.3437C45.4755 60.3361 46.492 59.6941 47.3658 60.2023C50.2236 61.8742 53.875 65.4409 53.875 71.2278C53.875 80.3585 48.8415 84.5582 44.2093 86.1944Z" fill="#EC6F59"/>
                        </svg>
                    </div>
                    <!-- Table Wrapper with fixed header -->
                    <div class="mt-5">
                        <table class="w-full text-left table-auto">
                            <thead class="bg-gray-200 text-gray-700">
                                <tr>
                                    <th class="px-4 py-2">Top 10</th>
                                    <th class="px-4 py-2">Berita</th>
                                </tr>
                            </thead>
                        </table>
                        <!-- Scrollable body container -->
                        <div class="h-[400px] overflow-y-auto">
                            <table class="w-full text-left table-auto">
                                <tbody id="news-table-body">
                                    <!-- Rows will be inserted here dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-3 mt-4 border-t border-gray-200 sm:pt-6">
                    <div class="relative">
                        <button class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 rounded-lg hover:text-gray-900" type="button" onclick="toggleDropdown()">
                            7 Hari Terakhir
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="weekly-sales-dropdown" class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow">
                            <div class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    16 Sep, 2021 - 22 Sep, 2021
                                </p>
                            </div>
                            <ul class="py-1">
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kemarin</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Hari Ini</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">7 Hari Terakhir</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">30 Hari Terakhir</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">90 Hari Terakhir</a>
                                </li>
                            </ul>
                            <div class="py-1">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kustom...</a>
                            </div>
                    </div>
                    </div>
                    <!-- <div class="flex-shrink-0">
                        <a href="#" class="inline-flex items-center p-2 text-xs font-medium uppercase rounded-lg text-primary-700 sm:text-sm hover:bg-gray-100">
                            Laporan Penjualan
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div> -->
                    </div>
                    <script>
                        function toggleDropdown() {
                            const dropdown = document.getElementById('weekly-sales-dropdown');
                            dropdown.classList.toggle('hidden');
                        }
                    </script>
                </div>
            </div>

            <div class="grid w-full grid-cols-1 gap-4 xl:grid-cols-2 2xl:grid-cols-3">
                <!-- Card 1 -->
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex sm:p-6">
                    <div class="w-full grid grid-cols-2 gap-4 items-center">
                        <!-- Left Column -->
                        <div class="w-full flex flex-col justify-center">
                            <h3 class="text-xl font-normal text-gray-500">Berita Terbaru</h3>
                            <span class="text-2xl font-bold leading-none text-gray-900 sm:text-3xl">2,340</span>
                            <p class="flex items-center text-sm font-normal text-gray-500">
                                <span class="flex items-center mr-1.5 text-sm text-green-500">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z"></path>
                                    </svg>
                                    12.5%
                                </span>
                                Since last month
                            </p>
                        </div>

                        <!-- Right Column (Chart) -->
                        <div class="w-full flex justify-center items-center" id="news-chart"></div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex sm:p-6">
                    <div class="w-full grid grid-cols-2 gap-4 items-center">
                        <!-- Left Column -->
                        <div class="w-full flex flex-col justify-center">
                            <h3 class="text-xl font-semibold text-gray-500">Pembaca Baru</h3>
                            <span class="text-xl font-bold leading-none text-gray-900 sm:text-2xl">2,340</span>
                            <p class="flex items-center text-sm font-normal text-gray-500">
                                <span class="flex items-center mr-1.5 text-sm text-green-500">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z"></path>
                                    </svg>
                                    12.5%
                                </span>
                                Since last month
                            </p>
                        </div>

                        <!-- Right Column (Chart) -->
                        <div class="w-full flex justify-center items-center" id="new-readers-chart"></div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex sm:p-6">
                    <div class="w-full grid grid-cols-2 gap-4 items-center">
                        <!-- Left Column -->
                        <div class="w-full flex flex-col justify-center">
                            <h3 class="text-base font-normal text-gray-500">Jumlah Artikel</h3>
                            <span class="text-2xl font-bold leading-none text-gray-900 sm:text-3xl">15</span>
                            <p class="flex items-center text-sm font-normal text-gray-500">
                                <span class="flex items-center mr-1.5 text-sm text-green-500">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z"></path>
                                    </svg>
                                    10%
                                </span>
                                Since Last Month
                            </p>
                        </div>

                        <!-- Right Column (Chart) -->
                        <div class="w-full flex justify-center items-center" id="articles-published-chart"></div>
                    </div>
                </div>
            </div>
       

            <div class="grid grid-cols-2 gap-4 mt-2">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-shrink-0">
                            <span class="text-xl font-semibold leading-none text-gray-900 sm:text-2xl">Jumlah Bertia Bedasarkan Kategori</span>
                        </div>
                        <div class="flex items-center justify-end flex-1 text-base font-medium text-green-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                    <div id="chart-donut-kategori" class="chart-donut-kategori w-full h-80"></div>
                </div>

                <div class="bg-gray-100 p-4 rounded-lg">
                    <h3 class="text-gray-600">Card Title 2</h3>
                    <p class="text-gray-500">Some content for the second card.</p>
                </div>
            </div>
    </div>

    <script>
        // ApexCharts Chart
        var options = {
            chart: {
                type: 'line',
                height: 350
            },
            series: [{
                name: "Sales",
                data: [30, 40, 45, 50, 49, 60, 70, 91, 125]
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();      

         // Dummy data array
         const newsData = [
            { top: "1", title: "Global Innovation Summit Unveils Groundbreaking Tech for a Sustainable Future" },
            { top: "2", title: "Breakthrough in Renewable Energy Promises Cleaner, Cheaper Power Worldwide" },
            { top: "3", title: "New Health Advancements Transform the Future of Personalized Medicine" },
            { top: "4", title: "AI and Robotics: Shaping Tomorrow’s Workforce and Daily Life" },
            { top: "5", title: "Historic Climate Agreement Reached at International Summit" },
            { top: "6", title: "New Advances in Quantum Computing" },
            { top: "7", title: "The Rise of Electric Vehicles in 2023" },
            { top: "8", title: "Exploring the Depths of the Ocean: Latest Discoveries" },
            { top: "9", title: "How Smart Cities are Transforming Urban Living" },
            { top: "10", title: "The Future of Space Exploration and Mars Colonization" }
        ];

        // Get the table body element
        const tableBody = document.getElementById("news-table-body");

        // Populate the table with data
        newsData.forEach((news) => {
            const row = document.createElement("tr");
            row.className = "border-b border-gray-300";

            // Create cells for 'Top' and 'Title Berita'
            const topCell = document.createElement("td");
            topCell.className = "px-4 py-2";
            topCell.textContent = news.top;

            const titleCell = document.createElement("td");
            titleCell.className = "px-4 py-2";
            titleCell.textContent = news.title;

            // Append cells to the row
            row.appendChild(topCell);
            row.appendChild(titleCell);

            // Append the row to the table body
            tableBody.appendChild(row);
        });
    </script>

</body>

</html>

<script>
    const getMainChartOptions = () => {
    // Define colors without dark mode check
    const mainChartColors = {
        borderColor: '#F3F4F6',  // light mode border color
        labelColor: '#6B7280',    // light mode label color
        opacityFrom: 0.45,
        opacityTo: 0
    };

    return {
        chart: {
            height: '80%',  // Set to 100% of its container height
            type: 'area',
            fontFamily: 'Inter, sans-serif',
            foreColor: mainChartColors.labelColor,
            toolbar: {
                show: false
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                enabled: true,
                opacityFrom: mainChartColors.opacityFrom,
                opacityTo: mainChartColors.opacityTo
            }
        },
        dataLabels: {
            enabled: false
        },
        tooltip: {
            style: {
                fontSize: '14px',
                fontFamily: 'Inter, sans-serif',
            },
        },
        grid: {
            show: true,
            borderColor: mainChartColors.borderColor,
            strokeDashArray: 1,
            padding: {
                left: 35,
                bottom: 15
            }
        },
        series: [
            {
                name: 'Pembaca Aktif',
                data: [120, 140, 130, 155, 165, 145, 160], // Replace with actual Pembaca Aktif data
                color: '#1A56DB'
            },
            {
                name: 'Pembaca Aktif (periode sebelumnya)',
                data: [110, 120, 115, 140, 155, 130, 150], // Replace with actual previous period data
                color: '#FDBA8C'
            }
        ],
        markers: {
            size: 5,
            strokeColors: '#ffffff',
            hover: {
                size: undefined,
                sizeOffset: 3
            }
        },
        xaxis: {
            categories: ['01 Feb', '02 Feb', '03 Feb', '04 Feb', '05 Feb', '06 Feb', '07 Feb'], // Update as necessary
            labels: {
                style: {
                    colors: [mainChartColors.labelColor],
                    fontSize: '14px',
                    fontWeight: 500,
                },
            },
            axisBorder: {
                color: mainChartColors.borderColor,
            },
            axisTicks: {
                color: mainChartColors.borderColor,
            },
            crosshairs: {
                show: true,
                position: 'back',
                stroke: {
                    color: mainChartColors.borderColor,
                    width: 1,
                    dashArray: 10,
                },
            },
        },
        yaxis: {
            labels: {
                style: {
                    colors: [mainChartColors.labelColor],
                    fontSize: '14px',
                    fontWeight: 500,
                },
                formatter: function (value) {
                    return value + ' Pembaca'; // Adjust to reflect "Pembaca Aktif"
                }
            },
        },
        legend: {
            fontSize: '14px',
            fontWeight: 500,
            fontFamily: 'Inter, sans-serif',
            labels: {
                colors: [mainChartColors.labelColor]
            },
            itemMargin: {
                horizontal: 10
            }
        },
        responsive: [
            {
                breakpoint: 1024,
                options: {
                    chart: {
                        height: '350px',  // Adjust height for smaller screens
                    },
                    xaxis: {
                        labels: {
                            show: false
                        }
                    }
                }
            },
            {
                breakpoint: 768,
                options: {
                    chart: {
                        height: '250px',  // Further reduce height on mobile devices
                    }
                }
            }
        ]
    };
}

if (document.getElementById('main-chart')) {
    const chart = new ApexCharts(document.getElementById('main-chart'), getMainChartOptions());
    chart.render();
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('new-readers-chart')) {

            const getReadersChartOptions = () => {
                let readersChartColors = {}

                // Check if the theme is dark
                if (document.documentElement.classList.contains('dark')) {
                    readersChartColors = {
                        backgroundBarColors: ['#374151', '#374151', '#374151', '#374151', '#374151', '#374151', '#374151']
                    };
                } else {
                    readersChartColors = {
                        backgroundBarColors: ['#E5E7EB', '#E5E7EB', '#E5E7EB', '#E5E7EB', '#E5E7EB', '#E5E7EB', '#E5E7EB']
                    };
                }

                return {
                    series: [{
                        name: 'Pembaca Baru',
                        data: [
                            { x: '01 Feb', y: 170 },
                            { x: '02 Feb', y: 180 },
                            { x: '03 Feb', y: 164 },
                            { x: '04 Feb', y: 145 },
                            { x: '05 Feb', y: 194 },
                            { x: '06 Feb', y: 170 },
                            { x: '07 Feb', y: 155 },
                        ]
                    }],
                    labels: ['01 Feb', '02 Feb', '03 Feb', '04 Feb', '05 Feb', '06 Feb', '07 Feb'],
                    chart: {
                        type: 'bar',
                        height: '200px',
                        width: '100%',  // Adjusted to 100% for responsiveness or set a fixed value like '400px'
                        foreColor: '#4B5563',
                        fontFamily: 'Inter, sans-serif',
                        toolbar: {
                            show: false
                        }
                    },
                    theme: {
                        monochrome: {
                            enabled: true,
                            color: '#1A56DB'
                        }
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '40%',
                            borderRadius: 3,
                            colors: {
                                backgroundBarColors: readersChartColors.backgroundBarColors,
                                backgroundBarRadius: 3
                            },
                        },
                        dataLabels: {
                            hideOverflowingLabels: false
                        }
                    },
                    xaxis: {
                        floating: false,
                        labels: {
                            show: false
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        style: {
                            fontSize: '14px',
                            fontFamily: 'Inter, sans-serif'
                        }
                    },
                    states: {
                        hover: {
                            filter: {
                                type: 'darken',
                                value: 0.8
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    yaxis: {
                        show: false
                    },
                    grid: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: false
                    }
                };
            };

            // Initialize the chart with the generated options
            const chart = new ApexCharts(document.getElementById('new-readers-chart'), getReadersChartOptions());
            chart.render();

            // Re-initialize when toggling dark mode
            document.addEventListener('dark-mode', function () {
                chart.updateOptions(getReadersChartOptions());
            });
        }
    });
</script>


<script>
    if (document.getElementById('news-chart')) {
        const options = {
            colors: ['#1A56DB', '#FDBA8C'], // Chart colors
            series: [
                {
                    name: 'Articles',
                    color: '#1A56DB',
                    data: [
                        { x: '01 Oct', y: 50 },
                        { x: '02 Oct', y: 75 },
                        { x: '03 Oct', y: 65 },
                        { x: '04 Oct', y: 80 },
                        { x: '05 Oct', y: 95 },
                        { x: '06 Oct', y: 110 },
                        { x: '07 Oct', y: 120 },
                    ]
                }
            ],
            chart: {
                type: 'bar',
                height: '140px',
                fontFamily: 'Inter, sans-serif',
                foreColor: '#4B5563',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '90%',
                    borderRadius: 3
                }
            },
            tooltip: {
                shared: false,
                intersect: false,
                style: {
                    fontSize: '14px',
                    fontFamily: 'Inter, sans-serif'
                }
            },
            states: {
                hover: {
                    filter: {
                        type: 'none', // Removed dark mode filter
                        value: 0
                    }
                }
            },
            stroke: {
                show: true,
                width: 3,
                colors: ['transparent']
            },
            grid: {
                show: false, // Hides the grid
            },
            dataLabels: {
                enabled: false // Hides data labels
            },
            legend: {
                show: false // Hides the legend
            },
            xaxis: {
                floating: false,
                labels: {
                    show: false // Hides the x-axis labels
                },
                axisBorder: {
                    show: false // Hides x-axis border
                },
                axisTicks: {
                    show: false // Hides x-axis ticks
                },
            },
            yaxis: {
                show: false // Hides y-axis completely
            },
            fill: {
                opacity: 1
            }
        };

        const chart = new ApexCharts(document.getElementById('news-chart'), options);
        chart.render();
    }
</script>
<script>
    const getKategoriChartOptions = () => {
	let kategoriChartColors = {};

	if (document.documentElement.classList.contains('dark')) {
		kategoriChartColors = {
			strokeColor: '#1f2937'
		};
	} else {
		kategoriChartColors = {
			strokeColor: '#ffffff'
		}
	}

	return {
		series: [40, 35, 25], // Adjust these values as needed for your kategori data
		labels: ['Kategori A', 'Kategori B', 'Kategori C'], // Update the categories as per your data
		colors: ['#16BDCA', '#FDBA8C', '#1A56DB'], // You can change these colors if needed
		chart: {
			type: 'donut',
			height: 400,
			fontFamily: 'Inter, sans-serif',
			toolbar: {
				show: false
			},
		},
		responsive: [{
			breakpoint: 430,
			options: {
				chart: {
					height: 300
				}
			}
		}],
		stroke: {
			colors: [kategoriChartColors.strokeColor]
		},
		states: {
			hover: {
				filter: {
					type: 'darken',
					value: 0.9
				}
			}
		},
		tooltip: {
			shared: true,
			followCursor: false,
			fillSeriesColor: false,
			inverseOrder: true,
			style: {
				fontSize: '14px',
				fontFamily: 'Inter, sans-serif'
			},
			x: {
				show: true,
				formatter: function (_, { seriesIndex, w }) {
					const label = w.config.labels[seriesIndex];
					return label;
				}
			},
			y: {
				formatter: function (value) {
					return value + '%';
				}
			}
		},
		grid: {
			show: false
		},
		dataLabels: {
			enabled: false
		},
		legend: {
			show: false
		},
	};
}

if (document.getElementById('chart-donut-kategori')) {
	const chart = new ApexCharts(document.getElementById('chart-donut-kategori'), getKategoriChartOptions());
	chart.render();

	// init again when toggling dark mode
	document.addEventListener('dark-mode', function () {
		chart.updateOptions(getKategoriChartOptions());
	});
}

</script>