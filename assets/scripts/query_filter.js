document.addEventListener("DOMContentLoaded", function () {
   $(document).ready(function(){
    function updateTable() {
        var searchValue = $(".search_data").val().toLowerCase();
        var limit = $("#entries_limit").val();
        
       
        $("#no-data-row").remove();

        var visibleCount = 0;
        var totalMatched = 0;

        $(".myTable .data-row").each(function() {
            var rowText = $(this).text().toLowerCase();
            
          
            if (rowText.indexOf(searchValue) > -1) {
                totalMatched++;
                
              
                if (limit === "All" || visibleCount < parseInt(limit)) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide(); 
                }
            } else {
                $(this).hide(); 
            }
        });

     if (totalMatched === 0) {
            $(".myTable").append(`
                <tr id="no-data-row">
                    <td colspan="5" class="p-0 border-none">
                        <div class="flex flex-col items-center justify-center py-16 px-5 text-center text-base-content/40 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-50"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <p class="text-sm m-0">No property requests found.</p>
                        </div>
                    </td>
                </tr>
            `);
        }
    }

  
    $(".search_data").on("keyup", function() {
        updateTable();
    });

   
    $("#entries_limit").on("change", function() {
        updateTable();
    });

   
    updateTable();
});
 
});

//hget user poerty requests
document.addEventListener("DOMContentLoaded", function () {
   $(document).ready(function(){
    function updateTable() {
        var searchValue = $(".search_data1").val().toLowerCase();
        var limit = $("#entries_limit1").val();
        
       
        $("#no-data-row1").remove();

        var visibleCount = 0;
        var totalMatched = 0;

        $(".myTable1 .data-row1").each(function() {
            var rowText = $(this).text().toLowerCase();
            
          
            if (rowText.indexOf(searchValue) > -1) {
                totalMatched++;
                
              
                if (limit === "All" || visibleCount < parseInt(limit)) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide(); 
                }
            } else {
                $(this).hide(); 
            }
        });

      
        if (totalMatched === 0) {
             $(".myTable1").append(`
                <tr id="no-data-row1">
                    <td colspan="6" class="p-0 border-none">
                        <div class="flex flex-col items-center justify-center py-16 px-5 text-center text-base-content/40 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-50"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <p class="text-sm m-0">No Amenties found.</p>
                        </div>
                    </td>
                </tr>
            `);
        }
    }

  
    $(".search_data1").on("keyup", function() {
        updateTable();
    });

   
    $("#entries_limit1").on("change", function() {
        updateTable();
    });

   
    updateTable();
});
 
});

//get
document.addEventListener("DOMContentLoaded", ()=> {

        function fetchData() {
        $.ajax({
            url: "request_account_fetch.php",
            method: "GET",
            success: function(data) {
                $(".request_data").html(data);
            },
            error: function() {
                $(".request_data").html("Error loading data");
            }
        });
    }


    setInterval(fetchData, 2000);


    fetchData();

});

//get
    //get
document.addEventListener("DOMContentLoaded", ()=> {

        function fetchData() {
        $.ajax({
            url: "get_notifications.php",
            method: "GET",
            success: function(data) {
                $(".noti_data").html(data);
            },
            error: function() {
                $(".noti_data").html("Error loading data");
            }
        });
    }


    setInterval(fetchData, 2000);


    fetchData();

});
//get_report
 document.addEventListener("DOMContentLoaded", ()=> {
    function fetchData() {
        $.ajax({
            url: "fetch_report.php",
            method: "GET",
            success: function(data) {
                $(".report_data").html(data);  
            },
            error: function() {
                $(".report_data").html("Error loading data");
            }
        });
    }
    setInterval(fetchData, 2000);
    fetchData();
});


    //get
