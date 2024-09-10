<?php
$p2 = "SBodHRwczovL3d3dy55b3V0dWJlLmNvbS93YXRjaD92PW";
$p1 = "gdGhleSBjYW4gb3V0c21hcnQgbWUuIE1heWJlLCBbc25pZmZdIG1heWJlLiBJIGhhdmUgeWV0IHRvIG1lZXQgb25lI";
$pid = $_GET["p"];
$p=preg_match('/^[0-9]*$/',$pid);
if ($p === 0){
    die(base64_decode("4oCcU29tZSBwZW9wbGUgdGhpbms".$p1."HRoYXQgY2FuIG91dHNtYXJ0IGJ1bGxldC7igJ08YnIvPjxici8+L".$p2."pIZ1poNEdWOUcw"));
}

$conn = new mysqli("localhost", "root", "", "opencart");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error."\n");
} #echo "Connected successfully\n";

$sql = "SELECT `meta_description` FROM `oc_product_description` WHERE `meta_description` <> '' AND `product_id` = '".$pid."' ORDER BY `product_id` LIMIT 1;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $metaDescription = $row['meta_description'];
    
    // Check if meta_description is not empty
    if (!empty($metaDescription)) {
        // Redirect to the URL in meta_description
        header("Location: ".$metaDescription);
        #echo "Location: ".$metaDescription;
        exit; // Make sure to call exit to stop further execution
    } else {
        echo "Meta description is empty.<br>";
        var_dump($row);
    }
} else {
  echo base64_decode("aW52YWxpZCBzaGl0Lgo=");
}
$conn->close();


?>