<?php

require_once "db.php"; 

if (isset($_GET['term'])) {
     
   $query = "SELECT ownername FROM passwords WHERE ownername LIKE '{$_GET['term']}%'";
   $result = mysqli_query($conn, $query);
 
    if (mysqli_num_rows($result) > 0) {
     while ($user = mysqli_fetch_array($result)) {
      $res[] = $user['ownername'];
     }
    } else {
      $res = array();
    }
    //return json res
    echo json_encode($res);
}
?>
