<?php
//echo '<p>'. $_POST  .'</p>';
//echo '<p>'. $_GET  .'</p>';


// razoes para passar pela URL:
//	para que o professor possa passar a URL para os alunos
//  para que fique visivel o que eu estou passando
//  para nao quebrar quando o cara clicar no botao de voltar

// para dar parse no output do round robin
// <?php
// parse_str("name=Peter&age=43");
// echo $name."<br>";
// echo $age;
// >

// recupera os valores da URL
$round_robin = $_GET['round_robin'];
$lotery = $_GET['lotery'];
$priority = $_GET['priority'];
$queues = $_GET['queues'];
$shortest = $_GET['shortest'];

// checa se tem todos os parametros esperados, se nao tiver algum, volta para a pagina de simulacao
if($round_robin == null || $lotery == null || $priority == null || $queues == null || $shortest == null) {
	header('Location: '.'index.php');
}
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

	// para conseguir adicionar a lingua no meio do json
	$params = array_merge($_GET, array("test" => "testvalue"));
	$new_query_string = http_build_query($params);
	echo "<p>" . urldecode($new_query_string) . "</p";
	//
	
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