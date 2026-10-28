    document.addEventListener("DOMContentLoaded", function() {

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
