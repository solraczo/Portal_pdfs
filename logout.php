<?php
session_start();
session_unset();
session_destroy();

// Redirigir al login del Portal_pdfs
header("Location: login.php");
exit();
