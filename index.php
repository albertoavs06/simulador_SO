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
	
	<style>
	@font-face {
	    font-family: 'ponderosa';
	    src: url('util/ponderosa/ponde___.ttf');
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
            
            <form class="navbar-form navbar-right">
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
            </form>
        </div>
    </nav>

    <div class="container">
		<!-- Tab items list -->
		<ul id="diveInfo" class="nav nav-tabs nav-justified" role="tablist">
			<li class="active">
				<a href="#tab_algorithm" role="tab" data-toggle="tab"> <?php echo $xml->title[1]->value; ?> </a>
			</li>

			<li>
				<a href="#tab_processes" role="tab" data-toggle="tab"> <?php echo $xml->title[2]->value; ?> </a>
			</li>

			<li>
				<a href="#tab_parameters" role="tab" data-toggle="tab"> <?php echo $xml->title[3]->value; ?> </a>
			</li>

			<li>
				<a href="#tab_simulation" role="tab" data-toggle="tab" onclick="tab_simulation()"> <?php echo $xml->title[4]->value; ?> </a>
			</li>
		</ul>

		<!-- Tab contents, when selected -->
		<div class="tab-content">
			<div class="tab-pane active" id="tab_algorithm">
				<div class="panel panel-primary">
					<!-- Panel Header -->
				    <div class="panel-heading">
				        <h4><?php echo $xml->title[1]->value; ?></h4>
				    </div>

					<!-- Panel Body -->
				    <div class="panel-body">
					<div class="row">
						<div class="row-eq-height">

							<!-- First Column Start -->
							<div class="col-md-5" >	
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
							<!-- First Column End -->

							<!-- Second Column Start -->
							<div class="col-md-7">
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
							<!-- Second Column End-->
						</div>
					</div>
					</div>

					<!-- Footer -->
				    <div class="panel-footer">
				    	<?php echo $xml->item[24]->value; ?>
				    </div>
        		</div>
			</div>

			<!-- Second Tab -->
			<div class="tab-pane" id="tab_processes">
				<div class="panel panel-primary">
					<!-- Panel Header -->
					<div class="panel-heading">
				        <h4><?php echo $xml->title[2]->value; ?></h4>
					</div>

					<!-- Panel Body -->
					<div class="panel-body">
						<div class="row">
						<div class="row-eq-height">
							<div class="col-md-5">
								<!-- Menu para adicionar novos processos -->
				                <?php 
				                echo '<h4>' . $xml->second_section[0]->title . '</h4>';
            				    echo '<input class="form-control" type="text" id="' . $xml->input[0]['name'] . '" placeholder="' . $xml->input[0]->value . '" onfocus="tempoExecucaoSelecionado()" size="' . strlen($xml->input[0]->value) .' " maxlength="3">'; 
                				?>
						
								<!-- CPU ou IO bound -->
								<div>
									<?php echo '<h4>' . $xml->second_section[1]->title . '</h4>'; ?>
									<p><input type="radio" name="bound" value="cpu" checked onclick="tipoSelecionado()"><?php echo $xml->item[2]->value; ?></p>
									<p><input type="radio" name="bound" value="io" onclick="tipoSelecionado()"><?php echo $xml->item[3]->value; ?></p>
								</div>			
								
								<!-- Campo opcional para alguns algoritimos -->
								<div>
									<h4  id="titulo_opcional" hidden="true"></h4>
									<input class="form-control" type="text" id="valor_opcional" placeholder="prioridade ou tickets" hidden="true" onfocus="valorOpcionalSelecionado()">
								</div>	
			
								<!-- Dar uma arrumada para nao ficar tao perto dos outros -->
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
						</div>
        			</div>
				</div>
				
				<!-- Process List Table -->
				<div class="panel panel-primary">
					<!-- Panel Header -->
					<div class="panel-heading">
			    		<h4><?php echo $xml->item[15]->value ?></h4>
					</div>
					<table class="table table-condensed" style="width:100%" id="myTable"></table>
				</div>

			</div>

			<div class="tab-pane" id="tab_parameters">
				<div class="panel panel-primary">
					<!-- Panel Header -->
					<div class="panel-heading">
				        <h4><?php echo $xml->title[3]->value; ?></h4>
					</div>

					<!-- Panel Body -->
					<div class="panel-body">
						<div class="row">
						<div class="row-eq-height">
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
								<h4 id="titulo_secao3"></h4>
								<p id="descricao_secao3"></p>				
							</div>
						</div>
						</div>	
					</div>
				</div>
			</div>

			<div class="tab-pane" id="tab_simulation">
				<div class="panel panel-primary">
					<!-- Panel Header -->
					<div class="panel-heading">
				        <h4><?php echo $xml->title[4]->value; ?></h4>
					</div>

					<!-- Panel Body -->
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
			                	<?php echo '<p>'. $xml->third_section[4]->description .'</p>'; ?>
			                	
			                	<?php echo '<p id="simulation_warning" style="display:none">' .$xml->third_section[5]->description . '</p>' ?>
			                	<ul id="filled_algorithms">
			                	</ul>
							</div>

							<!-- Executar Simulacao -->
							<center>
								<button type="button" class="btn btn-primary btn-lg" onclick="simular()" style="width:50%"><?php echo $xml->item[6]->value; ?></button>
							</center>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div> <!-- Fim div container -->
    
    <?php 
	if($lingua == null) {
		echo '<p id="lang" hidden="true">en</p>';
	} else {
		echo '<p id="lang" hidden="true">' . $lingua .'</p>';
	}
	?>

    <div class="navbar navbar-default navbar-fixed-bottom navbar-inverse">
        <p class="navbar-text">&copy; <?php echo date("Y"); echo " " . $xml->item[21]->value; ?></p>
    </div>
   
   
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
