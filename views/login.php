<?php 
// views/login.php
include 'views/header.php'; 
?>

<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4"><?= htmlspecialchars(APP_TITLE) ?></h3>
                
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="login">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']) ?>">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mt-3">Sign In</button>
                </form>
                
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>