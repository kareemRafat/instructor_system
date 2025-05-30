<?php
session_start();

if (isset($_SESSION['user_id'])) {
  redirectBasedOnRole($_SESSION['role']);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="icon/sah.png" type="image/x-icon">
  <title>Login</title>
  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  <!-- tailwind cli output -->
  <link rel="stylesheet" href="css/output.css">
  <!-- Flowbite CDN -->
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap');

    h1 {
      font-family: "Poetsen One", sans-serif;
    }
  </style>
</head>

<body class="bg-gray-50 md:bg-gray-50 flex flex-col items-center h-screen md:justify-center">
  <!-- mobile image -->
  <div class="w-full max-w-sm rounded-lg p-6 mt-5 md:mt-0 md:p-0">
    <img class="md:hidden" src="images/login2.svg" alt="">
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:items-center md:bg-white md:shadow px-7 w-full max-w-5xl rounded-lg">
    <!-- img div -->
    <div class="hidden md:block w-full max-w-md justify-self-center">
      <img src="images/Designer.svg" alt="">
    </div>
    <!-- form div -->
    <div class="w-full max-w-full md:max-w-md bg-gray-50 md:bg-white rounded-lg py-3 md:my-20">
      <h2 class="text-2xl font-bold text-left md:text-center text-gray-700 mb-2">Login</h2>
      <p class="text-base font-semibold text-left md:text-center text-gray-700 mb-6">Please sign in to Continue</p>
      <form class="max-w-full mx-auto" action="functions/Auth/login.php" method="POST">
        <div class="mb-5"> <label for="username" class="block mb-2 text-sm font-medium text-gray-900"> username</label>
          <input autofocus name="username" type="text" id="username" class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-zinc-500 md:focus:border-blue-500 block w-full p-2.5" placeholder="" required />
        </div>
        <div class="mb-5"> <label for="password" class="block mb-2 text-sm font-medium text-gray-900"> password</label>
          <input name="password" type="password" id="password" class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-zinc-500 md:focus:border-blue-500 block w-full p-2.5" required />
        </div>
        <div class="flex items-start mb-5">
          <div class="flex items-center h-5">
            <input id="remember" name="remember" type="checkbox" class="w-4 h-4 text-zinc-600 md:text-blue-600 bg-gray-100 border border-gray-300 rounded-sm focus:ring-gray-300 md:focus:ring-blue-500 focus:ring-2" />
          </div>
          <label for="remember" class="ms-2 text-sm font-medium text-gray-900">Remember me</label>
        </div>
        <button type="submit" class="w-full text-white bg-zinc-800 md:bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center">Submit</button>
      </form>

      <div>
        <?php
        if (isset($_SESSION['error'])) {
          echo $_SESSION['error'];
          unset($_SESSION['error']);
        }
        ?>
      </div>
    </div>
  </div>
</body>

</html>

<?php

function redirectBasedOnRole($role): void
{
  if ($role == 'admin' || $role == 'instructor') {
    header("Location: ../../index.php");
  } elseif ($role == 'cs' or $role == 'cs-admin') {
    header("Location: ../../lectures.php");
  }

  exit();
}

?>