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
            $(".myTable").append('<tr id="no-data-row"><td colspan="4" class="text-center py-4 font-semibold ">No Data Found!</td></tr>');
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


//hget user poerty requests
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