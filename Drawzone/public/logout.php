<?php
session_start();
session_unset();
session_destroy();

header("Location: /DrawZone/public/index.php");
exit;