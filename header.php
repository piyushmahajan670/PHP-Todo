<header class="navbar ">
  <nav class="w-full border-b-[1px] bg-blue-100 border-b-white text-black transition-all duration-150 z-50 py-2">
    <div class="container mx-auto md:px-8 xl:px-14 px-4 lg:px-10 2xl:px-10">
      <div class="flex items-center justify-between py-[10px] 2xl:py-[12px]">
        <div>
          <a href="/">
            <h3 class="text-lg font-medium">Login Register</h3>
          </a>
        </div>
        <div class="hidden lg:flex text-black gap-10 items-center">
        <?php
          if(!isset($_SESSION['username'])) {
            echo ' 
              <a href="login.php" class="block py-2 font-medium">
                Login
              </a>
              <a href="register.php" class="block py-2 font-medium">
                Register
              </a>
            ';
          } else {
            echo ' 
              <a href="todo.php" class="block py-2 font-medium">
                Todos
              </a>
            ';
          }
        ?>
          <a href="user.php" class="bg-red-400 text-white p-2 text-sm rounded font-medium hover:opacity-85">
            Users Info
          </a>
        </div>
      </div>
    </div>
  </nav>
</header>