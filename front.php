<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Warehouses</title>
	<style>
		div {
			display: inline-block;
			width: 40%;
			vertical-align: top;
			padding-left:100px ;
		}
	</style>
</head>
<body>
	<?php 
		include "back.php";

		$address = 'localhost';
		$username = 'root';
		$password = '';
		$database = 'warehouses';
		$charset = 'utf8';
		try {
			$pdo = new PDO ("mysql:host=$address;dbname=$database", $username, $password);
			$pdo->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo "Exception: " . $e->getMessage();
		} 
	?>
	
	<div>
		<label>Остатки склада 1</label>
		<ul>
			<?php
				$shortNomenclatures1  = $shortNomenclaturesWithItems($goods, $warehouse1_quantity);
				foreach ($shortNomenclatures1 as $key => $value) {
						echo '<li>Код товара: ' . $value['short_names'] . ', Остаток: ' . $value['quantity'] . 'шт;</li>';
				} 
			?>
		</ul>
		<p>Итого для первого склада: <?php echo $sumQuantityWh(1);?> шт</p>
	</div>
	<div>
		<label>Остатки склада 2</label>
		<ul>
			<?php
				$shortNomenclatures2 = $shortNomenclaturesWithItems($goods, $warehouse2_quantity);
				foreach ($shortNomenclatures2 as $key => $value) {
						echo '<li>Код товара: ' . $value['short_names'] . ', Остаток: ' . $value['quantity'] . 'шт;</li>';
				} ?>
		</ul>
		<p>Итого для второго склада: <?php echo $sumQuantityWh(2);?> шт</p>
	</div>
	<div>

		<?php 
			$result = [];
			$result[] = $shortNomenclatures1;
			$result[] = $shortNomenclatures2;
			echo $convertToJson($goods, $result);
		?>
	</div>
</body>
</html>


