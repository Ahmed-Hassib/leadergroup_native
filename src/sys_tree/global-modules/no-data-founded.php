<?php
// prepare flash session variables
$_SESSION['flash_message'] = 'NO DATA FOUNDED';
$_SESSION['flash_message_icon'] = 'bi-exclamation-triangle-fill';
$_SESSION['flash_message_class'] = 'danger';
$_SESSION['flash_message_status'] = false;
// redirect to the previous page
redirectHome(null, 'back', 0);