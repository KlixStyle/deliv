<?php
$ENV = parse_ini_file(".env");
$database = new mysqli($ENV["CONNECTION"], $ENV["USER"], $ENV["PASSWORD"], $ENV["DATABASE"]);
$con = mysqli_connect($ENV["CONNECTION"], $ENV["USER"], $ENV["PASSWORD"]);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["sell"])) {
        $_sell = $_POST["sell"];
        mysqli_query($database, "insert into " . $ENV["TABLE_SOLD"] . "(model_id, date) VALUES ($_sell, current_timestamp())");
        mysqli_query($database, "update " . $ENV["TABLE_MODELS"] . " set bestand = (bestand - 1) WHERE id = $_sell");
    }
}
$choice = 0;
if (isset($_GET["value"])) {
    $choice = $_GET["value"];
}
?>
<!DOCTYPE html>
<html data-theme="light">

<head>
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["sell"])) {
            echo '<meta http-equiv="refresh" content=0; URL="index.php?value=1">';
        }
    } ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width no-cache">
    <meta http-equiv="Pragma" content="">
    <title>Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2.0.6/css/pico.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2.0.6/css/pico.colors.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <header class="container gap-x-1">
        <nav class="justify-around">
            <ul>
                <li>
                <li><img src="logo.png" alt="" style="height:3.5rem;min-width:3.5rem"></li>
                <h1 style="font-size:1rem" class=""><a href="index.php"><b>FMR Product Delivery Systems</b></a></h1>
                </li>
            </ul>
            <ul class="flex-wrap grid gap-x-5">

                <?php
                if ($choice == 1) {
                    echo '<li class="grow"><a href="index.php"><kbd>Main Site</kbd></a></li>';
                    echo '<li class="grow"><a href="index.php?value=2"><kbd>Orders</kbd></a></li>';
                }
                if ($choice == 0) {
                    echo '<li class="grow"><a href="index.php?value=1"><kbd>Manage Products</kbd></a></li>';
                    echo '<li class="grow"><a href="index.php?value=2"><kbd>Orders</kbd></a></li>';
                }
                if ($choice == 2) {
                    echo '<li class="grow"><a href="index.php"><kbd>Main Site</kbd></a></li>';
                    echo '<li class="grow"><a href="index.php?value=1"><kbd>Manage Products</kbd></a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>
    <main class="container-fluid">
        <?php
        switch ($choice) {
            case 0:
                include "search/search.php";
                break 1;
            case 1:
                include "add.php";  
                include "search/search.php";
                break 1;
            case 2:
                include "orders.php";
                include "search/ordersearch.php";
                break 1;
        }
        ?>
    </main>

</body>

</html>