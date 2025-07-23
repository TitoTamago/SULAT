<?php include('../includes/init.php');
session_start();
$user_id = $_SESSION['user_id'] ?? 0;

?>

<ul class="conversation-list px-3" data-simplebar style="max-height: 538px" id="chat-box">
    <?php
$stmt = $pdo->query("SELECT * FROM tbl_conversation WHERE user_id = $user_id ORDER BY created_at ASC");
while ($row = $stmt->fetch()) {?>
    <li class="clearfix odd">
        <div class="conversation-text">
            <div class="ctext-wrap">
                <p><?=htmlspecialchars($row['input_text'])?></p>
            </div>
        </div>
    </li>
    <li class="clearfix">
        <div class="conversation-text">
            <div class="ctext-wrap">
                <p><?=htmlspecialchars($row['ai_response'])?></p>
            </div>
        </div>
    </li>
    <?php }?>
</ul>