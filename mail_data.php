<?php
header('Content-Type: application/json');
if (!isset($_COOKIE['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Please login"
        ]);
        exit;
    }
include "connection.php";
$user_id = $_COOKIE['user_id'];
$received_sql = "SELECT mail.*, user.name, user.email
                 FROM mail
                 JOIN user
                 ON mail.sender_id = user.id
                 WHERE mail.receiver_id = '$user_id'
                 ORDER BY mail.created_at DESC";

$received_result = mysqli_query($conn, $received_sql);
$received_html = "";
if (mysqli_num_rows($received_result) > 0) {
    while ($mail = mysqli_fetch_assoc($received_result)) {
        $received_html .= '
        <div class="mail-box">
            <p>
                <b>From:</b>
                ' .$mail['name'] . '
            </p>
            <p>
                <b>Email:</b>
                ' .$mail['email'] . '
            </p>
            <p>
                <b>Message:</b>
                ' .$mail['message'] . '
            </p>
        ';

        if (!empty($mail['attachment'])) {
            $file = urlencode($mail['attachment']);
            $size = $mail['size'] ?? 0;
            $received_html .= '
            <p>
                <b>Attachment:</b>
                <a href="uploads/' . $file . '"
                   target="_blank">
                    View File
                </a>
            </p>
            <p><b>Size:</b>' . $size . ' bytes</p>';
        }
        $received_html .= '
            <p>
                <b>Date:</b>
                ' . $mail['created_at'] . '
            </p>
        </div>
        ';
    }
} else {
    $received_html = "<p>No received mail.</p>";
}
$sent_sql = "SELECT mail.*, user.name, user.email
             FROM mail
             JOIN user
             ON mail.receiver_id = user.id
             WHERE mail.sender_id = '$user_id'
             ORDER BY mail.created_at DESC";

$sent_result = mysqli_query($conn, $sent_sql);
$sent_html = "";
if (mysqli_num_rows($sent_result) > 0) 
    {
    while ($mail = mysqli_fetch_assoc($sent_result)) 
        {
            $sent_html .= '
            <div class="mail-box">
                <p>
                    <b>To:</b>
                    ' .$mail['name'] . '
                </p>
                <p>
                    <b>Email:</b>
                    ' . $mail['email'] . '
                </p>
                <p>
                    <b>Message:</b>
                    ' . $mail['message'] . '
                </p>
            ';
            if (!empty($mail['attachment'])) {
                $file = urlencode($mail['attachment']);
                $size = $mail['size'] ?? 0;
                $sent_html .= '
                <p>
                    <b>Attachment:</b>
                    <a href="uploads/' . $file . '"
                    target="_blank">
                        View File
                    </a>
                </p>
                <p>
                    <b>Size:</b>
                    ' . $size . ' bytes
                </p>
                ';
            }
            $sent_html .= '
                <p>
                    <b>Date:</b>
                    ' . $mail['created_at'] . '
                </p>
            </div>
            ';
        }
} else {
    $sent_html = "<p>No sent mail.</p>";
}
echo json_encode([
    "status" => "success",
    "received" => $received_html,
    "sent" => $sent_html
]);
?>