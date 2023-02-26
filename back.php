<?php 
$address = 'localhost';
$username = 'root';
$password = '';
$database = 'warehouses';
$charset = 'utf8';

try {
	$pdo = new PDO ("mysql:host=$address;dbname=$database;cahrset=$charset", $username, $password);
	$pdo->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo "Exception: " . $e->getMessage();
} 

$file = file_get_contents('https://bfdev.ru/test/json.txt');
$data = json_decode($file, true);

foreach ($data["NA_SKLADE"] as $key => $value) {
	$warehouse1_quantity[] = $value[1]["QUANTITY"];
	$warehouse2_quantity[] = $value[0]["QUANTITY"];
	$goods[] = $key;
}

$insertDataFromFile = function() use($goods, $warehouse1_quantity, $warehouse2_quantity){
	
	for ($i=0; $i < count($goods); $i++) { 
		$sql_insert_data = "INSERT INTO `remnants of goods`(goods, warehouse1_quantity, warehouse2_quantity) VALUES(?, ?, ?)";
		$insert_data = $pdo->prepare($sql_insert_data);
		$insert_data->execute([$goods[$i], $warehouse1_quantity[$i], $warehouse2_quantity[$i]]);
	}
};
//$insertDataFromFile();

$sumQuantityWh = function ($wh) use ($pdo) {
	$sql_sum_quantity_wh = "SELECT warehouse" . "$wh" . "_quantity FROM `remnants of goods` WHERE warehouse" . "$wh" . "_quantity > 0";
	$rst_sum_quantity_wh = $pdo->query($sql_sum_quantity_wh);
	$sum_quantity_wh = $rst_sum_quantity_wh->fetchAll(PDO::FETCH_COLUMN, 0);
	$sum_array = array_sum($sum_quantity_wh);

	return $sum_array;
};

$shortCodeNomenclature = function($code) { 
	preg_match("/\w+-\w+/", $code, $short);
	$short = reset($short); 

	return $short;
};

$shortNomenclaturesWithItems = function($goods, $warehouse_quantity) use ($shortCodeNomenclature) {
	$result = [];
	for ($i = 0; $i < count($goods); $i++) { 
		$shortNames[] = $shortCodeNomenclature($goods[$i]);

		if ($warehouse_quantity[$i] > 0) {
			$result[$i] = [
				'short_names' => $shortNames[$i],
				'quantity' => $warehouse_quantity[$i]
			];
		}
	}	 

	return $result;
};

$convertToJson = function($goods, $shortNamesList) {
	$jsonArray = [];
	
	foreach($shortNamesList as $i => $shortNames) {

		foreach($shortNames as $key => $item) {
			$fullName = $goods[$key]; 
			$jsonArray[$fullName] = [$item['short_names'], "склад " . ++$i, $item['quantity']];
		}
	}
	return json_encode($jsonArray);
};