document.addEventListener("DOMContentLoaded", ()=> {

        function fetchData() {
        $.ajax({
            url: "fetch_property_request.php",
            method: "GET",
            success: function(data) {
                $(".request_properties").html(data);
            },
            error: function() {
                $(".request_properties").html("Error loading data");
            }
        });
    }


    setInterval(fetchData, 2000);


    fetchData();

});
/*
document.addEventListener("DOMContentLoaded", function () {

    const CARDS_PER_PAGE = 8; 

    const $grid        = $("#pr_grid");
    const allCards      = $grid.find(".pr-card-item").toArray();
    const $search        = $("#pr_search");
    const $emptyState    = $("#pr_empty_state");
    const $resultCount   = $("#pr_result_count");
    const $paginationInfo     = $("#pr_pagination_info");
    const $paginationControls = $("#pr_pagination_controls");
    const $paginationWrap      = $("#pr_pagination");

    const BTN_BASE   = "min-w-[36px] h-9 px-2.5 rounded-md border text-sm font-semibold inline-flex items-center justify-center transition cursor-pointer";
    const BTN_IDLE    = "bg-white border-gray-200 text-gray-700 hover:border-[#0fab9e] hover:text-[#0d9488] hover:bg-teal-50";
    const BTN_ACTIVE  = "bg-gradient-to-b from-[#0fab9e] to-[#0d9488] border-[#0d9488] text-white";
    const BTN_DISABLED = "opacity-40 cursor-not-allowed";

    let currentPage = 1;

    // If there were zero results server-side, there are no .pr-card-item elements at all.
    if (allCards.length === 0) {
        $emptyState.removeClass("hidden").addClass("flex");
        $paginationWrap.addClass("hidden");
        return;
    }

    function getFilteredCards() {
        const term = $search.val().toLowerCase().trim();
        if (!term) return allCards;
        return allCards.filter(function (card) {
            return $(card).text().toLowerCase().indexOf(term) !== -1;
        });
    }

    function render() {
        const filtered = getFilteredCards();
        const totalCards = filtered.length;
        const totalPages = Math.max(1, Math.ceil(totalCards / CARDS_PER_PAGE));

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        allCards.forEach(function (card) { $(card).hide(); });

        if (totalCards === 0) {
            $emptyState.removeClass("hidden").addClass("flex");
            $resultCount.text("");
            $paginationWrap.addClass("hidden");
            return;
        }

        $emptyState.addClass("hidden").removeClass("flex");
        $paginationWrap.removeClass("hidden");

        const start = (currentPage - 1) * CARDS_PER_PAGE;
        const end = Math.min(start + CARDS_PER_PAGE, totalCards);
        const pageCards = filtered.slice(start, end);
        pageCards.forEach(function (card) { $(card).show(); });

        $resultCount.text(totalCards + (totalCards === 1 ? " result" : " results"));
        $paginationInfo.text("Showing " + (start + 1) + "\u2013" + end + " of " + totalCards);

        renderPaginationControls(totalPages);
    }

    function renderPaginationControls(totalPages) {
        $paginationControls.empty();

        const $prev = $('<button type="button" aria-label="Previous page">&laquo;</button>')
            .addClass(BTN_BASE + " " + BTN_IDLE);
        if (currentPage === 1) {
            $prev.addClass(BTN_DISABLED).prop("disabled", true);
        }
        $prev.on("click", function () { goToPage(currentPage - 1); });
        $paginationControls.append($prev);

        const pageNumbers = getPageNumbers(currentPage, totalPages);
        pageNumbers.forEach(function (p) {
            if (p === "...") {
                $paginationControls.append('<span class="w-9 h-9 inline-flex items-center justify-center text-gray-300 text-sm">&hellip;</span>');
            } else {
                const $btn = $('<button type="button">' + p + '</button>').addClass(BTN_BASE);
                if (p === currentPage) {
                    $btn.addClass(BTN_ACTIVE);
                } else {
                    $btn.addClass(BTN_IDLE);
                }
                $btn.on("click", function () { goToPage(p); });
                $paginationControls.append($btn);
            }
        });

        const $next = $('<button type="button" aria-label="Next page">&raquo;</button>')
            .addClass(BTN_BASE + " " + BTN_IDLE);
        if (currentPage === totalPages) {
            $next.addClass(BTN_DISABLED).prop("disabled", true);
        }
        $next.on("click", function () { goToPage(currentPage + 1); });
        $paginationControls.append($next);
    }

    function getPageNumbers(current, total) {
        const delta = 1;
        const pages = [];
        for (let i = 1; i <= total; i++) {
            if (i === 1 || i === total || (i >= current - delta && i <= current + delta)) {
                pages.push(i);
            } else if (pages[pages.length - 1] !== "...") {
                pages.push("...");
            }
        }
        return pages;
    }

    function goToPage(page) {
        currentPage = page;
        render();
        $('html, body').animate({ scrollTop: $grid.offset().top - 100 }, 200);
    }

    $search.on("keyup", function () {
        currentPage = 1;
        render();
    });

    render();
});*/

