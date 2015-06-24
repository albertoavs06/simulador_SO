<!-- 
Copyright 2015 Marcelo Koti Kamada, Maria Lydia Fioravanti

This file is part of SESI (Simulador de Escalonamento para Sistemas Interativos).

SESI is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

SESI is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with SESI.  If not, see <http://www.gnu.org/licenses/>.
 -->

<?php 
// mostra os erros de abrir arquivo
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
$lingua = null;

if(array_key_exists('lang', $_GET)) {
	$lingua = $_GET['lang'];
}

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
	<script src="util/jquery-2.1.4.min.js"></script>
	
	<!-- Bootstrap -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="util/equal-height-columns.css">

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
			<!--  
<img src="img/logo_icmc.png" height="100%" width="100%" style="padding-top:15%; padding-bottom:11%">
			-->
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
			echo "<p><a href='simulador.php?" . urldecode($new_query_string) . "' " . $pt . ">Português</a>";

			$params = array_merge($_GET, array("lang" => "en"));
			$new_query_string = http_build_query($params);
			echo "
			<a href='simulador.php?" . urldecode($new_query_string) . "' " . $en . ">English</a></p>";
		?>
	</div>
	

	<!-- Titulo da primeira secao -->
   	<div class="row">
       	<div class="col-md-12" style="background-color:#ffaf4b">
       		<h2 id="current_algorithm"><?php 
       			if(count($algoritimos) == 1) {
       				echo $algoritimos[0];
       			} else {
       				echo 'Parametros Globais de Simulacao';
       			}
       		?></h2>
   		</div>
    </div>

    <div class="row" id="ponto_alinhamento">
       	<div class="col-md-3" style="background-color:#ffaf4b">
               <p id="campo_quantum" style="display:inline;"></p>
               <?php echo str_replace('"', "", $quantum); ?>
        </div>

       	<div class="col-md-3" style="background-color:#ffaf4b">
               <p id="campo_switch_cost" style="display:inline;"></p>
               <?php echo str_replace('"', "", $switch); ?>
        </div>

       	<div class="col-md-3" style="background-color:#ffaf4b">
               <p id="campo_io_time" style="display:inline;"></p>
               <?php echo str_replace('"', "", $io_time); ?>
        </div>

       	<div class="col-md-3" style="background-color:#ffaf4b">
               <p id="campo_processing_until_io" style="display:inline;"></p>
               <?php echo str_replace('"', "", $until_io); ?>
        </div>
    </div>
	
	<div <?php if(count($algoritimos) != 1) echo 'style="display:none"'; ?>>

	<!-- Tres colunas, uma para a descricao do que ocorreu, a do meio para mostrar a CPU, e a ultima para o menu de opcoes -->
	<div class="row">
		<div class="row-eq-height">
			<div class="col-md-3" style="background-color:lightblue">			
				<h3><?php echo $xml->item[7]->value; ?></h3>
				<textarea id="descricao_algoritimo" class="form-control" rows="9" style="resize:none;" readonly></textarea>
			</div>
		
            <div class="col-md-6" style="background-color:lightblue">
             	<h3>CPU</h3>
               	<canvas id="canvas_cpu" class="col-md-12" height="100"> </canvas>
            </div>

			<div class="col-md-3" style="background-color:lightblue">			
				<h3><?php echo $xml->item[8]->value; ?></h3>
				<div class="row">
                    <button class="form-control" type="button" onclick="next()"><?php echo $xml->item[9]->value; ?></button>
				</div>
				<div class="row">
                	<button class="form-control" type="button" onclick="previous()"><?php echo $xml->item[10]->value; ?></button>
				</div>
				<div class="row">
                    <button class="form-control" type="button" onclick="auto()"><?php echo $xml->item[11]->value; ?></button>
				</div>
				<div class="row">
                	<button class="form-control" type="button" onclick="reset()"><?php echo $xml->item[12]->value; ?></button>
				</div>
				<div class="row">
                    <button class="form-control" type="button" onclick="home()"><?php echo $xml->item[13]->value; ?></button>
				</div>
			</div>
		</div>
	</div>		

    <div class="row" style="background-color:khaki">		
		<div id="coluna1">
			<center>
				<h3 id="myHeader1"> <?php echo $xml->table_header[5]->value; ?> </h3>
			</center>
			<table class="table table-condensed" style="width:100%" id="myTable"> </table>
		</div>

	    <div id="tabelas_extras">		
			<div class="col-md-2">
				<center>
					<h3 id="myHeader3"> <?php echo $xml->table_header[5]->value; ?> </h3>
				</center>
				<table class="table table-condensed" style="width:100%" id="myTable3"> </table>
			</div>
	
			<div class="col-md-2">
				<center>
					<h3 id="myHeader4"> <?php echo $xml->table_header[5]->value; ?> </h3>
				</center>
				<table class="table table-condensed" style="width:100%" id="myTable4"> </table>
			</div>
	
			<div class="col-md-2">
				<center>
					<h3 id="myHeader5"> <?php echo $xml->table_header[5]->value; ?> </h3>
				</center>
				<table class="table table-condensed" style="width:100%" id="myTable5"> </table>
			</div>
		</div>	

		<div id="coluna2">
			<center>
				<h3 id="myHeader2"> <?php echo $xml->table_header[6]->value; ?> </h3>
			</center>
			<table class="table table-condensed" style="width:100%" id="myTable2"> </table>
		</div>
	</div>
		
	</div> <!-- Fim do div que esconde a simulacao -->

    <!-- comparacao -->
    <div <?php if(count($algoritimos) == 1) echo 'style="display:none"'; ?>>

    <?php 
    	if(count($algoritimos) > 1) {
    	$cores = array('lightblue', 'khaki', '#ffaf4b', '#8AE68A', '#CCCC52');
    
    	for($i = 0; $i < count($algoritimos); $i++) {
			$command = "python engine/" . $algoritimos[$i] . "/main.py -d engine/". $algoritimos[$i] ."/en.xml -j '" . $_GET[$algoritimos[$i]] . "' -q ". $quantum . " -s ". $switch ." -i ". $io_time . " -p " . $until_io;
			exec($command, $retorno);

			$arrays = array();
		
			$arrays['msg'] = array();
			$arrays['status'] = array();
			$arrays['cpu'] = array();
			$arrays['tte'] = array();
			$arrays['switches'] = array();
		
			foreach($retorno as $line) {
				$flag = 1;
				parse_str($line);	
				foreach($arrays as $key => $valor) {
					if(!strcmp($id, $key)) {
						array_push($arrays[$key], array($time_stamp, $value));
						$flag = 0;
						break;
					}
				}
				
				if($flag) {
					echo '<p>nao deu match ' . $line . ' </p>';	
				}
			}
    		
			// imprime header
			echo '<div class="row">';
			echo '<div class="col-md-12" style="background-color:'. $cores[$i] .'">';
       		echo '<h2 id="alg'.$i.'">' . $algoritimos[$i] . '</h2>'; // javascript tem que olhar no dicionario e traduzir
			echo '</div>';
			echo '</div>';
			
			// imprime estatisticas e tabela de processos
			echo '<div class="row">';
			echo '<div class="row-eq-height">';

			echo '<div class="col-md-4" style="background-color:'. $cores[$i] .'">';
			echo '<h3>'. $xml->item[16]->value.'</h3>';
			echo '<textarea id="" class="form-control" rows="9" style="resize: none;" readonly>';

			$tamanho = count($arrays['tte']) - 1;
			echo $xml->item[17]->value . ': ' . $arrays['tte'][$tamanho][1] . "\n";

			$tamanho = count($arrays['cpu']) - 1;
			echo $xml->item[18]->value . ': ' . $arrays['cpu'][$tamanho][1] . "\n";

			$tamanho = count($arrays['switches']) - 1;
			echo $xml->item[19]->value . ': ' . $arrays['switches'][$tamanho][1] . "\n";

			echo '</textarea>';
			echo '</div>';
			
			echo '<div class="col-md-8" style="background-color:'. $cores[$i] .'">';
			echo '<h3>'. $xml->item[15]->value .'</h3>';
			echo '<table class="table table-condensed" style="width:100%" id="tabela' . $i . '">';
			echo '<thead>';
			
			echo '<tr>';
			echo '<th style="text-align:center">'. $xml->table_header[0]->value .'</th>';
			echo '<th style="text-align:center">'. $xml->table_header[2]->value .'</th>';
			echo '<th style="text-align:center">'. $xml->table_header[1]->value .'</th>';
			
			if(!strcmp($algoritimos[$i], "lotery")) {
				echo '<th style="text-align:center">' . $xml->table_header[3]->value .'</th>';
			} else if(!strcmp($algoritimos[$i], "priority")) {
				echo '<th style="text-align:center">' . $xml->table_header[4]->value .'</th>';
			}
			
			echo '</tr>';
			echo '</thead>';
			
			echo '<tbody>';
			echo '</tbody>';
			
			echo '</table>';
			
			echo '</div>';
			echo '</div>';

			echo '</div>';
			
			echo '<p id="resultado'.$i.'" hidden="true">'. $_GET[$algoritimos[$i]] . '</p>';
    	}
    	}
    ?>
	<div class="row" style="background-color:#b0d4e3">	
		<div class="col-md-12">
			<!-- Executar Simulacao -->
			<center>
				<button type="button" class="btn btn-primary btn-lg" onclick="home()" style="width:50%"><?php echo $xml->item[13]->value; ?></button>
			</center>
		</div>
	</div>

	</div> <!-- Fim do div que esconde a comparacao -->

	</div><!-- Fim div container -->

	<?php 
		if($lingua == null) {
			echo '<p id="lang" hidden="true">en</p>';
		} else {
			echo '<p id="lang" hidden="true">' . $lingua .'</p>';
		}
	?>
	
	<?php 
	// executa o round robin, guardando o stdout em $retorno
	if(count($algoritimos) == 1) {
		$command = "python engine/" . $algoritimos[0] . "/main.py -d engine/". $algoritimos[0] ."/".$lingua.".xml -j '" . $_GET[$algoritimos[0]] . "' -q ". $quantum . " -s ". $switch ." -i ". $io_time . " -p " . $until_io;
		exec($command, $retorno);
		echo '<p hidden="true">' . $command . '</p>';
		
		$arrays = array();
		
		$arrays['msg'] = array();
		$arrays['status'] = array();
		$arrays['cpu'] = array();
		$arrays['tte'] = array();
		$arrays['switches'] = array();
		
		foreach($retorno as $line) {
			$flag = 1;
			parse_str($line);	
			foreach($arrays as $key => $valor) {
				if(!strcmp($id, $key)) {
					array_push($arrays[$key], array($time_stamp, $value));
					$flag = 0;
					break;
				}
			}
			
			if($flag) {
				echo '<p hidden="true">nao deu match ' . $line . ' </p>';	
			}
		}
		
		foreach($arrays as $key => $value) {
			$arrlength = count($value);
			for($i = 0; $i < $arrlength; $i++) {
//				echo '<p id="' . $key . $value[$i][0] . '">' . $value[$i][0] . ' ' . $value[$i][1] . '<p>';
				echo '<p id="' . $key . $i . '" name="' . $key . $value[$i][0] . '" hidden="true">' . $value[$i][1] . '<p>';
			}
			echo '<p id="' . $key . '" hidden="true">' . count($value) . '<p>';
		}
	} 
	
	// ################### Descricoes dos algoritimos ###################
	foreach($xml->algorithm as $algoritimo):
	$campo = $algoritimo['name'];
	$titulo = $algoritimo->title;
	 
	echo "<div hidden=true id=\"" . $campo . "\">";
	echo $titulo;
	echo "</div>";
	endforeach;
	
	// ################### Descricoes da terceira secao ###################
	foreach($xml->third_section as $item):
	$campo = $item['name'];
	$titulo = $item->title;
	 
	echo "<div hidden=true id=\"" . $campo . "\">";
	echo $titulo;
	echo "</div>";
	endforeach;

	// ################### cabecalho das tabelas ###################
	foreach($xml->table_header as $item):
	$campo = $item['name'];
	$titulo = $item->value;
	 
	echo "<div hidden=true id=\"" . $campo . "\">";
	echo $titulo;
	echo "</div>";
	endforeach;
	
	// ################### itens ###################
	echo '<div hidden=true id="execution_total_time">' . $xml->item[17]->value . '</div>';
	echo '<div hidden=true id="cpu_usage">' . $xml->item[18]->value . '</div>';
	echo '<div hidden=true id="context_switches">' . $xml->item[19]->value . '</div>';
	echo '<div hidden=true id="action">' . $xml->item[20]->value . '</div>';
	?>
</body>

</html>
