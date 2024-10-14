<?php
function renderCategoryHeader($categoryName) {
    echo "<div class='bg-black text-white py-4'>
            <div class='container mx-auto'>
                <span class='text-2xl font-semibold flex items-center'>
                    <span class='bg-[#FF3232] w-2 h-8 inline-block mr-2'></span> {$categoryName}
                </span>
            </div>
          </div>";
}
?>