// Function to filter chat items based on search input and toggle no-results state
    function filterChats() {
        const searchText = $('#search_input').val().toLowerCase();
        let visibleCount = 0;

        $('.user-item').each(function() {
            const userName = $(this).find('.user-name').text().toLowerCase();
            if (userName.includes(searchText)) {
                $(this).removeClass('hidden');
                visibleCount++;
            } else {
                $(this).addClass('hidden');
            }
        });

        // Show or hide the "No User Found" message container depending on matches
        if (searchText !== '' && visibleCount === 0) {
            $('#noSearchResults').removeClass('hidden').addClass('flex');
        } else {
            $('#noSearchResults').removeClass('flex').addClass('hidden');
        }
    }

    function fetchData() {
        fetch('fetch_users_mess.php')
            .then(response => response.text())
            .then(html => {
                document.getElementById('users_list').innerHTML = html;
                // Re-apply filter so that active searches persist even after the 3-second poll refresh
                filterChats();
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    $(document).ready(function() {
        // Trigger filter on every keystroke
        $('#search_input').on('keyup input', function() {
            filterChats();
        });

        // Poll every 3 seconds (3000 milliseconds)
        setInterval(fetchData, 3000);

        // Run immediately on page load
        fetchData();
    });