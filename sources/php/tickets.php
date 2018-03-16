  <?php
  include 'database.php';
  include 'head.php';
  ?>
  <div class="container-fluid">
  	<div class ="row">
  		<div class="container" style="height: 100%; border-radius: 0 0 10px 10px;">
  			<?php
  			$mailaddress = $_GET["mailaddress"];
        $sql = "SELECT * FROM REL_REGUSER NATURAL JOIN REL_RESERVATION NATURAL JOIN TBL_MOVIE NATURAL JOIN TBL_SHOW WHERE MAILADDRESS = q'{" . $mailaddress . "}' ORDER BY reservationid DESC";
      // execute sql statement
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);

        $stmt2 = oci_parse($conn, $sql);
        oci_execute($stmt2);
        $row = oci_fetch_assoc($stmt);
        ?>
  <div class ="row">
         <div class="col-xs-12">
          <h3>
           <?php echo "Reservations for " . $row['MAILADDRESS'] . " alias " . $row['USERNAME'] ; ?>
         </h3>
         <hr>
         </div>


            <div class="col-md-10 col-xs-12" style="margin-top: 1%; overflow:auto; height:500">
      <table class="table table-hover">
        <thead style="position: relative; top: 0px; ">
          <tr bgcolor="#DF691A">
            <th align="center">ReservationID</th>
            <th align="center">Movie</th>
            <th align="center">Seat</th>
            <th align="center">Row</th>
            <th align="center">Price</th>
          </tr>
        </thead>
        <tbody>
          <?php
          while ($row = oci_fetch_assoc($stmt)) {
            echo "<tr bgcolor=\"#4C84A2\">
            <td align=\"left\">" . $row['RESERVATIONID'] . "</td>
            <td align=\"left\"><a href='reservation.php?showid=${row['SHOWID']}'> " . $row['TITLE'] . "</td>
            <td align=\"left\">" . $row['SEATNR'] . "</td>
            <td align=\"left\">" . $row['ROWNR'] . "</td>
            <td align=\"left\">10 Euro</td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>


  <div class ="row">

    </div>
  </div>

</div>
</div>
</div>


<?php  oci_free_statement($stmt); ?>
<?php include 'footer.php'; ?>
