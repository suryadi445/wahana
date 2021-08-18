<?php
include 'configDB.php';

require_once __DIR__ . '/vendor/autoload.php';

use Predis\Client;

$logFile = file_get_contents('nginx.log');

$regex = "/(\S+) (\S+) (\S+) (\[\d+\/\S+\ \+\d+\]) (\"\S+\s+\S+\s+\S+\") (\d+\d+) (\d+\d+) (\"\S+\") (\S+) (\S+) (\S+) (\S+) (\S+)/";

if (preg_match_all($regex, $logFile, $matches)) {

    $json = json_encode($matches[0], JSON_FORCE_OBJECT);
}

try {
    $redis = new Client();

    $redis->set('json', $json);

    $user = $redis->get('json');

    echo $user . "\n";

    $decode = json_decode($user);
    foreach ($decode as $key => $value) {
        $query = "INSERT INTO tbl_json (id, key_json, value_json) VALUES ('', '$key', '$value')";
        mysqli_query($koneksi, $query);
    }
    $redis->del('json');
} catch (Exception $e) {
    die($e->getMessage());
}
