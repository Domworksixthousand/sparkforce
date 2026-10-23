document.addEventListener("DOMContentLoaded", function () {
   $(document).ready(function(){
    function updateTable() {
        var searchValue = $(".search_data").val() ? $(".search_data").val().toLowerCase() : "";
        var limit = $("#entries_limit").val();
        $("#no-data-row").remove();
        var visibleCount = 0;
        var totalMatched = 0;
        var dataRows = $(".myTable .data-row");
        if (dataRows.length === 0) {
            // Check if PHP already rendered an empty state
            if ($(".myTable tr").length === 0 || $(".myTable").text().toLowerCase().indexOf("all caught up") !== -1 || $(".myTable").text().toLowerCase().indexOf("no account") !== -1) {
                return;
            }
            var noMsg = "No account requests found.";
            if (window.location.pathname.indexOf("request_accounts") === -1) {
                noMsg = "No property requests found.";
            }
            $(".myTable").append('<tr id="no-data-row"><td colspan="6" class="p-0 border-none"><div class="flex flex-col items-center justify-center py-16 px-5 text-center text-base-content/40 w-full"><svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-50"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg><p class="text-sm m-0">' + noMsg + '</p></div></td></tr>');
            return;
        }
        dataRows.each(function() {
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
            var noMsg = "No property requests found.";
            if (window.location.pathname.indexOf("request_accounts") !== -1) {
                noMsg = "No account requests found.";
            }
            $(".myTable").append('<tr id="no-data-row"><td colspan="6" class="p-0 border-none"><div class="flex flex-col items-center justify-center py-16 px-5 text-center text-base-content/40 w-full"><svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-50"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg><p class="text-sm m-0">' + noMsg + '</p></div></td></tr>');
        }
    }
    $(".search_data").on("keyup", function() { updateTable(); });
    $("#entries_limit").on("change", function() { updateTable(); });
    updateTable();
   });
});
