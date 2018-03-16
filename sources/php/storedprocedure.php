  <?php
  include 'database.php';
  include 'head.php';
  ?>
  <div class="container-fluid">


 <!-- Stored procedure to get username form mailaddress-->
    
    <?php
    // fetch ONLY 200 rel_reguser for dropdown
    $sql = "SELECT MAILADDRESS FROM REL_REGUSER WHERE ROWNUM <= 200";
    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt);
    ?>



    <div class ="row">
    <div class="col-md-8" style="margin-top: 1%">
        <h3>Check Mailaddress</h3>
       <form method="post" action="">
    <select name="mailaddresse">
        <?php
              while ($row = oci_fetch_assoc($stmt)) {
                echo "<option>" . $row['MAILADDRESS'] . "</option>";
              }
        ?>
    </select>
    <INPUT TYPE="submit" name="submit" />
</form>

<?php
if (isset($_POST['mailaddresse']))
 {
    //Call Stored Procedure  
    $mailaddress = $_POST['mailaddresse'];
    echo $mailaddress;
    $username='';
    $sproc = oci_parse($conn, 'begin getusername(:p1, :p2); end;');
    //Bind variables, p1=input (mailaddress), p2=output (username)
    oci_bind_by_name($sproc, ':p1', $mailaddress);
    oci_bind_by_name($sproc, ':p2', $username, 40);
    oci_execute($sproc);
    $conn_err=oci_error($conn);
    $proc_err=oci_error($sproc);
    //If there have been no Connection or Database errors, print department
    if(!$conn_err && !$proc_err){
      echo("<p> This mailaddress is linked to the username \"" . $username . "\"</p>");
    }
    else{
      //Print potential errors and warnings
      print("There is no mailaddress like this in database");
    }  
     // clean up connections
 oci_free_statement($sproc);
 oci_close($conn);  
 }

?>
      </div>
    </div>
    </div>

    <?php  oci_free_statement($stmt); ?>
<?php include 'footer.php'; ?>
