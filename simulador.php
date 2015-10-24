<!-- 
Copyright 2015 Marcelo Koti Kamada, Maria Lydia Fioravanti

This file is part of I3S (Interactive Systems Scheduling Simulator)

I3S is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

I3S is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with I3S.  If not, see <http://www.gnu.org/licenses/>.
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

	<!-- 
	<link rel="stylesheet" type="text/css" href="util/equal-height-columns.css">
	 -->

	<script type="text/javascript" src="simulador.js"></script>		
	
	<style>
	.row {
		margin-top: 0px;
		margin-bottom: 0px;
	}

	.inactiveLink {
		pointer-events: none;
		cursor: default;
	}
	
	@font-face {
	    font-family: 'ponderosa';
	    src: url('util/ponderosa/ponde___.ttf');
	}

	.nopadding {
   padding: 0 !important;
   margin: 0 !important;
}
	</style>
	
</head>

<body style="padding-bottom: 66px;">
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">

            <div class="navbar-header">
				<a class="navbar-brand" href="#">
					<img src="img/logo.png" width="60" height="42" style="margin-top:-12px;"/>
				</a>
            </div>

            <ul class="nav navbar-nav">
                <li class="active"><a href="#"><?php echo $xml->item[22]->value; ?></a></li>
                <li><a href="#"><?php echo $xml->item[23]->value; ?></a></li>
            </ul>
            
           	<div style="float:right">
			<?php 
				$en = "";
				$pt = "";
				if(!strcmp($lingua, "en")) {
					$en = 'style="text-decoration: underline"';
				} else {
					$pt = 'style="text-decoration: underline"';
				}
				echo '<p><a href="index.php?lang=pt"'. $pt . '>Português</a>
						 <a href="index.php?lang=en"'. $en . '>English</a>
					</p>';
			?>
			</div> 
        </div>
    </nav>

    <div class="container-fluid">
    
    	<h3 id="current_algorithm"><?php 
			if(count($algoritimos) == 1) {
				echo $algoritimos[0];
			} else {
		    	echo $xml->item[25]->value;
			}
		?></h3>

		<!-- Tab items list -->
		<ul class="nav nav-tabs nav-justified" role="tablist">
			<li>
				<a href="#" role="tab" data-toggle="tab" class="inactiveLink"> 
		        	<spam id="campo_quantum" style="display:inline;"></spam>
		        	<?php echo str_replace('"', "", $quantum); ?>
				</a>
			</li>

			<li>
				<a href="#" role="tab" data-toggle="tab" class="inactiveLink"> 
		            <spam id="campo_switch_cost" style="display:inline;"></spam>
		            <?php echo str_replace('"', "", $switch); ?>
		        </a>
			</li>

			<li>
				<a href="#" role="tab" data-toggle="tab" class="inactiveLink"> 
			        <spam id="campo_io_time" style="display:inline;"></spam>
			        <?php echo str_replace('"', "", $io_time); ?>
			    </a>
			</li>

			<li>
				<a href="#" role="tab" data-toggle="tab" class="inactiveLink"> 
			        <spam id="campo_processing_until_io" style="display:inline;"></spam>
			        <?php echo str_replace('"', "", $until_io); ?>
			    </a>
			</li>
		</ul>

	    <div <?php if(count($algoritimos) != 1) echo 'style="display:none"'; ?>>
	    	<!-- Tres colunas, uma para a descricao do que ocorreu, a do meio para mostrar a CPU, e a ultima para o menu de opcoes -->
			<div class="row">
				<div class="row-eq-height">
					<div class="col-md-3">			
						<div class="panel panel-primary" id="kamada8">
							<!-- Panel Header -->
							<div class="panel-heading">
								<?php echo $xml->item[7]->value; ?>
							</div>
							<textarea id="descricao_algoritimo" class="form-control" rows="9" style="resize:none;" readonly></textarea>
		    			</div>
					</div>
			
        		    <div class="col-md-6">
                       	<div class="panel panel-primary">
                       	    <!-- Panel Header -->
                       	    <div class="panel-heading" id="kamada7">
                       	    	Viewport
							</div>
							
							<!-- 
				    		<div class="panel-body">
		               			<canvas id="canvas_cpu" class="col-md-12"> </canvas>
							</div>
							 -->
		               		<canvas id="canvas_cpu"> </canvas>
							
		               	</div>
            		</div>
	
					<div class="col-md-3">			
                       	<div class="panel panel-primary">
                       	    <!-- Panel Header -->
                       	    <div class="panel-heading">
								<?php echo $xml->item[8]->value; ?>
                       	    </div>

	    					<ul class="list-group">
								<button class="form-control" type="button" onclick="next()"><?php echo $xml->item[9]->value; ?></button>
				            	<button class="form-control" type="button" onclick="previous()"><?php echo $xml->item[10]->value; ?></button>
								<button class="form-control" type="button" onclick="auto()"><?php echo $xml->item[11]->value; ?></button>
				               	<button class="form-control" type="button" onclick="reset()"><?php echo $xml->item[12]->value; ?></button>
								<button class="form-control" type="button" onclick="home()"><?php echo $xml->item[13]->value; ?></button>
    						</ul>
						</div>
					</div>
				</div> <!-- Fim eq-height -->
			</div> <!-- Fim row body -->
			
			<div class="row">
				<div id="coluna1">
               		<div class="panel panel-primary" >
               		    <!-- Panel Header -->
               		    <div class="panel-heading">
							<spam id="myHeader1"> <?php echo $xml->table_header[5]->value; ?> </spam>
						</div>

						<div id="coluna1">
							<table class="table table-condensed" style="width:100%" id="myTable"> </table>
						</div>
					</div>
				</div>

			    <div id="tabelas_extras">		
					<div class="col-md-2 nopadding">
		               	<div class="panel panel-primary">
		               	    <div class="panel-heading">
								<spam id="myHeader3"> <?php echo $xml->table_header[5]->value; ?> </spam>
							</div>
							<table class="table table-condensed" style="width:100%" id="myTable3"> </table>
						</div>
					</div>
					
					<div class="col-md-2 nopadding">
		               	<div class="panel panel-primary">
		               	    <div class="panel-heading">
								<spam id="myHeader4"> <?php echo $xml->table_header[5]->value; ?> </spam>
							</div>
							<table class="table table-condensed" style="width:100%" id="myTable4"> </table>
						</div>
					</div>
					
					<div class="col-md-2 nopadding">
		               	<div class="panel panel-primary">
		               	    <div class="panel-heading">
								<spam id="myHeader5"> <?php echo $xml->table_header[5]->value; ?> </spam>
							</div>
							<table class="table table-condensed" style="width:100%" id="myTable5"> </table>
						</div>
					</div>	
				</div>

				<div id="coluna2">
	              	<div class="panel panel-primary">
	               	    <div class="panel-heading">
							<spam id="myHeader2"> <?php echo $xml->table_header[6]->value; ?> </spam>
						</div>
						<table class="table table-condensed" style="width:100%" id="myTable2"> </table>
				    </div> 
				</div>
			</div> <!-- Fim row -->
			</div> <!-- Fim do div que esconde a simulacao -->

		    <!-- comparacao -->
    		<div <?php if(count($algoritimos) == 1) echo 'style="display:none"'; ?>>
			    <?php 
			    	if(count($algoritimos) > 1) {
				    
			    	for($i = 0; $i < count($algoritimos); $i++) {
						$command = "python engine/" . $algoritimos[$i] . "/main.py -d engine/". $algoritimos[$i] ."/en.xml -j '" . $_GET[$algoritimos[$i]] . "' -q ". $quantum . " -s ". $switch ." -i ". $io_time . " -p " . $until_io;
						exec($command, $retorno);
				
						$arrays = array();
						
						$arrays['msg'] = array();
						$arrays['status'] = array();
						$arrays['cpu'] = array();
						$arrays['tte'] = array();
						$arrays['switches'] = array();
						$arrays['viewport'] = array();
		
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
    		
						echo '<div class="panel panel-primary" >';

							// imprime header
							echo '<div class="panel-heading">';
			       				echo '<h4 id="alg'.$i.'">' . $algoritimos[$i] . '</h4>'; // javascript tem que olhar no dicionario e traduzir
							echo '</div>';
							
							echo '<div class="panel-body">';
							// imprime estatisticas e tabela de processos
							echo '<div class="row">';
								echo '<div class="row-eq-height">';
									echo '<div class="col-md-4">';
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
							
									echo '<div class="col-md-8">';
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
							echo '</div>';
				
						echo '</div>';
							
						echo '<p id="resultado'.$i.'" hidden="true">'. $_GET[$algoritimos[$i]] . '</p>';
			    	}
		    	}
			    ?>

				<!-- Footer -->
			    <div class="panel-footer">
					<div class="row">	
						<div class="col-md-12">
							<!-- Executar Simulacao -->
							<center>
								<button type="button" class="btn btn-primary btn-lg" onclick="home()" style="width:50%"><?php echo $xml->item[13]->value; ?></button>
							</center>
						</div>
					</div>
			    </div>
    		</div> <!-- Fim div que esconde a comparacao -->
    	</div> <!-- Fim div panel -->
	</div><!-- Fim div container -->

    <div class="navbar navbar-default navbar-fixed-bottom navbar-inverse">
        <p class="navbar-text">&copy; <?php echo date("Y"); echo " " . $xml->item[21]->value; ?></p>
    </div>
	
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
		$arrays['viewport'] = array();
		
		foreach($retorno as $line) {
			$flag = 1;
			parse_str($line);	
			// $key e' o nome do indice do array
			// $valor vai ser o array associado a key
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

		// $key e' o tipo da mensagem, pode ser cpu, msg, tte
		foreach($arrays as $key => $value) {
			$arrlength = count($value);
			for($i = 0; $i < $arrlength; $i++) {
				echo '<p id="' . $key . $i . '" name="' . $key . $value[$i][0] . '" hidden="true">' . $value[$i][1] . '</p>';
//				echo '<p id="' . $key . $i . '" name="' . $key . $value[$i][0] . '" >[' .$key . $value[$i][0] . ']' . $value[$i][1] . '<p>';
				//echo '<p id="' . $key . $i . '" name="' . $key . $value[$i][0] . '" >[' .$key . $i . ']' . $value[$i][1] . '<p>';
			}
			echo '<p id="' . $key . '" hidden="true">' . ($value[$i-1][0] + 1) . '</p>';
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
