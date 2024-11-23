<?php
function renderCategoryHeader($categoryName) {
    echo "<div class='bg-black text-white py-4 sticky top-0'>
            <div class='container mx-auto px-6'>
                <span class='text-lg flex items-center'>
                    <span class='bg-[#FF3232] w-2 h-8 inline-block mr-2'></span> 
                    <span class='ml-2'>{$categoryName}</span>
                </span>
            </div>
          </div>";
}
?>