document.addEventListener("DOMContentLoaded", function () {
   $(document).ready(function () {
    $('.filter-btn').on('click', function () {
        const filter = $(this).data('filter');

        // switch active button style
        $('.filter-btn').removeClass('bg-emerald-600 text-white').addClass('bg-[#f3f2ee] text-black');
        $(this).removeClass('bg-[#f3f2ee] text-black').addClass('bg-emerald-600 text-white');

        let visibleCount = 0;

        if (filter === 'all') {
            $('.rental-card').show();
            visibleCount = $('.rental-card').length;
        } else {
            $('.rental-card').hide();
            const matched = $('.rental-card[data-type="' + filter + '"]');
            matched.show();
            visibleCount = matched.length;
        }

        // show/hide "no results" message
        if (visibleCount === 0) {
            $('#noResultsMsg').show();
        } else {
            $('#noResultsMsg').hide();
        }
    });
}); 
});


//property request
/*
document.addEventListener("DOMContentLoaded", function () {

    const ROWS_PER_PAGE = 8; 

    const $tbody      = $("#pr_table_body");

    // ✅ IDAGDAG ITO — huwag tumakbo itong script kung walang table sa page na ito
    if ($tbody.length === 0) {
        return;
    }

    const allRows      = $tbody.find("tr.pr-row").toArray();
    const $search       = $("#pr_search");
    const $emptyState   = $("#pr_empty_state");
    const $resultCount  = $("#pr_result_count");
    const $paginationInfo     = $("#pr_pagination_info");
    const $paginationControls = $("#pr_pagination_controls");
    const $paginationWrap      = $("#pr_pagination");

    // Base classes for pagination number/nav buttons (Tailwind only)
    const BTN_BASE   = "min-w-[36px] h-9 px-2.5 rounded-md border text-sm font-semibold inline-flex items-center justify-center transition cursor-pointer";
    const BTN_IDLE    = "bg-white border-gray-200 text-gray-700 hover:border-[#0fab9e] hover:text-[#0d9488] hover:bg-teal-50";
    const BTN_ACTIVE  = "bg-gradient-to-b from-[#0fab9e] to-[#0d9488] border-[#0d9488] text-white";
    const BTN_DISABLED = "opacity-40 cursor-not-allowed";

    let currentPage = 1;

    function getFilteredRows() {
        const term = $search.val().toLowerCase().trim();
        if (!term) return allRows;
        return allRows.filter(function (row) {
            return $(row).text().toLowerCase().indexOf(term) !== -1;
        });
    }

    function render() {
        const filtered = getFilteredRows();
        const totalRows = filtered.length;
        const totalPages = Math.max(1, Math.ceil(totalRows / ROWS_PER_PAGE));

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        allRows.forEach(function (row) { $(row).hide(); });

        if (totalRows === 0) {
            $emptyState.removeClass("hidden").addClass("flex");
            $resultCount.text("");
            $paginationWrap.addClass("hidden");
            return;
        }

        $emptyState.addClass("hidden").removeClass("flex");
        $paginationWrap.removeClass("hidden");

        const start = (currentPage - 1) * ROWS_PER_PAGE;
        const end = Math.min(start + ROWS_PER_PAGE, totalRows);
        const pageRows = filtered.slice(start, end);
        pageRows.forEach(function (row) { $(row).show(); });

        $resultCount.text(totalRows + (totalRows === 1 ? " result" : " results"));
        $paginationInfo.text("Showing " + (start + 1) + "\u2013" + end + " of " + totalRows);

        renderPaginationControls(totalPages);
    }

    function renderPaginationControls(totalPages) {
        $paginationControls.empty();

        // Prev
        const $prev = $('<button type="button" aria-label="Previous page">&laquo;</button>')
            .addClass(BTN_BASE + " " + BTN_IDLE);
        if (currentPage === 1) {
            $prev.addClass(BTN_DISABLED).prop("disabled", true);
        }
        $prev.on("click", function () { goToPage(currentPage - 1); });
        $paginationControls.append($prev);

        const pageNumbers = getPageNumbers(currentPage, totalPages);
        pageNumbers.forEach(function (p) {
            if (p === "...") {
                $paginationControls.append('<span class="w-9 h-9 inline-flex items-center justify-center text-gray-300 text-sm">&hellip;</span>');
            } else {
                const $btn = $('<button type="button">' + p + '</button>').addClass(BTN_BASE);
                if (p === currentPage) {
                    $btn.addClass(BTN_ACTIVE);
                } else {
                    $btn.addClass(BTN_IDLE);
                }
                $btn.on("click", function () { goToPage(p); });
                $paginationControls.append($btn);
            }
        });

        // Next
        const $next = $('<button type="button" aria-label="Next page">&raquo;</button>')
            .addClass(BTN_BASE + " " + BTN_IDLE);
        if (currentPage === totalPages) {
            $next.addClass(BTN_DISABLED).prop("disabled", true);
        }
        $next.on("click", function () { goToPage(currentPage + 1); });
        $paginationControls.append($next);
    }

    function getPageNumbers(current, total) {
        const delta = 1;
        const pages = [];
        for (let i = 1; i <= total; i++) {
            if (i === 1 || i === total || (i >= current - delta && i <= current + delta)) {
                pages.push(i);
            } else if (pages[pages.length - 1] !== "...") {
                pages.push("...");
            }
        }
        return pages;
    }

    function goToPage(page) {
        currentPage = page;
        render();
        $('html, body').animate({ scrollTop: $("#pr_table").offset().top - 100 }, 200);
    }

    $search.on("keyup", function () {
        currentPage = 1;
        render();
    });

    render();
});*/

