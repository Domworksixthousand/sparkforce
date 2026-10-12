<?php
include '../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_login']) || $statusko === "Deactivate") {
    echo json_encode(['success' => false, 'message' => 'Not authorized.']);
    exit;
}

// Fail without touching $_SESSION['error']: the JS already shows the message,
// and a session message would pop up again after the next reload.
function rc_fail($msg) {
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

$req_id   = trim($_POST['req_id'] ?? '');
$landlord = trim($_POST['landlord_id'] ?? '');
$comment  = trim($_POST['comment'] ?? '');
$rate     = (int)($_POST['rate'] ?? 0);

// Say exactly which field is missing
$missing = [];
if ($req_id === '')         $missing[] = 'req_id';
if ($landlord === '')       $missing[] = 'landlord_id';
if ($rate < 1 || $rate > 5) $missing[] = 'rate (select 1-5 stars)';
if ($comment === '')        $missing[] = 'comment';
if ($missing) {
    rc_fail('Missing: ' . implode(', ', $missing));
}

if (mb_strlen($comment) > 255) {
    $comment = mb_substr($comment, 0, 255);
}

// 1) The request must be MINE, for THIS landlord, and Completed
$chk = $conn->prepare("
    SELECT 1 FROM `request_visit`
    WHERE req_id = ? AND user_id = ? AND landlord_id = ? AND status = 'Completed'
    LIMIT 1
");
$chk->bind_param("iss", $req_id, $user_id_login, $landlord);
$chk->execute();
$chk->store_result();
$valid = $chk->num_rows > 0;
$chk->close();

if (!$valid) {
    rc_fail('You can only rate your own completed visit requests.');
}

// 2) Already rated? same req_id + landlord_id + user -> not allowed
$dup = $conn->prepare("
    SELECT 1 FROM `comment_rate`
    WHERE req_id = ? AND landlord_id = ? AND user_id = ?
    LIMIT 1
");
$dup->bind_param("iss", $req_id, $landlord, $user_id_login);
$dup->execute();
$dup->store_result();
$already = $dup->num_rows > 0;
$dup->close();

if ($already) {
    rc_fail('You have already commented and rated this visit.');
}

// 3) Save ($datetoday and $timetoday1 come from config.php)
$stmt = $conn->prepare("
    INSERT INTO `comment_rate` (req_id, comment, rate, landlord_id, user_id, date_rated, time_rated)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("isissss", $req_id, $comment, $rate, $landlord, $user_id_login, $datetoday, $timetoday1);

try {
    $stmt->execute();
    $ok = $stmt->affected_rows > 0;
} catch (mysqli_sql_exception $e) {
    $ok = false; // e.g. unique key violation from a double-click
}
$stmt->close();

if ($ok) {
    $_SESSION['success'] = 'Thank you! Your comment and rating were saved.';
    echo json_encode(['success' => true, 'message' => 'Saved.']);
} else {
    rc_fail('Could not save your comment and rating. You may have already rated this visit.');
}