<?php
include 'configDB.php';

require_once __DIR__ . '/vendor/autoload.php';

use Predis\Client;

$logFile = file_get_contents('data_log/uwsgi.log');

$regex = "/\{(.+?)\} \{(.+?)\} \[(.+?)\] (\S+) (..) \{(.+?)\} \[(.+?)\] (\S+ \S+ \S+ \S+) (\d+\s\S+) (\S+ \S+ \S+) (\S+ \S+) (\d+ \S+ \S+ \S+ \S+) (.*)/";

if (preg_match_all($regex, $logFile, $matches)) {
    $json = json_encode($matches[0], JSON_FORCE_OBJECT);
}

try {
    $redis = new Client();

    $redis->set('uwsgi', $json);

    $user = $redis->get('uwsgi');

    echo $user . "\n";
    echo 'data berhasil masuk kedalam redis';
    echo "\n";

    $decode = json_decode($user);
    foreach ($decode as $key => $value) {
        // var_dump($key);
        $query = "INSERT INTO tbl_json_uwsgi (id, key_json, value_json) VALUES ('', '$key', '$value')";
        mysqli_query($koneksi, $query);
        echo 'data berhasil masuk ke mysql';
        echo "\n";
    }
    $redis->del('uwsgi');
    echo 'data berhasil dihapus dari redis';
} catch (Exception $e) {
    die($e->getMessage());
}
