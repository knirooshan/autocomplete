<?php
    
    $conn = mysqli_connect("localhost","root","","global_printing_system");

      if(!$conn){
          die('Could not Connect MySql Server:' .mysql_error());
        }

?>