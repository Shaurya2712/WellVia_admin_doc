<?php
include 'connection.php';
var_dump($conn);
if ($conn instanceof mysqli) {
    echo "Connection is a valid mysqli object.";
} else {
    echo "Connection is not a mysqli object.";
}
?>