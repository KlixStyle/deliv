<?php
$ENV = parse_ini_file(".env");
$database = new mysqli($ENV["CONNECTION"], $ENV["USER"], $ENV["PASSWORD"],$ENV["DATABASE"]);
$search = $_GET["search"] ?? "";
$items = $database->query("SELECT ".$ENV["TABLE_ITEMS"].".Product_Name,".$ENV["TABLE_ITEMS"].".Manufacturer_ID,".$ENV["TABLE_ITEMS"].".Gross_Price,".$ENV["TABLE_ITEMS"].".Product_ID FROM ".$ENV["TABLE_ITEMS"].";")->fetch_all();
?>
<div class="flex flex-auto flex-wrap justify-center gap-y-1 gap-x-1">
<?php foreach ($items as $item) { ?>
        <div class="pico-background-grey-100" style="border-radius:7px">
            <div style="margin:0.1rem;text-align:center">
                <h3><?php echo $item[0] ?></h3>
            </div>
            <div style="margin:0.1rem;text-align:center">
                <small><?php echo $item[2] ?></small>
            </div>
            <div class="flex flex-auto justify-center gap-x-1" style="margin:0.1rem">
                <?php
                $choice = 0;
                if (isset($_GET["value"])) {
                    $choice = $_GET["value"];
                }
                switch ($choice) {
                    case 1:
                        echo '<a href="index.php?value=1&change=' . $item[3] . '"><button class="flex-auto p-1" style="width:100%;font-size:0.85rem">Ändern</button></a> 
                    <a href="index.php?value=1&del=' . $item[3] . '"><button class="flex-auto p-1" style="width:100%;font-size:0.85rem">Löschen</button></a> ';
                        break 1;
                } ?>
                </div>
            </div>
        <?php }?>
    </div>