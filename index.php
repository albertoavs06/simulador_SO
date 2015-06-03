<!DOCTYPE html>
<?php
// restart no apache
// sudo systemctl restart apache2

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
        <?php echo '<title>'. $xml->title[0]->value .'</title>'; ?>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<!-- <script src="jquery-2.1.4.min.js"></script> -->
		
		<!-- DataTables CSS -->
		<link rel="stylesheet" type="text/css" href="./DataTables-1.10.7/media/css/jquery.dataTables.css">
		<link rel="stylesheet" type="text/css" href="./equal-height-columns.css">
		
		<!-- jQuery -->
		<script type="text/javascript" charset="utf8" src="./DataTables-1.10.7/media/js/jquery.js"></script>
		  
		<!-- DataTables -->
		<script type="text/javascript" charset="utf8" src="./DataTables-1.10.7/media/js/jquery.dataTables.js"></script>

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
				echo '<p><a href="index.php?lang=pt"'. $pt . '>Portugues</a>
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
		<!-- style="height: 50%" -->
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
					<h4 id="titulo_algoritimo">Round Robin</h4>
					<p id="descricao_algoritimo"></p>
					
					<!-- Suas vantanges-->
					<h4>Vantagens</h4>
					<p id="vantagens_algoritimo">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium</p>
					
					<!-- e desvantagens-->
					<h4>Desvantagens</h4>
					<p id="desvantagens_algoritimo">molestias excepturi sint occaecati cupiditate non provident</p>
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
                echo '<input type="text" id="' . $xml->input[0]['name'] . '" placeholder="' . $xml->input[0]->value . '" onfocus="tempoExecucaoSelecionado()" size="' . strlen($xml->input[0]->value) .' " maxlength="3">'; 
                ?>
			
				<!-- Campo opcional para alguns algoritimos -->
				<div>
					<h4  id="titulo_opcional" hidden="true">Valor Opcional</h4>
					<input type="text" id="valor_opcional" placeholder="prioridade ou tickets" hidden="true" onfocus="valorOpcionalSelecionado()">
				</div>			
			
				<!-- CPU ou IO bound -->
				<div>
					<?php echo '<h4>' . $xml->second_section[1]->title . '</h4>'; ?>
					<p><input type="radio" name="bound" value="cpu" checked onclick="tipoSelecionado()">CPU bound</p>
					<p><input type="radio" name="bound" value="io" onclick="tipoSelecionado()">I/O bound</p>
				</div>			
			
				<button type="button" onclick="addProcesso()">Adicionar</button>
				<button type="button" onclick="rmProcesso()">Remover</button>
			</div>	
			
			<div class="col-md-7">
				<h4 id="titulo_secao2">Titulo</h4>
				<p id="descricao_secao2"></p>				
			</div>	
		</div>
		
		<div class="row" style="background-color:khaki">		
			<div class="col-md-12">
				<table style="width:100%" id="myTable"> </table>
			</div>
		</div>	
			
		<!-- Parametros Globais de Simulacao -->
		<div class="row" style="background-color:yellow">	
			<div class="col-md-12">
				<h2>Parametros Globais de Simulacao</h2>
			</div>
		</div>

		<div class="row" style="background-color:yellow">
			<div class="col-md-5">
				<h4>Quantum</h4>
				<input type="text" id="" placeholder="" onfocus="">
			
				<h4>Custo de Switch</h4>
				<input type="text" id="" placeholder="" onfocus="">
			
				<h4>Tempo de Processamento para I/O bound</h4>
				<input type="text" id="" placeholder="" onfocus="">

				<h4>Tempo de I/O</h4>
				<input type="text" id="" placeholder="" onfocus="">
			</div>	
			
			<div class="col-md-7">
				<h4 id="titulo_secao3">Titulo</h4>
				<p id="descricao_secao3"></p>				
			</div>	
		</div>

		<!-- Simulacao -->
		<div class="row" style="background-color:coral">	
			<div class="col-md-12">
                <?php echo '<h2>'. $xml->title[3]->value .'</h2>'; ?>
			</div>
		</div>

		<div class="row" style="background-color:coral">	
			<div class="col-md-12">
                <?php echo '<p>'. $xml->third_section[0]->description .'</p>'; ?>
			</div>
		</div>
		
		<div class="row" style="background-color:coral">	
			<div class="col-md-12">
				<!-- Executar Simulacao -->
				<center>
					<input type="submit" value="Executar Simulacao" style="width: 50%" onclick="simular()">
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
		<script type="text/javascript" src="game.js"></script>		
	</body>
</html>

