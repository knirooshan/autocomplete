<?php

include('gfg.php'); 

$searchTerm = $_POST['term'];
$sql = "SELECT ownername FROM passwords WHERE ownername LIKE '%".$searchTerm."%'"; 
$result = $conn->query($sql); 
if ($result->num_rows > 0) {
  $tutorialData = array(); 
  while($row = $result->fetch_assoc()) {

   $data['id']    = $row['id']; 
   $data['value'] = $row['ownername'];
   array_push($tutorialData, $data);
} 
}
 echo json_encode($tutorialData);
?>