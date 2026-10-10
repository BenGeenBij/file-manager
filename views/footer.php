<?php
// views/footer.php
if (!defined('APP_TITLE')) exit; 
?>
</div> <!-- End container-fluid -->

<!-- External JS -->
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Pass CSRF token to frontend JS for AJAX calls
    window.csrf = '<?= isset($_SESSION['token']) ? htmlspecialchars($_SESSION['token']) : '' ?>';
    
    // Auto-hide flash messages after 3 seconds
    $(document).ready(function() {
        setTimeout(function() {
            $('.message').fadeOut('slow');
        }, 3000);
    });
</script>

</body>
</html>