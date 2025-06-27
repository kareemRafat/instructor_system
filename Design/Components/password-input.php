<div class="password-wrapper">
    <div class="flex flex-row justify-between gap-1 mb-2 cursor-pointer">
        <label for="password" class="block text-sm font-medium text-gray-900">Password</label>
        <span id="togglePass" class="text-gray-400 flex gap-2 items-center text-sm font-semibold select-none">
            <span id="password-text">Show Password</span>
            <i id="eye-icon" class="fa-solid fa-eye mr-1"></i>
        </span>
    </div>

    <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="<?= !$loginPage ? 'Enter password if you want to Change it ...' : '' ?>">
    <?php if (isset($errors['password'])) {
        echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
            $errors['password'] .
            '</div>';
    }
    ?>
</div>

<script>
    var passwordInput = document.querySelector('input[type="password"]');
    var togglePass = document.getElementById('togglePass');
    var passText = document.getElementById('password-text');
    var icon = document.getElementById('eye-icon');

    togglePass.addEventListener('click' , (e)=> {
        if(passwordInput.type == 'password') {
            passwordInput.type = 'text';
            passText.innerText = 'Hide Password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-low-vision');
        } else {
            passwordInput.type = 'password';
            passText.innerText = 'Show Password';
            icon.classList.remove('fa-eye-low-vision');
            icon.classList.add('fa-eye');
        }
    })
    

</script>