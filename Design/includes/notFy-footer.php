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
    // login toaster
    <?php if (isset($_SESSION['login'])): ?>
        notyf.success({
            message: 'Welcome to Your Dashboard',
            duration: 3000,
            icon:'<i class="fa-solid fa-code"></i>' ,
            dismissible: false,
            position: {
                y: 'top',
                x: 'center'
            },
            background : '#39a0ca',
            className : 'text-white !shadow-none border-l-8 border-slate-500 font-semibold text-center tracking-wider'
        });
    <?php endif; ?>
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
unset($_SESSION['login']);

?>