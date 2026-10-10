<?php 
// views/editor.php
include 'views/header.php'; 
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Editing: <code><?= htmlspecialchars($filename) ?></code></h5>
        <div>
            <a href="index.php?path=<?= urlencode($current_path) ?>" class="btn btn-sm btn-outline-secondary"><i class="fa fa-arrow-left"></i> Back</a>
            <button form="edit-form" type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
        </div>
    </div>
    
    <div class="card-body p-0">
        <form id="edit-form" action="index.php" method="POST">
            <input type="hidden" name="action" value="save_file">
            <input type="hidden" name="file" value="<?= htmlspecialchars($filepath) ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']) ?>">
            
            <textarea name="content" class="form-control border-0 rounded-0" rows="25" style="font-family: monospace; resize: vertical;"><?= htmlspecialchars($file_content) ?></textarea>
        </form>
    </div>
</div>

<?php include 'views/footer.php'; ?>