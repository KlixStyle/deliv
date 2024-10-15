<?php
$ENV = parse_ini_file(".env");
$database = new mysqli($ENV["CONNECTION"], $ENV["USER"], $ENV["PASSWORD"],$ENV["DATABASE"]);
$search = $_GET["search"] ?? "";

$items = $database->query("
Select *
FROM ".$ENV["TABLE_ORDERS"]." as ord
INNER JOIN ".$ENV["TABLE_PACKAGES"]." as pac
ON ord.Order_ID = pac.Order_ID
INNER JOIN ".$ENV["TABLE_LOCATIONS"]." as loc
ON  pac.Location_ID = loc.Location_ID 
INNER JOIN ".$ENV["TABLE_ITEMS"]." as ite
ON ord.Product_ID = ite.Product_ID
INNER JOIN ".$ENV["TABLE_MANUFACTURERS"]." as man
ON ite.Manufacturer_ID = man.Manufacturer_ID
INNER JOIN ".$ENV["TABLE_AREAS"]." as are
ON loc.ZIP = are.ZIP
")->fetch_all();
?>
<div class="gap-y-1 pico-background-grey-100 overflow-auto  " style="border-radius:7px">
    <table>
        <?php foreach ($items as $item) { ?>
            <tr style="font-size:smaller">
                <td>
                    <small><?php echo $item[0] ?></small>
                </td>
                <td>
                    <small><?php echo $item[3] ?></small>
                </td>
                <td>
                    <small><?php echo $item[9].' '.$item[10] ?></small>
                </td>
                <td>
                    <small><?php echo $item[11] ?></small>
                </td>
                <td>
                    <small><?php echo $item[19] ?></small>
                </td>
                <td>
                    <small><?php echo $item[13] ?></small>
                </td>
                <td>
                    <small><?php echo $item[4] ?></small>
                </td>
                <td>
                    <small><?php echo $item[15] ?></small>
                </td>
                <td>
                    <small><?php echo $item[17] ?></small>
                </td>
                <td>
                    <?php echo '<a href="index.php?value=2&del=' . $item[0] . '"><button class="flex-auto p-1" style="width:100%;font-size:0.85rem">Löschen</button></a>' ?> 
                </td>
            </tr>
        <?php }?>
    </table>
</div>