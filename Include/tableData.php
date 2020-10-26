<?php
$resp = array();

$row = array(["Red","Blue","Green","Orange"],[13,16,18,19],["Sarah","Adam","Ben","John"],['Dog','Cat','Mouse','Tiger']);
$columns = array("Colour","Number", "Name","Animal");

$resp["respCode"] = 1;
$resp["data"][] = $columns;
$resp["data"][] = $row;

echo json_encode($resp);

?>