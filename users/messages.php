<?php
  include '../config.php'; 
  if(!isset($_SESSION['user_login'])){
    echo "<script>location.href='../index.php';</script>";
  }
    $data_stat = 'viewed';
    $update = $conn->prepare("UPDATE messages SET `status` = ? WHERE  `receiver_id` = ? ");
    $update->bind_param("ss", $data_stat, $user_id_login);
    $update->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
    <script src="./../assets/scripts/jquery.js"></script>
</head>
<body class="bg-base-100">

  <div class="drawer lg:drawer-open ">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col ">
      <nav class="navbar w-full bg-base-300 px-4 bg-[#0fab9e]">
        <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5 text-white"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </label>
        <div class="flex-1 font-bold text-white">Messages</div>
      </nav>
      <div class="py-6">
        <!--main content-->
        <main>
            <section class="my-container">
            <!-- Outer Card Container -->
            <div class="bg-base-100 border border-base-200 rounded-2xl shadow-sm overflow-hidden">
                
                <!-- Header & Search Bar -->
                <div class="p-4 border-b border-base-200 space-y-3 bg-base-100/50 backdrop-blur">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold tracking-tight text-base-content">Chats</h1>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                    <button type="button" onclick="location.href='start_chat.php'" class="btn btn-circle text-white btn-success btn-xs text-base-content/70 hover:text-base-content">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"/></svg>
                    </button>
                    </div>
                </div>

                <!-- Search Input with ID added -->
                <label class="input input-sm input-bordered flex items-center gap-2 rounded-full bg-base-200/50 focus-within:bg-base-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="search_input" class="grow text-xs" placeholder="Search Messenger..." />
                </label>
                </div>

                <!-- Message List -->
              <div id="users_list" class="divide-y divide-base-200/50 p-6 sm:p-10">
                 
                </div>
                <div id="noSearchResults" class="hidden flex-col items-center justify-center py-12 px-4 text-center">
                    <div class="w-16 h-16 mb-4 rounded-full bg-base-200/70 flex items-center justify-center text-base-content/40">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-round"><path d="M17.925 20.056a6 6 0 0 0-11.851.001"/><circle cx="12" cy="11" r="4"/><circle cx="12" cy="12" r="10"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-500">No User Found!</h3>
                </div>
            </div>
            </section>
        </main>
      </div>
    </div>
    <div class="drawer-side z-40">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <?php include 'drawer.php'; ?>
    </div>
  </div>

  <script src="./../assets/scripts/index.js"></script>
  <script src="./../assets/scripts/query_filter.js"></script>
</body>
</html>
<script>
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
</script>