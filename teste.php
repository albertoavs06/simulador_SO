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
//		$obj = json_decode($json);
//		foreach($obj as $key => $value) {
//			echo '<p>' . $key . " " . $value . "</p>";
//		}
		
//		$someJSON = '[{"nome":"A","tempo":"1","tipo":"cpu","valor":""},{"nome":"B","tempo":"1","tipo":"cpu","valor":""},{"nome":"C","tempo":"1","tipo":"cpu","valor":""},{"nome":"D","tempo":"1","tipo":"cpu","valor":""}]';
//		// JSON string
//		//$someJSON = '[{"name":"Jonathan Suh","gender":"male"},{"name":"William Philbin","gender":"male"},{"name":"Allison McKinnery","gender":"female"}]';
//		
//		// Convert JSON string to Array
//		$someArray = json_decode($someJSON, true);
//		foreach($someArray as $item) {
//			echo $item["nome"];
//			echo $item['tempo'];
//		}
//		//echo $someArray[0]["name"]; // Access Array data
//		
//		// Convert JSON string to Object
//		$someObject = json_decode($someJSON);
//		echo $someObject[0]->name; // Access Object data
	?>
</body>
</html>