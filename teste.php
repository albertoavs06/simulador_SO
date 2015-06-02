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
	
	// recupera os valores da URL
	$round_robin = $_GET['round_robin'];
	$lotery = $_GET['lotery'];
	$priority = $_GET['priority'];
	$queues = $_GET['queues'];
	$shortest = $_GET['shortest'];
	
	if(!strcmp($round_robin, "null")) {
	} else {
		$processos = json_decode($round_robin, true);
		echo '<p>Round Robin<p>';
		foreach($processos as $processo) {
			echo '<p>' . $processo['nome'] . " " . $processo['tempo'] . '</p>';
		}
	}

	if(!strcmp($lotery, "null")) {
	} else {
		$processos = json_decode($lotery, true);
		echo '<p>lotery<p>';
		foreach($processos as $processo) {
			echo '<p>' . $processo['nome'] . " " . $processo['tempo'] . '</p>';
		}
	}
	
	if(!strcmp($priority, "null")) {
	} else {
		$processos = json_decode($priority, true);
		echo '<p>priority<p>';
		foreach($processos as $processo) {
			echo '<p>' . $processo['nome'] . " " . $processo['tempo'] . '</p>';
		}
	}
	
	if(!strcmp($queues, "null")) {
	} else {
		$processos = json_decode($queues, true);
		echo '<p>queues<p>';
		foreach($processos as $processo) {
			echo '<p>' . $processo['nome'] . " " . $processo['tempo'] . '</p>';
		}
	}
	
	if(!strcmp($shortest, "null")) {
	} else {
		$processos = json_decode($shortest, true);
		echo '<p>queues<p>';
		foreach($processos as $processo) {
			echo '<p>' . $processo['nome'] . " " . $processo['tempo'] . '</p>';
		}
	}
	?>
</body>
</html>