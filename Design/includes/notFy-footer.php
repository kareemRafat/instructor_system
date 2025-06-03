
<script>
    // notFy
    const notyf = new Notyf({
        duration: 7000,
        dismissible: true,
        position: {
            x: 'right',
            y: 'top',
        },
    });
</script>

<script>
    // success toaster
    <?php if (isset($_SESSION['success'])): ?>
        notyf.success(`<?= $_SESSION['success'] ?>`);
    <?php endif; ?>
    // error toaster
    <?php if (isset($_SESSION['errors'])): ?>
    <?php foreach ($_SESSION['errors'] as $error): ?>
        notyf.error(`<?= $error ?>`);
    <?php endforeach; ?>
    <?php endif; ?>
</script>


<?php

unset($_SESSION['success']);
unset($_SESSION['old']);
unset($_SESSION['errors']);

?>