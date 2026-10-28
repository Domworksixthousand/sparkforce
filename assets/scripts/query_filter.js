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
                        <td colspan="3" class="p-0 border-none">
                            <div class="flex flex-col items-center justify-center py-16 px-5 text-center text-base-content/40 w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-50"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                <p class="text-sm m-0">No Amenities found.</p>
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

        // Run on page load
        updateTable();
    });
});







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

document.addEventListener("DOMContentLoaded", ()=> {

        function fetchData() {
        $.ajax({
            url: "request_count.php",
            method: "GET",
            success: function(data) {
                $("#get_request_count").html(data);
            },
            error: function() {
                $("#get_request_count").html("Error loading data");
            }
        });
    }


    setInterval(fetchData, 2000);


    fetchData();

});


/*
  document.addEventListener("DOMContentLoaded", function(){
      async function pollData() {
        try {
            let response = await fetch('noti_messages.php'); 
            let result = await response.text();
            
            document.getElementById('get_mess').innerHTML = result;
*/