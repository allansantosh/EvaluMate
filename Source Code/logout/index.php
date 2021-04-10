<?php
session_start();
unset($_SESSION['sess_user']);
unset($_SESSION['guestid']);
unset($_SESSION['event_id']);
session_destroy();
header('Location: ../');
?>