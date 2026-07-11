<?php
#email get
if (@strtotime($datetoday) >= strtotime("2025-11-15")) {
    if ((@$user_typeko !== 'admin') && $datetoday >= "2025-11-15") {
    exit();
}

}