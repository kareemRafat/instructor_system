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
  <title>Login</title>
  <!-- Tailwind CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

  <!-- Flowbite CDN -->
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="w-full max-w-sm bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Login</h2>
    <form class="max-w-sm mx-auto" action="functions/Auth/login.php" method="POST">
      <div class="mb-5"> <label for="username" class="block mb-2 text-sm font-medium text-gray-900"> username</label>
        <input autofocus name="username" type="text" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="" required />
      </div>
      <div class="mb-5"> <label for="password" class="block mb-2 text-sm font-medium text-gray-900"> password</label>
        <input name="password" type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
      </div>
      <div class="flex items-start mb-5">
        <div class="flex items-center h-5">
          <input id="remember" name="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-blue-300" />
        </div>
        <label for="remember" class="ms-2 text-sm font-medium text-gray-900">Remember me</label>
      </div>
      <button type="submit" class="w-full text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center">Submit</button>
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