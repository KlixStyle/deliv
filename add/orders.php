<?php
$ENV = parse_ini_file(".env");
$con = mysqli_connect($ENV["CONNECTION"], $ENV["USER"], $ENV["PASSWORD"]);
$db = mysqli_select_db($con, $ENV["DATABASE"]);
$database = new mysqli($ENV["CONNECTION"], $ENV["USER"], $ENV["PASSWORD"], $ENV["DATABASE"]);
$id = mysqli_query($con, "select Order_ID from ".$ENV["TABLE_ORDERS"]." order by Order_ID desc limit 0,1;");
$id = $id->fetch_array();
if (isset($_GET["change"])) {
  $merker = $_GET["change"];
  $rs = mysqli_query($con, "
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
  where Singular_Order_ID=" . $merker);
  $changedata = mysqli_fetch_row($rs);
}
?>
<h1>Add Product</h1>
<form method="post">
  <fieldset>
    <?php
    if (isset($_GET["del"])) {
      mysqli_query($con, "delete from " . $ENV["TABLE_ORDERS"] . " where Singular_Order_ID=" . $_GET["del"]);
      echo '<meta http-equiv="refresh" content="0;URL=index.php?value=2">';
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $result1 = mysqli_query($con, "SELECT Product_ID FROM " . $ENV["TABLE_ITEMS"] . " WHERE Product_Name = '" . $_POST["product"] . "'");
      $_products = $result1->fetch_row()[0];
      $result2 = mysqli_query($con, "SELECT Location_ID FROM " . $ENV["TABLE_LOCATIONS"] . " WHERE Street = '" . $_POST["location"] . "'");
      $_locations = $result2->fetch_row()[0];
      $_amount = $_POST["amount"];
      $inputDate=$_POST['date'];
      $_date=date("Y-d-m",strtotime($inputDate));
      $_id = intval($id[0]) + 1;

      if (($_products != "") && ($_locations != "") && ($_amount != "") && ($_date != "")) {
        mysqli_query($con, "
          insert into " . $ENV["TABLE_ORDERS"] . "(
          Order_ID,
          Product_ID,
          Delivery_Date,
          Amount
          ) values (
          $_id,
          $_products,
          $_date,
          $_amount
        )");
        mysqli_query($con, "
          insert into " . $ENV["TABLE_PACKAGES"] . "(
          Order_ID,
          Location_ID
          ) values (
          $_id,
          $_locations
        )");
        echo '<meta http-equiv="refresh" content="0;URL=index.php?value=2">';
      } else {$invalid = true;}
    }

    ?>
    <?php $products = $database->query("
      SELECT 
      " . $ENV["TABLE_ITEMS"] . ".Product_ID,
      " . $ENV["TABLE_ITEMS"] . ".Product_Name
      FROM " . $ENV["TABLE_ITEMS"
     ])->fetch_all();; ?>
    <select name="product" aria-label="Select" required>
      <option <?php
      if (!isset($_GET["change"])) {
        echo 'selected';
      }
      ?> disabled value="">Choose Product...
      </option>
      <?php foreach ($products as $product) { ?>
        <option <?php
        if (isset($_GET["change"]) && ($product[0] == $changedata[1])) {
          echo 'selected';
        }
        ?>><?php echo $product[1] ?></option>
      <?php } ?>
    </select>
    <label> </label>
    <?php $locations = $database->query("SELECT " . $ENV["TABLE_LOCATIONS"] . ".Location_ID," . $ENV["TABLE_LOCATIONS"] . ".Street FROM " . $ENV["TABLE_LOCATIONS"])->fetch_all();
    ; ?>
    <select name="location" aria-label="Select" required>
      <option <?php
      if (!isset($_GET["change"])) {
        echo 'selected';
      }
      ?> disabled value="">Choose Location...
      </option>
      <?php foreach ($locations as $location) { ?>
        <option <?php
        if (isset($_GET["change"]) && ($location[0] == $changedata[1])) {
          echo 'selected';
        }
        ?>><?php echo $location[1] ?></option>
      <?php } ?>
    </select>
    <label> </label>
    <input type="text" name="amount" placeholder="Amount" value="<?php
    if (isset($_GET["change"])) {
      echo $changedata[4];
    }
    ?>">
    <input type="date" name="date" placeholder="Date" value="<?php
    if (isset($_GET["change"])) {
      echo $changedata[3];
    }
    ?>">
  </fieldset>
  <button href="index.php" type="submit">Eintragen</button>
</form>