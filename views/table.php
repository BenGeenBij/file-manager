<?php 
// views/table.php
include 'views/header.php'; 
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Current Directory: <code>/<?= htmlspecialchars($current_path) ?></code></h5>
        <div>
            <a href="#" class="btn btn-sm btn-outline-primary"><i class="fa fa-upload"></i> Upload</a>
            <a href="#" class="btn btn-sm btn-outline-success"><i class="fa fa-folder"></i> New Folder</a>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50%;">Name</th>
                    <th>Size</th>
                    <th>Modified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($current_path !== ''): ?>
                <tr>
                    <td colspan="4">
                        <a href="?path=<?= urlencode(dirname($current_path)) ?>"><i class="fa fa-level-up"></i> .. (Up a directory)</a>
                    </td>
                </tr>
                <?php endif; ?>

                <?php foreach ($folders as $folder): ?>
                <tr>
                    <td>
                        <a href="?path=<?= urlencode(trim($current_path . '/' . $folder, '/')) ?>" class="text-decoration-none text-dark">
                            <i class="fa fa-folder text-primary"></i> <?= htmlspecialchars($folder) ?>
                        </a>
                    </td>
                    <td>-</td>
                    <td>-</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php foreach ($files as $file): ?>
                <tr>
                    <td>
                        <a href="?action=view&file=<?= urlencode(trim($current_path . '/' . $file['name'], '/')) ?>" class="text-decoration-none text-dark">
                            <i class="fa fa-file-text-o text-secondary"></i> <?= htmlspecialchars($file['name']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($file['size']) ?></td>
                    <td><?= htmlspecialchars($file['modified']) ?></td>
                    <td>
                        <a href="?action=edit&file=<?= urlencode(trim($current_path . '/' . $file['name'], '/')) ?>" class="btn btn-sm btn-outline-secondary"><i class="fa fa-pencil"></i></a>
                        <a href="?action=delete&file=<?= urlencode(trim($current_path . '/' . $file['name'], '/')) ?>" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($folders) && empty($files)): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-3">Folder is empty</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'views/footer.php'; ?>