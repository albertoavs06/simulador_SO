<?php 
// mostra os erros de abrir arquivo
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

$array_algoritimos = array('round_robin', 'lotery', 'priority', 'queues', 'shortest');

$flag = 0;
$algoritimos = array();
foreach($array_algoritimos as $alg) {
	$conteudo = $_GET[$alg];
	
	if($conteudo == null) {
		header('Location: '.'index.php');
	} else {
		if(!strcmp($conteudo, "null")) {
			$flag = $flag + 1;
		} else {
			array_push($algoritimos, $alg);
		}
	}
}

// nenhum dos 5 algoritimos tem processos
if($flag == 5) {
	header('Location: '.'index.php');
}

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
<!DOCTYPE html>

<?php
// verifica a lingua da pagina
$lang_file;
$lingua = $_GET['lang'];

if($lingua == null || !strcmp($lingua, "")) {
	$lang_file = "lang/english.xml";	// padrao
	$lingua = "en";
} else {
	if(!strcmp($lingua, "en")) {
		$lang_file = "lang/english.xml";
	} else if(!strcmp($lingua, "pt")) {
		$lang_file = "lang/portugues.xml";
	}
}

// carrega o arquivo de configuracoes xml
$xml = simplexml_load_file($lang_file) or die("Error: Cannot create object");
?>

<html>

<head>
	<!-- Suporte a UTF-8 -->
	<meta charset="UTF-8">

	<!-- Titulo -->
    <?php echo '<title>'. $xml->title[0]->value .'</title>'; ?>

	<!-- jQuery -->
	<script src="jquery-2.1.4.min.js"></script>
	
	<!-- Bootstrap -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="./equal-height-columns.css">

	<!-- Altera o tamanho dos radio buttons -->
	<style>
		input[type=radio] { 
			margin: 1em 1em 1em 0; 
			transform: scale(1.5, 1.5); 
		}
	</style>
	<script type="text/javascript" src="simulador.js"></script>		
</head>

<body>
	<div class="container">
	
	<!-- Header. Logo e Nome do simulador -->
	<!-- Colocar uma cor de background-->
	<div class="row" style="background-color:lightgreen">
		<div class="col-md-2">
			<img src="img/logo_icmc.png" height="100%" width="100%" style="padding-top:15%; padding-bottom:11%">
		</div>
		
		<div class="col-md-10">
       		<?php echo '<h1>'. $xml->title[0]->value .'</h1>'; ?>
			<h3>Marcelo Koti Kamada & Maria Lydia Fioravanti</h3>
		</div>
	</div>

	<div style="float:right">
		<?php 
			$en = "";
			$pt = "";
			if(!strcmp($lingua, "en")) {
				$en = 'style="text-decoration: underline"';
			} else {
				$pt = 'style="text-decoration: underline"';
			}

			$params = array_merge($_GET, array("lang" => "pt"));
			$new_query_string = http_build_query($params);
			echo "<p><a href='simulador.php?" . urldecode($new_query_string) . "' " . $pt . ">Portugues</a>";

			$params = array_merge($_GET, array("lang" => "en"));
			$new_query_string = http_build_query($params);
			echo "
			<a href='simulador.php?" . urldecode($new_query_string) . "' " . $en . ">English</a></p>";
		?>
	</div>
	
	<!-- Titulo da primeira secao -->
   	<div class="row">
       	<div class="col-md-12" style="background-color:lightblue">
       		<h2>Round Robin</h2>
   		</div>
       </div>

       <div class="row">
       	<div class="col-md-12" style="background-color:lightblue">
               <p style="display:inline;">Quantum: 000</p>
               <p style="display:inline;">Tempo Total de Execucao: 0000</p>
           </div>
       </div>
	
	<!-- Tres colunas, uma para a descricao do que ocorreu, a do meio para mostrar a CPU, e a ultima para o menu de opcoes -->
	<div class="row">
		<div class="row-eq-height">
			<div class="col-md-3" style="background-color:lightblue">			
				<h3>Descricao</h3>
				<textarea id="descricao_algoritimo" class="form-control" rows="3" style="height:80%"></textarea>
			</div>
		
            <div class="col-md-6" style="background-color:lightgreen">
             	<h3>CPU</h3>
               	<canvas id="canvas_cpu" class="col-md-12"> </canvas>
            </div>

			<div class="col-md-3" style="background-color:lightblue">			
				<h3>Opcoes</h3>
				<div class="row">
					<div class="col-md-12">
                        <button class="form-control" type="button" onclick="next()">Avancar</button>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
                   		<button class="form-control" type="button" onclick="previous()">Voltar</button>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
                        <button class="form-control" type="button" onclick="auto()">Automatico</button>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
                        <button class="form-control" type="button" onclick="reset()">Resetar</button>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
                        <button class="form-control" type="button" onclick="home()">Home</button>
					</div>
				</div>
			</div>
		</div>
	</div>		

       <div class="row" style="background-color:gray">		
		<div class="col-md-12">
			<table style="width:100%"> </table>
		</div>
	</div>	
	
	</div><!-- Fim div container -->

	<?php 

	// executa o round robin, guardando o stdout em $retorno
	if(count($algoritimos) == 1) {
		$command = "engine/round_robin/main.py -d engine/". $algoritimos[0] ."/en.xml -j '" . $_GET[$algoritimos[0]] . "' -q ". $quantum . " -s ". $switch ." -i ". $io_time . " -p " . $until_io;
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
		
		$arrlength = count($mensagens);
		echo '<p id="msg">' . $arrlength . '</p>';
		for($i = 0; $i < $arrlength; $i++) {
			echo '<p id="msg' . $i . '">' . $mensagens[$i] . '<p>';
		}

		$arrlength = count($status);
		echo '<p id="status">' . $arrlength . '</p>';
		for($i = 0; $i < $arrlength; $i++) {
			echo '<p id="status' . $i . '">' . $status[$i] . '<p>';
		}
	} 
	// senao, tem que comparar os algoritimos
	else {
		echo '<p>Compara</p>';
	}
	?>
</body>

</html>