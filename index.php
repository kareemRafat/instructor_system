<?php
include_once 'Helpers/bootstrap.php';
include_once 'Design/includes/header.php';
include_once 'Design/includes/navbar.php';

$errors = $_SESSION['errors'] ?? [];

?>

<div class="max-w-7xl mx-auto md:pt-6 md:px-6">
  <!-- card -->
  <div class="flex flex-col items-center justify-center md:my-8 text">
    <div class="w-full max-w-full md:max-w-[30rem] bg-white rounded-lg md:shadow-md p-6">
      <h2 class="text-2xl font-bold text-center text-gray-700 mb-4">
        <span class="underline underline-offset-5 decoration-8 decoration-blue-400"><?= ucwords(USERNAME) ?>'s</span> Groups
      </h2>
      <form action="functions/Lectures/insert_lecture.php" method="POST" class="space-y-6">
        <input type="hidden" name="date" id="currentDate">
        <script>
          const now = new Date();
          const formatted = now.toLocaleString('sv-SE').replace('T', ' '); // 'YYYY-MM-DD HH:mm:ss'
          document.getElementById('currentDate').value = formatted;
        </script>
        <div class="mb-6">
          <label for="group" class="block text-sm font-medium text-gray-700 mb-2">Group</label>
          <select id="group" name="group" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option value="">Select Group</option>
          </select>
          <?php if (isset($errors['group'])) {
            echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
              $errors['group'] .
              '</div>';
          }
          ?>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <div class="flex items-center gap-2 bg-amber-50 px-4 py-2 rounded-lg border border-amber-200 w-full  shadow-sm">
            <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg><span id="start-date" class="text-amber-700 font-medium text-sm">Group Start Date</span>
          </div>
          <div class="flex items-center gap-2 bg-teal-50 px-4 py-2 rounded-lg border border-teal-200 w-full  shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-teal-500">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"></path>
            </svg>
            <span id="end-date" class="text-teal-700 font-medium text-sm">Expected End Date</span>
          </div>
        </div>
        <div class="mb-6">
          <label for="track" class="block text-sm font-medium text-gray-700 mb-2">Track</label>
          <select id="track" name="track" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option value="">Select Track</option>
            <option value="1">HTML</option>
            <option value="2">CSS</option>
            <option value="3">JavaScript</option>
            <option value="4">PHP</option>
            <option value="5">MySQL</option>
            <option value="6">Project</option>
          </select>
          <?php if (isset($errors['track'])) {
            echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
              $errors['track'] .
              '</div>';
          }
          ?>
        </div>
        <!-- <div class="mb-6">
          <label for="message" class="block mb-2 text-sm font-medium text-gray-900">Comment</label>
          <textarea name="comment-test" id="message" rows="2" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Write your comment here ..."></textarea>
          <?php if (isset($errors['comment-test'])) {
            echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
              $errors['comment-test'] .
              '</div>';
          }
          ?>
        </div> -->
        <!-- dropdown list for lectures with search -->
        <div class="relative w-full">
          <label class="block mb-2 text-sm font-medium text-gray-900">Comment</label>
          <div class="relative">
            <input type="text"
              id="comment-input"
              name="comment"
              autocomplete="off"
              class="block w-full appearance-none p-2.5 pr-10 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 placeholder:text-gray-900"
              placeholder="Search for Lectures">

            <!-- Arrow Icon -->
            <div class="pointer-events-none absolute inset-y-0  right-2 flex items-center font-bold">
              <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="4" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
            <!-- search close icon -->
            <div id="clear-search" class="hidden cursor-pointer text-blue-500 absolute inset-y-0 right-8 flex items-center font-bold">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
              </svg>
            </div>
          </div>
          <!-- small list -->
          <ul id="lecture-list"
            style="-webkit-overflow-scrolling: touch; margin: 0;"
            class="
              hidden
              md:absolute md:bottom-0 md:mt-1 md:w-full md:max-h-48  md:border md:border-gray-300 md:rounded-md md:shadow-md
              md:bg-white
              md:overflow-y-auto
              md:hidden
              fixed inset-0 md:inset-auto z-50 bg-white overflow-y-auto flex flex-col p-8 max-h-full
              md:flex-none md:p-0
            ">
            <li class="text-left px-3 py-1 text-gray-500 font-semibold cursor-default">Select Track First</li>
          </ul>

        </div>

        <?php if (isset($errors['comment'])) {
          echo '<div class="p-2 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert"> ' .
            $errors['comment'] .
            '</div>';
        }
        ?>
        <button type="submit" class="w-full px-6 py-3 text-white bg-zinc-800 md:bg-blue-600 rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 transition duration-200 font-medium shadow-sm">Submit</button>
      </form>
    </div>
    <div class="w-full max-w-md mr-11 md:mr-0 mt-3 text-xl text-blue-600 text-right">
      <i class="fa-solid fa-fire mr-1"></i>
      <a class="hover:underline font-semibold" href="https://tinyurl.com/createivo-track" target="_blank">Track</a>
    </div>
  </div>
</div>

<?php
unset($_SESSION['errors']);
?>

<script type="module" src="dist/main.js"></script>
<script type="module" src="dist/main-dropdown.js"></script>

<?php
include_once "Design/includes/notFy-footer.php";
?>

</body>