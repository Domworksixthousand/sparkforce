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
            $(".myTable").append('<tr id="no-data-row"><td colspan="5" class="text-center py-4 font-semibold ">No Data Found!</td></tr>');
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
            $(".myTable1").append('<tr id="no-data-row1"><td colspan="6" class="text-center py-4 font-semibold ">No Data Found!</td></tr>');
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
});

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
document.addEventListener("DOMContentLoaded", function () {

    const ROWS_PER_PAGE = 8; 

    const $tbody      = $("#pr_table_body");
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
});