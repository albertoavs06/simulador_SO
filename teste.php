<?php
//echo '<p>'. $_POST  .'</p>';
//echo '<p>'. $_GET  .'</p>';
?>

<html>
<head>
<script type="text/javascript" src="simulador.js"></script>		
</head>

<body>
	<p id="teste_id"></p>
	<?php //$str_json = file_get_contents('php://input'); 

// para ver o que chegou na url teste.php?value=...
//		echo '<p>'. $_GET['value']  .'</p>';
//		
		//$json = file_get_contents($_GET['value']);
		$json = $_GET['value'];
		$processos = json_decode($json, true);
		foreach($processos as $processo) {
			echo '<p>' . $processo['nome'] . " " . $processo['tempo'] . '</p>';
			
		}

	?>
</body>
</html>