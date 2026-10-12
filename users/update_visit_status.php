<?php
include '../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_login']) || $statusko === "Deactivate") {
    $_SESSION['error'] = 'You are not authorized to do this.';
    echo json_encode(['success' => false, 'message' => 'Not authorized.']);
    exit;
}

$requester = trim($_POST['requester_id'] ?? '');
$landlord  = trim($_POST['landlord_id'] ?? '');
$date      = trim($_POST['date_sched'] ?? '');
$time      = trim($_POST['time_sched'] ?? '');
$status    = trim($_POST['status'] ?? '');

// allowed transitions: new status => required current status
$allowed = [
    'Approved'    => 'Pending',
    'Disapproved' => 'Pending',
    'Completed'   => 'Approved',
];

// message shown to the user (via alerts.php) when the update works
$success_msg = [
    'Approved'    => 'Visit request approved successfully.',
    'Disapproved' => 'Visit request disapproved successfully.',
    'Completed'   => 'Visit request marked as completed successfully.',
];

// Tell exactly what is missing instead of a generic error
$missing = [];
if ($requester === '') $missing[] = 'requester_id';
if ($landlord  === '') $missing[] = 'landlord_id';
if ($date      === '') $missing[] = 'date_sched';
if ($time      === '') $missing[] = 'time_sched';
if ($missing) {
    $msg = 'Invalid request. Missing: ' . implode(', ', $missing);
    $_SESSION['error'] = $msg;
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}
if (!isset($allowed[$status])) {
    $msg = 'Invalid request. Unknown status: ' . $status;
    $_SESSION['error'] = $msg;
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}
$required = $allowed[$status];

// Only the owner of the property can change the status.
$stmt = $conn->prepare("
  UPDATE `request_visit` rv
  INNER JOIN `landlord` l ON l.landlord_id = rv.landlord_id
  SET rv.status = ?
  WHERE rv.user_id = ?
    AND rv.landlord_id = ?
    AND rv.date_sched = ?
    AND rv.time_sched = ?
    AND l.user_id = ?
    AND rv.status = ?
");
$stmt->bind_param("sssssss", $status, $requester, $landlord, $date, $time, $user_id_login, $required);
$stmt->execute();

$ok = $stmt->affected_rows > 0;
$stmt->close();

if ($ok) {
    // alerts.php reads this on the next page load and shows the success toast
    $_SESSION['success'] = $success_msg[$status];
    echo json_encode(['success' => true, 'message' => $success_msg[$status]]);
} else {
    $msg = 'Nothing changed. The request may no longer be ' . $required . ', or you are not the owner of this property.';
    $_SESSION['error'] = $msg;
    echo json_encode(['success' => false, 'message' => $msg]);
}