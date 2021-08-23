<?php
include 'configDB.php';

require_once __DIR__ . '/vendor/autoload.php';

use Predis\Client; //menggunakan library predis(untuk menjalankan redis di php)
use Elasticsearch\ClientBuilder; //menggunakan library Elasticsearch(untuk menjalankan elasticsearch di php)
require 'vendor/autoload.php'; // elasticsearch
$client = ClientBuilder::create()->build();

$regex = "/^(\S+) (\S+) (\S+) (\[\d+\/\S+\ \+\d+\]) (\"\S+\s+\S+\s+\S+\") (\d+\d+) (\d+\d+) (\"\S+\") (\S+) (\S+) (\S+) (\S+) (\S+)/";

$fn = fopen("data_log/nginx.log", "r");

while (!feof($fn)) {
    $result = fgets($fn);
    $data = [];
    $parsing = preg_match_all("/^(\S+) (\S+) (\S+) (\[\d+\/\S+\ \+\d+\]) (\"\S+\s+\S+\s+\S+\") (\d+\d+) (\d+\d+) (\"\S+\") (\S+) (\S+) (\S+) (\S+) (\S+)/", $result, $data);
    $ip_address = $data[1][0] ?? "";
    $date = $data[4][0] ?? "";
    $method = $data[5][0] ?? "";
    $status = $data[6][0] ?? "";
    $ping = $data[7][0] ?? "";
    $site = $data[8][0] ?? "";
    $rt = $data[9][0] ?? "";
    $uct = $data[10][0] ?? "";
    $uht = $data[11][0] ?? "";
    $urt = $data[12][0] ?? "";
    $gz = $data[13][0] ?? "";

    $data = [$ip_address, $date, $method, $status, $ping, $site, $rt, $uct, $uht, $urt, $gz];
    $json = json_encode($data, JSON_PRETTY_PRINT);

    $redis = new Client();

    $redis->set('json', $json);

    $user = $redis->get('json');

    $decode = json_decode($user);

    // $json = json_encode("$ip_address $date $method $status $ping $site $rt $uct $uht $urt $gz");
    // $ip_address = json_encode($ip_address);
    // $date = json_encode($date);
    // $method = json_encode($method);
    // $status = json_encode($status);
    // $ping = json_encode($ping);
    // $site = json_encode($site);
    // $rt = json_encode($rt);
    // $uct = json_encode($uct);
    // $uht = json_encode($uht);
    // $urt = json_encode($urt);
    // $gz = json_encode($gz);

    $params = [
        'index' => 'tester',
        'type' => 'log_nginx',
        'id' => 3,
        'body' => [
            "ip_address" => $decode[0],
            "date" => $decode[1],
            "method" => $decode[2],
            "status" => $decode[3],
            "ping" => $decode[4],
            "site_url" => $decode[5],
            "rt" => $decode[6],
            "uct" => $decode[7],
            "uht" => $decode[8],
            "urt" => $decode[9],
            "gz" => $decode[10]
        ]
    ];

    $response = $client->index($params);


    // $query = "INSERT INTO tbl_json (id, ip_address, date_file, method, status_ms, ping, site_url, rt, uct, uht, urt, gz ) VALUES ('', '$decode[0]', '$decode[1]', '$decode[2]','$decode[3]','$decode[4]','$decode[5]','$decode[6]','$decode[7]','$decode[8]','$decode[9]','$decode[10]')";
    // mysqli_query($koneksi, $query);

    // echo 'data masuk ke database';

    $redis->del('json');
    echo 'data berhasil dihapus dari redis';
}

fclose($fn);
