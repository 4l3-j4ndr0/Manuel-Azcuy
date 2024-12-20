<?php
session_start();
session_destroy();
header("Location: ../../login/index.html"); // Redirige al login al cerrar sesión
exit();
