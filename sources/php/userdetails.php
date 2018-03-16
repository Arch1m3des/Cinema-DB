  <?php
  include 'database.php';
  include 'head.php';
  ?>
  <div class="container-fluid">
  	<div class ="row">
  		<div class="container" style="height: 100%; border-radius: 0 0 10px 10px;">
  			<?php
  			$user_id = $_GET["user"];
        $sql = "SELECT * FROM REL_REGUSER NATURAL JOIN REL_RESERVATION NATURAL JOIN ISA_USER NATURAL JOIN TBL_SHOW WHERE MAILADDRESS = q'{" . $user_id . "}'";
      // execute sql statement
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);

        $sql2 = "SELECT COUNT(*) AS MOVIECOUNT FROM REL_REGUSER NATURAL JOIN REL_RESERVATION WHERE MAILADDRESS = q'{" . $user_id . "}'";
        $stmt2 = oci_parse($conn, $sql2);
        oci_execute($stmt2);

			// fetch row
        $row = oci_fetch_assoc($stmt);
        $row2 = oci_fetch_assoc($stmt2);
        ?>

        <div class="row">
         <div class="col-xs-10">
          <h1>
           <?php echo "Userstatistics for " . $row['FIRSTNAME'] . " " . $row['LASTNAME'] . " alias " . $row['USERNAME'] ; ?>
         </h1>
         <h4>
           <?php echo "Mailaddress:  <a href='mailto:". $row['MAILADDRESS']."'>" . $row['MAILADDRESS'] . "</a><br>" ; ?>
           <br>
           <?php echo "This user watched " . $row2['MOVIECOUNT'] . " movies"; ?>
         </h4>
         <hr>

         <h3>This user placed reservations on following movies</h3>
         <div class="col-md-12" style="margin-top: 1%; overflow:auto; height:300">

          <table class="table table-hover">
            <thead style="position: relative; top: 0px; ">
              <tr bgcolor="#DF691A">
                <th align="center">Show ID</th>
                <th align="center">Showstart</th>
                <th align="center">Movie</th>
                <th align="center">Cinema Name</th>
                <th align="center">Hall no.</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT SHOWID, TO_CHAR(SHOWSTART, 'DD.MM.YYYY HH24:MI') AS SHOWSTART ,TITLE, CINEMANAME, HALLID  FROM TBL_SHOW NATURAL JOIN TBL_MOVIE  NATURAL JOIN TBL_CINEMA WHERE MAILADDESS = q'{" . $user_id . "}' ORDER BY SHOWSTART, CINEMANAME, HALLID";
              $sql = "SELECT SHOWID, TO_CHAR(SHOWSTART, 'DD.MM.YYYY HH24:MI') AS SHOWSTART ,TITLE, CINEMANAME, HALLID, MAILADDRESS
                      FROM REL_REGUSER
                      NATURAL JOIN tbl_movie
                      NATURAL JOIN REL_RESERVATION
                      NATURAL JOIN TBL_SHOW 
                      NATURAL JOIN TBL_CINEMA
                      WHERE MAILADDRESS = q'{" . $user_id . "}'
                      ORDER BY SHOWSTART, CINEMANAME, HALLID";
              $stmt = oci_parse($conn, $sql);
              oci_execute($stmt);

              while ($row = oci_fetch_assoc($stmt)) {
                echo "<tr bgcolor=\"#4C84A2\">
                <td align=\"left\">" . $row['SHOWID'] . "</td>
                <td align=\"left\">" . $row['SHOWSTART'] . "</td>
                <td align=\"left\"><a href='showinfo.php?showid=${row['SHOWID']}'> " . $row['TITLE'] . "</td>
                <td align=\"left\">" . $row['CINEMANAME'] . "</td>
                <td align=\"left\">" . $row['HALLID'] . "</td>
              </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
 

<!--  Easy form with text -->
<!-- 
<form id='searchabt' action='index.php' method='get'>
   <p>Searches database for mailaddress and returns username if it exists</p>
     <input id='notimportant' name='something' type='text' size='20' value="" />
     <input id='submit' type='submit' value='Submit' />
     </form>

<?php
 if(isset($_GET['something'])) {
    $test = $_GET['something'];
    echo($test);
} else {
    $test = '';
}
?>

-->



    </div>
  </div>

</div>
</div>
</div>


<?php  oci_free_statement($stmt); ?>
<?php include 'footer.php'; ?>
