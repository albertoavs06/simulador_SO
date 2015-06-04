<?php
// razoes para passar pela URL:
//	para que o professor possa passar a URL para os alunos
//  para que fique visivel o que eu estou passando
//  para nao quebrar quando o cara clicar no botao de voltar

// mostra os erros de abrir arquivo
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

$quantum = $_GET['quantum'];
$switch = $_GET['switch'];
$io_time = $_GET['io_time'];
$until_io = $_GET['until_io'];

if($quantum == null || $switch == null || $io_time == null || $until_io == null) {
	header('Location: '.'index.php');
}
?>

<html>
<head>
<script type="text/javascript" src="simulador.js"></script>		
</head>

<body>
	<?php
	// para ver o que chegou na url teste.php?value=...
	// echo '<p>'. $_GET['value']  .'</p>';

	// para conseguir adicionar a lingua no meio do json
	$params = array_merge($_GET, array("test" => "testvalue"));
	$new_query_string = http_build_query($params);
	echo "<p>" . urldecode($new_query_string) . "</p>";
	
// para decodificar um json em php
//	if(!strcmp($round_robin, "null")) {
//	} else {
//		$processos = json_decode($round_robin, true);
//		echo '<p>Round Robin<p>';
//		foreach($processos as $processo) {
//			echo '<p>' . $processo['nome'] . " " . $processo['tempo'] . '</p>';
//		}
//	}

	
// para verificar se um arquivo existe em php
//	$myfile = fopen("engine/round_robin/en.xml", "r") or die("Unable to open file!");
//	fclose($myfile);
//	echo fread($myfile,filesize("engine/round_robin/teste.c"));
//	echo '</p>';

	// executa o round robin, guardando o stdout em $retorno
	$command = "engine/round_robin/main.py -d engine/round_robin/en.xml -j '" . $round_robin . "' -q ". $quantum . " -s ". $switch ." -i ". $io_time . " -p " . $until_io;
	exec($command, $retorno);
	
	echo '<p>' . $command . '</p>';
	
	$mensagens = array();
	$estados = array();
	
	foreach($retorno as $line) {
		parse_str($line);	
        if(!strcmp($id, "status")) {
        	array_push($estados, $value);
        } else {
	        array_push($mensagens, $value);
        }
	}
	
	foreach($mensagens as $msg) {
		echo '<p>' . $msg . '<p>';
	}
	
	foreach($estados as $msg) {
		echo '<p>' . $msg . '<p>';
	}
	?>
</body>
</html>