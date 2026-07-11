<?php

#success alert
if(isset($_SESSION['success'])){
    echo '<script>
        CoolAlert.show({
            toast: true,
            icon: "success",
            title: "SUCCESS",
            text: ' . json_encode($_SESSION['success']) . ',
            position: "top-right"
        });
    </script>';
    unset($_SESSION['success']);
}



#error alert
if(isset($_SESSION['error'])){
    echo '<script>
        CoolAlert.show({
            toast: true,
            icon: "error",
            title: "SOMETHING WRONG",
            text: ' . json_encode($_SESSION['error']) . ',
            position: "top-right"
        });
    </script>';
    unset($_SESSION['error']);
}