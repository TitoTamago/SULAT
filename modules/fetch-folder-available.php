<?php
include('../includes/init.php');
session_start();

if (isset($_POST['file_ids'])) {
    $d_id_curr = $_POST['d_id_curr'];
    $fileIds = $_POST['file_ids'];
    $fileCount = count($fileIds);

    ?>

<div class="mb-3">
    <p>Select the destination folder for the <strong><?= $fileCount ?></strong> selected file(s):</p>
    <select id="targetFolder" class="form-select">
        <option value="">-- Choose Folder --</option>
        <?php 
        $stmt = $pdo->query("SELECT * FROM tbl_directory WHERE user_id = '".$_SESSION['user_id']."' AND d_id != $d_id_curr ORDER BY d_name");
        while ($row = $stmt->fetch()) {?>
        <option value="<?= $row['d_id'] ?>"><?= htmlspecialchars($row['d_name']) ?></option>
        <?php }?>
    </select>
</div>


<?php
} else {
    echo "<p class='text-danger'>No files selected.</p>";
}
?>