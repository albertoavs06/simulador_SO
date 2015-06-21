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

<!DOCTYPE html>

<?php
// bug no lang do windows
// bug do style no javascript no chrome
// colocar um botao para lista de processos sugerida


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
	</head>
	
	<body>
		<div class="container">
		
		<!-- Header. Logo e Nome do simulador -->
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
				echo '<p><a href="index.php?lang=pt"'. $pt . '>Português</a>
						 <a href="index.php?lang=en"'. $en . '>English</a>
					</p>';
			?>
		</div>

		<!-- Titulo da primeira secao -->
		<div class="row">
			<div class="col-md-12" style="background-color:lightblue">
                <?php echo '<h2>'. $xml->title[1]->value .'</h2>'; ?>
			</div>
		</div>
			
		<!-- Duas colunas, uma para escolher o algoritimo e a outra para a descricao -->
		<div class="row">
			<div class="row-eq-height">
				<div class="col-md-5" style="background-color:lightblue">			
					<?php 
					$first = 0;

					foreach($xml->algorithm as $item):
						$value = $item->value;
						$title = $item->title;
					
						if($first == 0) {
							echo '<p style="font-weight: bold;"><input type="radio" name="algoritimo" checked value=' . $value . ' onclick="selecionaAlgoritimo()">' . $title . '</p>';
							$first = 1;
						} else {
							echo '<p style="font-weight: bold;"><input type="radio" name="algoritimo" value=' . $value . ' onclick="selecionaAlgoritimo()">' . $title . '</p>';
						}
					endforeach;						
					?>
				</div>
			
				<div class="col-md-7" style="background-color:lightblue">
					<!-- Breve descricao do algoritimo-->
					<h4 id="titulo_algoritimo"></h4>
					<p id="descricao_algoritimo"></p>
					
					<!-- Suas vantanges-->
					<h4> <?php echo $xml->item[0]->value; ?> </h4>
					<p id="vantagens_algoritimo"></p>
					
					<!-- e desvantagens-->
					<h4> <?php echo $xml->item[1]->value; ?> </h4>
					<p id="desvantagens_algoritimo"></p>
				</div>
			</div>
		</div>
			
		<!-- Titulo da segunda secao -->	
		<div class="row" style="background-color:khaki">
			<div class="col-md-12">
                <?php echo '<h2>'. $xml->title[2]->value .'</h2>'; ?>
			</div>
		</div>	

		<div class="row" style="background-color:khaki">
			<div class="col-md-5">
				<!-- Menu para adicionar novos processos -->
                <?php 
                echo '<h4>' . $xml->second_section[0]->title . '</h4>';
                echo '<input class="form-control" type="text" id="' . $xml->input[0]['name'] . '" placeholder="' . $xml->input[0]->value . '" onfocus="tempoExecucaoSelecionado()" size="' . strlen($xml->input[0]->value) .' " maxlength="3">'; 
                ?>
			
				<!-- Campo opcional para alguns algoritimos -->
				<div>
					<h4  id="titulo_opcional" hidden="true">Valor Opcional</h4>
					<input class="form-control" type="text" id="valor_opcional" placeholder="prioridade ou tickets" hidden="true" onfocus="valorOpcionalSelecionado()">
				</div>			
			
				<!-- CPU ou IO bound -->
				<div>
					<?php echo '<h4>' . $xml->second_section[1]->title . '</h4>'; ?>
					<p><input type="radio" name="bound" value="cpu" checked onclick="tipoSelecionado()"><?php echo $xml->item[2]->value; ?></p>
					<p><input type="radio" name="bound" value="io" onclick="tipoSelecionado()"><?php echo $xml->item[3]->value; ?></p>
				</div>			
			
				<form class="form-inline">
					<button class="btn btn-success" type="button" onclick="addProcesso()"><?php echo $xml->item[4]->value; ?></button>
					<button class="btn btn-danger" type="button" onclick="rmProcesso()"><?php echo $xml->item[5]->value; ?></button>
					<button class="btn btn-warning" type="button" onclick="listaSugerida()"><?php echo $xml->item[14]->value; ?></button>
				</form>
			</div>	
			
			<div class="col-md-7">
				<h4 id="titulo_secao2"></h4>
				<p id="descricao_secao2"></p>				
			</div>	
		</div>
		
		<div class="row" style="background-color:khaki">		
			<div class="col-md-12">
				<table class="table table-condensed" style="width:100%" id="myTable"> </table>
			</div>
		</div>	
			
		<!-- Parametros Globais de Simulacao -->
		<div class="row" style="background-color:#ffaf4b ">	
			<div class="col-md-12">
				<h2 style="cursor:pointer;" data-toggle="collapse" data-target="#abc">
                <?php echo $xml->title[3]->value; ?>
				</h2>
			</div>
		</div>

		<div id="abc" class="collapse">
			<div class="row" style="background-color:#ffaf4b">
				<div class="col-md-5">
					<h4> <?php echo '<p>'. $xml->third_section[0]->title .'</p>'; ?> </h4>
					<input class="form-control" type="text" id="quantum" placeholder="<?php echo $xml->input[3]->value ?>" onfocus="terceiraSecaoSelecionada(this);">
				
					<h4> <?php echo '<p>'. $xml->third_section[1]->title .'</p>'; ?> </h4>
					<input class="form-control" type="text" id="switch" placeholder="<?php echo $xml->input[4]->value ?>" onfocus="terceiraSecaoSelecionada(this);">
				
					<h4> <?php echo '<p>'. $xml->third_section[2]->title .'</p>'; ?> </h4>
					<input class="form-control" type="text" id="io_time" placeholder="<?php echo $xml->input[5]->value ?>" onfocus="terceiraSecaoSelecionada(this);">
	
					<h4> <?php echo '<p>'. $xml->third_section[3]->title .'</p>'; ?> </h4>
					<input class="form-control" type="text" id="until_io" placeholder="<?php echo $xml->input[3]->value ?>" onfocus="terceiraSecaoSelecionada(this);">
				</div>	
				
				<div class="col-md-7">
					<h4 id="titulo_secao3">Titulo</h4>
					<p id="descricao_secao3"></p>				
				</div>	
			</div>
		</div>

		<!-- Simulacao -->
		<div class="row" style="background-color:#b0d4e3">	
			<div class="col-md-12">
                <?php echo '<h2>'. $xml->title[4]->value .'</h2>'; ?>
			</div>
		</div>

		<div class="row" style="background-color:#b0d4e3">	
			<div class="col-md-12">
                <?php echo '<p>'. $xml->third_section[4]->description .'</p>'; ?>
			</div>
		</div>
		
		<div class="row" style="background-color:#b0d4e3">	
			<div class="col-md-12">
				<!-- Executar Simulacao -->
				<center>
					<button type="button" class="btn btn-primary btn-lg" onclick="simular()" style="width:50%"><?php echo $xml->item[6]->value; ?></button>
				</center>
			</div>
		</div>
			
		</div><!-- Fim div container -->
		
    	<?php
            // ################### Descricoes dos algoritimos ###################
    	    foreach($xml->algorithm as $algoritimo):
    	    		$campo = $algoritimo['name'];
    	            $titulo = $algoritimo->title;
    	            $descricao = $algoritimo->description;
    	            $vantagens = $algoritimo->pros;
    	            $desvantagens = $algoritimo->cons;
    	            $valor = $algoritimo->value;
    	            
    	            echo "<div hidden=true id=\"titulo_" . $campo . "\">";
    	            echo $titulo;
    	            echo "</div>";
    	            
    	            echo "<div hidden=true id=\"descricao_" . $campo . "\">";
    	            echo $descricao;
    	            echo "</div>";
    	            
    	            echo "<div hidden=true id=\"vantagens_" . $campo . "\">";
    	            echo $vantagens;
    	            echo "</div>";
    	            
    	            echo "<div hidden=true id=\"desvantagens_" . $campo . "\">";
    	            echo $desvantagens;
    	            echo "</div>";

    	            echo "<div hidden=true id=\"valor_" . $campo . "\">";
    	            echo $valor;
    	            echo "</div>";
    	    endforeach;
		
			// ################### Descricoes da segunda secao ###################
		    foreach($xml->second_section as $item):
		    	$campo = $item['name'];
		    	$titulo = $item->title;
		    	$descricao = $item->description;
		    	
		    	echo "<div hidden=true id=\"titulo_" . $campo . "\">";
		    	echo $titulo;
		    	echo "</div>";
					
				echo "<div hidden=true id=\"descricao_" . $campo . "\">";
				echo $descricao;
		    	echo "</div>";
			endforeach;

			// ################### Descricoes da terceira secao ###################
		    foreach($xml->third_section as $item):
		    	$campo = $item['name'];
		    	$titulo = $item->title;
		    	$descricao = $item->description;
		    	
		    	echo "<div hidden=true id=\"titulo_" . $campo . "\">";
		    	echo $titulo;
		    	echo "</div>";
					
				echo "<div hidden=true id=\"descricao_" . $campo . "\">";
				echo $descricao;
		    	echo "</div>";
			endforeach;

			// ################### placeholder dos campos de input ###################
			foreach($xml->input as $item) {
				$campo = $item['name'];
				$valor = $item->value;
				
				echo "<div hidden=true id=\"" . $campo . "\">";
				echo $valor;
				echo "</div>";
			}
			
			// ################### mensagens de erro ###################
			foreach($xml->error as $item) {
				$campo = $item['name'];
				$valor = $item->value;
				
				echo "<div hidden=true id=\"" . $campo . "\">";
				echo $valor;
				echo "</div>";
			}

			// ################### cabecalho da tabela ###################
			foreach($xml->table_header as $item) {
				$campo = $item['name'];
				$valor = $item->value;
				
				echo "<div hidden=true id=\"" . $campo . "\">";
				echo $valor;
				echo "</div>";
			}
		?>
		<!-- Script javascript para as funcoes -->
		<script type="text/javascript" src="index.js"></script>		
	</body>
</html>

