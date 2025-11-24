<?php
    session_start();
    // Destroy session
    if(session_destroy()) {
        // Redirecting To Login Page
        unset($_SESSION['firstname']);
        header("Location: http://localhost/Admin_Dash-board/Pages/signin.html");
    }
?>