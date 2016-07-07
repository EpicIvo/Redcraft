<?php
session_start();
session_destroy();
header("Location: /Redcraft/html/index.html");
exit;
