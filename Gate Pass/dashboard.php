<?php if ($_SESSION['role'] === 'student'): ?>
    <!-- Student content here -->
<?php elseif ($_SESSION['role'] === 'warden'): ?>
    <!-- Warden content here -->
<?php endif; ?>
