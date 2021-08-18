<?php
if (isset($_POST["export"])) {

    include 'configDB.php';

    header('Content-Type: text/csv; charset=utf-8');

    header('Content-Disposition: attachment; filename=nginx.csv');

    $output = fopen("php://output", "w");

    fputcsv($output, array('ID', 'key_json', 'value_json'));

    $query = "SELECT * from tbl_json ORDER BY id ASC limit 100";

    $result = mysqli_query($koneksi, $query);

    while ($row = mysqli_fetch_assoc($result)) {

        fputcsv($output, $row);
    }

    fclose($output);
}
