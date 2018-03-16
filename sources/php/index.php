
  <?php
  include 'database.php';
  include 'head.php';

 
  ?>
  <div class="container-fluid">
  <a href='storedprocedure.php'>Link to Stored procedure</a>
    <div class ="row">

      <div class="col-md-2 col-xs-2" style="margin-top: 1%; overflow:auto; height:500">
        <table class="table table-hover" >
          <thead>
            <tr bgcolor="#DF691A">
              <th align="center">USERS</th>
            </tr>
          </thead>
          <tbody>
            <?php     
            // fetch all rel_reguser for login
            $sql = "SELECT * FROM rel_reguser";
            $stmt = oci_parse($conn, $sql);
            oci_execute($stmt);

            while ($row = oci_fetch_assoc($stmt)) {
              echo "<tr bgcolor=\"#4E5D6C\"><td align=\"left\"><a href='userdetails.php?user=${row['MAILADDRESS']}'>" . $row['MAILADDRESS'] . "</a></td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
   
    <div class="col-md-9 col-xs-5" style="margin-top: 1%; overflow:auto; height:500">
      <table class="table table-hover">
        <thead style="position: relative; top: 0px; ">
          <tr bgcolor="#DF691A">
            <th align="center">Showstart</th>
            <th align="center">Movie</th>
            <th align="center">Cinema Name</th>
            <th align="center">Hall no.</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql = "SELECT SHOWID, TO_CHAR(SHOWSTART, 'DD.MM.YYYY HH24:MI') AS SHOWSTART ,TITLE, CINEMANAME,HALLID   FROM TBL_SHOW NATURAL JOIN TBL_MOVIE  NATURAL JOIN TBL_CINEMA ORDER BY SHOWSTART, CINEMANAME, HALLID";
            $stmt = oci_parse($conn, $sql);
            oci_execute($stmt);

          while ($row = oci_fetch_assoc($stmt)) {
            echo "<tr bgcolor=\"#4C84A2\">
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
  </div>



</div>

<?php  oci_free_statement($stmt); ?>


<?php include 'footer.php'; ?>