//my_property.php script
 document.addEventListener("DOMContentLoaded", function(){
 $(function () {
    var $search = $('#pr_search');
    var $grid = $('#pr_grid');
    var $cards = $grid.find('.pr-card-item');
    var $count = $('#pr_result_count');
    var $empty = $('#pr_empty_state');
    var total = $cards.length;

    function filterCards() {
      var q = $search.val().toLowerCase().trim();
      var visible = 0;

      $cards.each(function () {
        var $card = $(this);
        var name = ($card.attr('data-name') || '').toLowerCase();

        if (name.indexOf(q) !== -1) {
          $card.show();
          visible++;
        } else {
          $card.hide();
        }
      });

      $count.text(
        q === ''
          ? total + ' item' + (total !== 1 ? 's' : '')
          : visible + ' of ' + total + ' item' + (total !== 1 ? 's' : '')
      );

      if (visible === 0) {
        $empty.removeClass('hidden').addClass('flex');
      } else {
        $empty.addClass('hidden').removeClass('flex');
      }
    }

    $search.on('input keyup', filterCards);

    // initialize count on page load
    filterCards();
  });
 });

//property_request.php
 document.addEventListener("DOMContentLoaded", function(){
    $(document).ready(function() {
    const rowsPerPage = 5; // Adjust number of rows per page if needed
    let currentPage = 1;

    function filterAndPagination() {
        const query = $('#pr_search1').val().toLowerCase().trim();
        const $rows = $('#pr_table_body1 tr.pr-row');
        let matchedRows = [];

        // Filter rows based on search input (checks property type, name, address, etc.)
        $rows.each(function() {
            const rowText = $(this).text().toLowerCase();
            if (rowText.includes(query)) {
                matchedRows.push(this);
            }
        });

        const totalMatches = matchedRows.length;
        const totalPages = Math.ceil(totalMatches / rowsPerPage) || 1;

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        // Hide all rows initially
        $rows.hide();

        // Show only rows for the current page
        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        
        for (let i = startIndex; i < endIndex && i < totalMatches; i++) {
            $(matchedRows[i]).show();
        }

        // Toggle empty state message
        if (totalMatches === 0) {
            $('#pr_empty_state').removeClass('hidden').addClass('flex');
            $('#pr_pagination1').hide();
        } else {
            $('#pr_empty_state').removeClass('flex').addClass('hidden');
            $('#pr_pagination1').show();
        }

        // Update result count info
        if (totalMatches > 0) {
            $('#pr_result_count').text(`Showing ${startIndex + 1}-${Math.min(endIndex, totalMatches)} of ${totalMatches} entries`);
        } else {
            $('#pr_result_count').text('0 entries found');
        }

        // Render Pagination Controls
        renderPaginationControls(totalPages);
    }

    function renderPaginationControls(totalPages) {
        const $controls = $('#pr_pagination_controls');
        $controls.empty();

        if (totalPages <= 1) {
            $('#pr_pagination_info').text('');
            return;
        }

        $('#pr_pagination_info').text(`Page ${currentPage} of ${totalPages}`);

        // Previous Button
        const prevDisabled = currentPage === 1 ? 'opacity-50 cursor-not-allowed' : '';
        $controls.append(`<button class="px-3 py-1 text-sm border rounded bg-white hover:bg-gray-100 ${prevDisabled}" id="pr_prev_btn">Prev</button>`);

        // Page Number Buttons
        for (let i = 1; i <= totalPages; i++) {
            const activeClass = i === currentPage ? 'bg-[#0fab9e] text-white border-[#0fab9e]' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-100';
            $controls.append(`<button class="px-3 py-1 text-sm border rounded page-num-btn ${activeClass}" data-page="${i}">${i}</button>`);
        }

        // Next Button
        const nextDisabled = currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : '';
        $controls.append(`<button class="px-3 py-1 text-sm border rounded bg-white hover:bg-gray-100 ${nextDisabled}" id="pr_next_btn">Next</button>`);
    }

    // Event Listeners
    $('#pr_search1').on('keyup input', function() {
        currentPage = 1; // Reset to page 1 on search
        filterAndPagination();
    });

    $(document).on('click', '.page-num-btn', function() {
        currentPage = parseInt($(this).attr('data-page'));
        filterAndPagination();
    });

    $(document).on('click', '#pr_prev_btn', function() {
        if (currentPage > 1) {
            currentPage--;
            filterAndPagination();
        }
    });

    $(document).on('click', '#pr_next_btn', function() {
        const $rows = $('#pr_table_body1 tr.pr-row').filter(function() {
            const query = $('#pr_search1').val().toLowerCase().trim();
            return $(this).text().toLowerCase().includes(query);
        });
        const totalPages = Math.ceil($rows.length / rowsPerPage);
        
        if (currentPage < totalPages) {
            currentPage++;
            filterAndPagination();
        }
    });

    // Initial call on page load
    filterAndPagination();
});
 });

//admin reports.php
 document.addEventListener("DOMContentLoaded", function(){
$(document).ready(function() {
    $('#pr_search2').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        var visibleCount = 0;

        $('#pr_table_body .pr-row2').filter(function() {
            var rowText = $(this).text().toLowerCase();
            var isMatch = rowText.indexOf(value) > -1;
            $(this).toggle(isMatch);
            if (isMatch) {
                visibleCount++;
            }
        });

        // Update result count display kung mayroon man
        $('#pr_result_count').text(visibleCount + ' report(s) found');

        // Ipakita o itago ang empty state kung walang makita
        if (visibleCount === 0) {
            $('#pr_empty_state').removeClass('hidden').addClass('flex');
        } else {
            $('#pr_empty_state').removeClass('flex').addClass('hidden');
        }
    });
});
});