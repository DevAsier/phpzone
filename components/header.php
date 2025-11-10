<header class="main-header">
  <div class="logo-area">
    <img src="public/img/logo.png" alt="Logo" class="logo">
    <h1 class="brand-name">PHPZone</h1>
  </div>

  <div class="user-menu">
  <span class="user-name"><?php echo $_SESSION['usuario']; ?></span>
  <img src="public/img/user.jpeg" alt="Usuario" class="user-icon" id="userToggle">
  <div class="dropdown" id="userDropdown">
    <a href="logout.php">Cerrar sesión</a>
  </div>
</div>

</header>
