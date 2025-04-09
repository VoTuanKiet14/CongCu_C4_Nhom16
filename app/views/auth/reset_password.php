<?php
// Redirect to the new password reset system
header("Location: " . BASE_URL . "/index.php?controller=PasswordReset&action=request");
exit;
?>