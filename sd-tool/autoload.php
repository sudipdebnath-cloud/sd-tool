<?php
include 'functions.php';
foreach (glob(SD_TOOL_DIR."classes/*.php") as $filename) {
    include $filename;
}
?>