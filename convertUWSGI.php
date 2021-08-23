<?php
include 'configDB.php';

require_once __DIR__ . '/vendor/autoload.php';

use Predis\Client; //menggunakan library predis(untuk menjalankan redis di php)
use Elasticsearch\ClientBuilder; //menggunakan library Elasticsearch(untuk menjalankan elasticsearch di php)
require 'vendor/autoload.php'; // elasticsearch
$client = ClientBuilder::create()->build();

$logFile = file_get_contents('data_log/uwsgi.log');

$regex = "/\{(.+?)\} \{(.+?)\} \[([^\]]*)\]/";
$matches = [];
$i = 0;
while ($logFile) {
    $result = preg_match_all($regex, $logFile, $matches);
    $address_space_usage = $matches[1] ?? "";
    $rss_usage = $matches[2] ?? "";
    $pid = $matches[3] ?? "";

    // $data = [$address_space_usage, $rss_usage, $pid];
    $array = [];
    $data =
        [
            "address_space_usage" => $address_space_usage,
            "rss_usage" => $rss_usage,
            "pid" => $pid
        ];

    $json = json_encode($data); //parsing data kedalam json

    $redis = new Client();

    $redis->set('uwsgi', 'nama'); //

    $uwsgi = $redis->get('uwsgi');

    $decode = json_decode($uwsgi, true);

    $address = $data['address_space_usage'];
    $rss     = $data['rss_usage'];
    $pid     = $data['pid'];

    // $query = "INSERT INTO tbl_json_uwsgi (id, address, rss, pid) VALUES ('', '$address[$i]', '$rss[$i]', '$pid[$i]')";
    // mysqli_query($koneksi, $query);

    $params = [
        'index' => 'index_uwsgi',
        'type' => 'log_nginx',
        'body'  => [
            "address_space_usage" => $address[$i],
            "rss_usage" => $rss[$i],
            "pid" => $pid[$i]
        ]
    ]; //data yang dikirim kedalam elastic search

    $response = $client->index($params); //membuat index pada elastic search


    // $redis->del('uwsgi');
    echo 'data berhasil dihapus dari redis';

    $i++;
}
// }
