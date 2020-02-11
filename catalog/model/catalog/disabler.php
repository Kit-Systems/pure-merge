<?php
$server = "srv-pleskdb16.ps.kz";
$db = "iringkz_pure";
$username = "iring_pure";
$password = "Class123";

//Connect to Mysql
$link = mysqli_connect($server, $username, $password, $db);
if (mysqli_connect_errno()) {
    printf("ERROR $server: %s\n", mysqli_connect_error());
    exit();
}
mysqli_set_charset($link, "utf8");

$q1 = mysqli_query($link,"SELECT product_id from oc_product_to_category WHERE category_id='2009'");

while ($f1 = mysqli_fetch_array($q1)){
    $product_id = $f1[0];
    mysqli_query($link,"UPDATE oc_product SET status = '0' WHERE product_id = '" . (int)$product_id . "'");
}

mysqli_close($link);

?>