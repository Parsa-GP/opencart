<?php
$conn = new mysqli("localhost", "root", "", "opencart");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error."\n");
} echo "Connected successfully\n";

$sql = "SELECT `meta_description` FROM `oc_product_description` WHERE `meta_description` <> '' AND `product_id` = '".$_GET["p"]."' ORDER BY `product_id` LIMIT 1;";
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
  echo "0 results.\n";
}
$conn->close();


?>