<?php
require __DIR__ . '/../packages/configdb.php';
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

function loggedIn_verification() {
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $role = $_SESSION['role'] ?? 'student';
        if ($role === 'teacher') {
            $redirect = 'teacher_profile.php';
        } else {
            $redirect = 'student_profile.php';
        }
        header("Location: " . $redirect);
        exit;
    }
}

function loggedOut_verification() {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: login.php");
        exit;
    }
}