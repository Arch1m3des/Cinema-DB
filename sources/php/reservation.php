  <?php
  include 'database.php';
  include 'head.php';
  ?>
  <div class="container-fluid" >
  	<?php     
    // fetch ONLY 200 rel_regshow_id for dropdown
  	$sql = "SELECT MAILADDRESS FROM REL_REGUSER";
  	$stmt = oci_parse($conn, $sql);
  	oci_execute($stmt);

    $show_id = $_GET["showid"];


    $sql_show = "SELECT rownr, seatnr FROM GETSHOWINFO WHERE showid = ".$show_id."";
    $stmt_show = oci_parse($conn, $sql_show);
    oci_execute($stmt_show);
    ?>

    <div class ="row row-centered">
      <div class="col-md-4 col-xs-5" style="margin-top: 1%">
        <h3>Step 1: Choose Mailaddress</h3>
        <label>Mailaddress:</label>
        <?php echo "<form method=\"post\" action=\"reservation.php?showid=".$show_id."\">";?>
        <select name="seatselection">
          <?php
          while ($row = oci_fetch_assoc($stmt)) {
            echo "<option>" . $row['MAILADDRESS'] . "</option>";
          }
          ?>
        </select>
        <?php echo "<input type='hidden' name='showid' value='". $show_id ."' >"; ?>
        <h3>Step 2: Pick a seat</h3>
        <table>
          <?php

     $hall= oci_fetch_assoc($stmt_show); //get first statement
     for ($hrow=1; $hrow <= 10; $hrow++) { echo "<p></p>";
      for ($hseat=1; $hseat <= 15 ; $hseat++) { 
        if (($hall['ROWNR'] == $hrow) && ($hall['SEATNR'] == $hseat)) { 
          echo "<input type='checkbox' checked disabled readonly />";
          $hall = oci_fetch_assoc($stmt_show);  
        } 
        else {
          echo "<input type='checkbox' name='seat[]' value='".$hrow.";".$hseat."'/>";
        }
      } echo "<p></p>";
    }
    ?>
  </table>

  <INPUT TYPE="submit" name="submit" value="Send" />
</form>

<?php
if (isset($_POST["seat"]) && !empty($_POST["seat"])) {
  $mail = $_POST["seatselection"];
  //echo $mail;
  //echo "<br>showid:" . $show_id;


  foreach( $_POST["seat"] as $v ){
    $seat = explode(';', $v);
    echo '<tr><td>Row: '.$seat[0].' Seat: '.$seat[1].'<br></td></tr>';
    $sql = "INSERT INTO rel_reservation(mailaddress, showId, rowNr, seatNr) VALUES ('". $mail ."', ".$show_id.", ".$seat[0].", ".$seat[1].")";
    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt);
  }
  echo "<p>".count($_POST["seat"])." Tickets are reserved! <br><a href='tickets.php?mailaddress=".$mail."'>Purchase them here</p>";

 
}else{  
  echo "No seats selected";
} 

//header("Refresh:0; url='reservation.php?showid=" .$show_id."'");

?>
</div>
</div>

</div>

<?php  oci_free_statement($stmt); ?>
<?php include 'footer.php'; ?>