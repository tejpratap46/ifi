<?php
error_reporting ( 0 );
require '../../connection.php';
require '../../pagination/pagination.top.php';

$apikey = $_GET ['apikey'];
$q = $_GET ['q'];

if (! empty ( $apikey )) {
	$api = mysql_query ( "SELECT * FROM apikey WHERE api_key = '" . $apikey . "'" ) or die ( "{\"status\":0," . "\"error\":\"" . mysql_error () . "\"}" );
	if (mysql_num_rows ( $api ) != 1) {
		die ( "{\"status\":0," . "\"error\":\"invalid apikey\"}" );
	}
} else {
	die ( "{\"status\":0," . "\"error\":\"invalid apikey\"}" );
}
if ($q) {
	echo "{";
	$query = mysql_query ( "SELECT * FROM `top` WHERE `name` LIKE '%". $q ."%' OR `formula` LIKE '%". $q ."%' ORDER BY `top`.`total_shares` DESC LIMIT $start,$limit" ) or die ( "{\"status\":0," . "\"error\":\"" . mysql_error () . "\"}" );
	echo "\"status\":1,";
	echo "\"results\":" . mysql_num_rows ( $query ) . ",";
	echo "\"text\":[";
	for($i = 0; $i < mysql_num_rows ( $query ); $i ++) {
		$info = mysql_fetch_array ( $query );
		if ($i + 1 == mysql_num_rows ( $query )) {
			echo "{";
			echo '"index":"' . $info ['index'] . '",';
			echo '"name":"' . $info ['name'] . '",';
			echo '"total_shares":"' . $info ['total_shares'] . '",';
			echo '"formula":' . $info ['formula'];
			echo "}";
		} else {
			echo "{";
			echo '"index":"' . $info ['index'] . '",';
			echo '"name":"' . $info ['name'] . '",';
			echo '"total_shares":"' . $info ['total_shares'] . '",';
			echo '"formula":' . $info ['formula'];
			echo "},";
		}
	}
	echo "]";
	echo "}";
}else{
	die ( "{\"status\":0," . "\"error\":\"Enter q\"}" );
}
?>