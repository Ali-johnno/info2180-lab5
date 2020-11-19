<?php
header("Access-Control-Allow-Origin: *");

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
$stmt = $conn->query("SELECT * FROM countries");

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$country = filter_var($_GET['country'], FILTER_SANITIZE_STRING);

if(isset($_GET['country']) && !isset($_GET['context'])){
  $search = $conn->query("SELECT * FROM countries WHERE name LIKE '%$country%'");
  $searchResults = $search->fetchAll(PDO::FETCH_ASSOC);
  echo "<table>";
  echo "<tr><th>Name</th><th>Continent</th><th>Independence</th><th>Head of State</th></tr>"; 
  foreach($searchResults as $row){
    echo "<tr><td>".$row['name']."</td><td>".$row['continent']."</td><td>".$row['independence_year']."</td><td>".$row['head_of_state']."</td></tr>";
  }
  
 // echo '<ul>';
  //foreach ($results as $row){
   // echo '<li>'. $row['name'] . ' is ruled by ' . $row['head_of_state'] .'</li>';
  //}
  //echo '</ul>';
}

if(isset($_GET['context'])){
  $search = $conn->query("SELECT * FROM countries WHERE name LIKE '%$country%'");
  $searchResults = $search->fetchAll(PDO::FETCH_ASSOC);
  // $citiesSearch = $conn->query("SELECT * FROM cities WHERE country_code = '%$country_code%'");
  // $cities = $citiesSearch->fetchAll(PDO::FETCH_ASSOC);

  echo "<table>";
  echo "<tr><th>Name</th><th>District</th><th>Population</th></tr>";
  foreach( $searchResults as $row){
    $countryCode = $row['code']; 
    $citiesSearch = $conn->query("SELECT c.name, c.district, c.population 
    FROM cities c 
    INNER JOIN countries 
    ON countries.code = c.country_code
    WHERE countries.code = '{$countryCode}'");
  }
  
  foreach ($citiesSearch as $row){
    echo "<tr><td>".$row['name']."</td><td>".$row['district']."</td><td>".$row['population']."</td></tr>";
  }
}
?>

