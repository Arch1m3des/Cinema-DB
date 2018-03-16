  <?php
  include 'database.php';
  include 'head.php';
  ?>
  <div class="container-fluid">
  	<div class ="row">
  		<div class="container" style="height: 100%; border-radius: 0 0 10px 10px;">
  			<?php
  			$show_id = $_GET["showid"];
        $sql = "SELECT HALLID, ISAN, AGERESTRICTION, TITLE, GENRE, CINEMANAME, ZIPCODE, CITY, STREET, STREETNUMBER, OPENTIMES, DISACCESS, SCREENAREA, TO_CHAR(SHOWSTART, 'DD.MM.YYYY HH24:MI') AS SHOWSTART, TO_CHAR(SHOWEND, 'DD.MM.YYYY HH24:MI')  AS SHOWEND  FROM TBL_SHOW NATURAL JOIN TBL_MOVIE NATURAL JOIN TBL_CINEMA NATURAL JOIN TBL_HALL WHERE SHOWID = q'{" . $show_id . "}'";
			// execute sql statement
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);

			// fetch row
        $row = oci_fetch_assoc($stmt);
        ?>

        <div class="row">
         <div class="col-xs-10">
          <h1>
            Showinfo
          </h1>
          <h2>
           <?php echo $row['TITLE']; 
           echo "<p>Genre: " . $row['GENRE']."</p>";
           ?>

         </h2>

         <h3>
         <div class="col-xs-10">
          <?php
          echo "Show starts: &nbsp; &nbsp; &nbsp;" . $row['SHOWSTART'] . "<br>";       
          echo "Show ends: &nbsp; &nbsp; &nbsp; &nbsp;" . $row['SHOWEND'] . "<br>";      
          ?>
          </div>
          <div class="col-xs-2">
          <?php
               echo "<a href='reservation.php?showid=". $show_id."' class='btn btn-info' role='button'>Book Ticket</a>";
          ?>
          </div>
          <hr>
        </h3>
        <hr>
      </div>
    </div>


          <div class="col-xs-4">
            <h3>Hallinfo</h3>
            <?php
            $disabled="";
            if($row['DISACCESS']== $disabled){
              $disabled="Yes";
            } else{
                $disabled="No";
              }
     
            echo "<p> Hallnumber:  " . $row['HALLID'] . "</p>";
            echo "<p> Screenarea:  " . $row['SCREENAREA'] . "m&sup2; </p>";
            echo "<p> Disabled access:  " . $disabled . "</p>";
            ?>
            <hr>

          </div>  

      <div class="col-xs-6">
        <h3>Cinema</h3>
        <?php
        echo "<p> Name:  " . $row['CINEMANAME'] . "</p>";
        echo "<p> Address:  <a href='https://www.google.at/maps/place/" . $row['ZIPCODE'] . ", " . $row['CITY'] . " " . $row['STREET'] . " " . $row['STREETNUMBER'] . "'>" . $row['ZIPCODE'] . ", " . $row['CITY'] . " " . $row['STREET'] . " " . $row['STREETNUMBER'] . "</a></p>";
        echo "<p> Opentimes:  " . $row['OPENTIMES'] . "</p>";
        ?>
        <hr>
        <h3>Actors</h3>
        <?php
        $sql2 = "SELECT * FROM TBL_MOVIE NATURAL JOIN TBL_ACTOR WHERE ISAN = q'{" . $row['ISAN'] . "}'";

					// execute sql statement
        $stmt2 = oci_parse($conn, $sql2);
        oci_execute($stmt2);

					// fetch row
        while($row = oci_fetch_assoc($stmt2)){
						/*echo "<form action='detail_actor.php' method='get'>
								<input type='hidden' name='actor' value='" . $row['P_ID'] . "'>
								<button class='btn btn-link' type='submit'>" . $row['FIRST_NAME'] . " " . $row['LAST_NAME'] . "</button><br>
							</form>";*/
              echo "<tr bgcolor=\"#4E5D6C\">
              <td>" . $row['ACTOR'] . "</td></tr>";

            }
            ?>
          </div>

      </div>
    </div>
  </div>


<?php  oci_free_statement($stmt); ?>
<?php include 'footer.php'; ?>
