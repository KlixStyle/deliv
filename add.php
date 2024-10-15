<?php
$ENV = parse_ini_file(".env");
$con = mysqli_connect($ENV["CONNECTION"], $ENV["USER"], $ENV["PASSWORD"]);
$db = mysqli_select_db($con, $ENV["DATABASE"]);
$database = new mysqli($ENV["CONNECTION"], $ENV["USER"], $ENV["PASSWORD"], $ENV["DATABASE"]);

if (isset($_GET["change"])) {
  $merker = $_GET["change"];
  $rs = mysqli_query($con, "select * from " . $ENV["TABLE_ITEMS"] . " where Product_ID=" . $merker);
  $changedata = mysqli_fetch_row($rs);
}
?>
<h1>Add Product</h1>
<form method="post">
  <fieldset>
    <?php
    if (isset($_GET["del"])) {
      mysqli_query($con, "delete from " . $ENV["TABLE_ITEMS"] . " where Product_ID=" . $_GET["del"]);
      echo '<meta http-equiv="refresh" content="0;URL=index.php?value=1">';
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $result = mysqli_query($con, "SELECT Manufacturer_ID FROM " . $ENV["TABLE_MANUFACTURERS"] . " WHERE Name = '" . $_POST["manufacturer"] . "'");
      $_manufacturer = $result->fetch_row()[0];
      $_name = $_POST["name"];
      $_price = $_POST["price"];

      if (($_manufacturer != "") && ($_name != "") && ($_price != "") && (isset($_GET["change"]))) {
        mysqli_query($con, "update " . $ENV["TABLE_ITEMS"] . " set Manufacturer_ID=$_manufacturer,Product_Name='$_name',Gross_Price=$_price where Product_ID=$merker");
        echo '<meta http-equiv="refresh" content="0;URL=index.php?value=1">';
      } else if (($_manufacturer != "") && ($_name != "") && ($_price != "")) {
        mysqli_query($con, "insert into " . $ENV["TABLE_ITEMS"] . "(Manufacturer_ID,Product_Name,Gross_Price) values (" . $_manufacturer . ",'$_name',$_price)");
        echo '<meta http-equiv="refresh" content="0;URL=index.php?value=1">';
      } else {$invalid = true;}
    }

    ?>
    <?php $manufacturers = $database->query("SELECT " . $ENV["TABLE_MANUFACTURERS"] . ".Manufacturer_ID," . $ENV["TABLE_MANUFACTURERS"] . ".name FROM " . $ENV["TABLE_MANUFACTURERS"])->fetch_all();
    ; ?>
    <select name="manufacturer" aria-label="Select" required>
      <option <?php
      if (!isset($_GET["change"])) {
        echo 'selected';
      }
      ?> disabled value="">Wähle Anbieter aus...
      </option>
      <?php foreach ($manufacturers as $manufacturer) { ?>
        <option <?php
        if (isset($_GET["change"]) && ($manufacturer[0] == $changedata[1])) {
          echo 'selected';
        }
        ?>><?php echo $manufacturer[1] ?></option>
      <?php } ?>
    </select>
    <label></label>
    <input type="text" name="name" placeholder="Name" value="<?php
    if (isset($_GET["change"])) {
      echo $changedata[1];
    }
    ?>">
    <input type="text" name="price" placeholder="Preis" value="<?php
    if (isset($_GET["change"])) {
      echo $changedata[3];
    }
    ?>">
  </fieldset>
  <button href="index.php" type="submit">Eintragen</button>
</form>