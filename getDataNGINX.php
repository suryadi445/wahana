<?php
include 'configDB.php';

$nginx = mysqli_query($koneksi, "SELECT * FROM tbl_json");
