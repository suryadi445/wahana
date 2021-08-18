<?php
include '../getDataUWSGI.php'; // mengambil query dari database
$uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)); //uri segment

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <title>Converting data in json</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="view_nginx.php">NGINX</a>
                    </li>
                    <li class="nav-item">
                        <?php if ($uriSegments[3] == 'view_uwsgi.php') { ?>
                            <a class="nav-link active font-weight-bold" href="view_uwsgi.php">UWSGI</a>
                        <?php } else { ?>
                            <a class="nav-link" href="view_uwsgi.php">UWSGI</a>
                        <?php } ?>
                    </li>
                </ul>
            </div>
            <div class="container">
    </nav>
    <div class=" container mt-2">
        <h1 class="text-center">ALL Result</h1>
        <!-- export csv -->
        <form method="post" action="../exportCSV_UWSGI.php">
            <button type="submit" name="export" value="CSV Export" class="btn btn-primary mb-3">Convert CSV</button>
        </form>
        <!-- akhir export -->
        <div class="row mt-3 mb-5 card shadow p-3 mb-5 bg-white rounded">
            <h3 class="text-center text-capitalize">tabel data UWSGI</h3>
            <div class="col-md-12">
                <table class="table" id="table_uwsgi">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">No</th>
                            <th scope="col" class="text-center">Key</th>
                            <th scope="col">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if ($uwsgi->num_rows > 0) {
                            while ($row = $uwsgi->fetch_assoc()) {
                        ?>
                                <tr>
                                    <th class="text-center"><?= $no++ ?></th>
                                    <td class="text-center"><?= $row['key_json'] ?></td>
                                    <td><?= $row['value_json'] ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
</body>

</html>

<script>
    $(document).ready(function() {
        $('#table_uwsgi').DataTable();
    });
</script>