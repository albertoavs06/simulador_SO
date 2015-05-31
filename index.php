<!DOCTYPE html>

<html>
	<head>
		<title>Simulador: Escalonamento em Sistemas Interativos</title>
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
				<h1>Escalonamento em Sistemas Interativos</h1>
				<h3>Marcelo Koti Kamada & Maria Lydia Fioravanti</h3>
			</div>
		</div>

		<!-- Titulo da primeira secao -->
		<div class="row">
			<div class="col-md-12" style="background-color:lightblue">
				<h2>1 - Escolha o algoritimo de escalonamento</h2>
			</div>
		</div>
			
		<!-- Duas colunas, uma para escolher o algoritimo e a outra para a descricao -->
		<!-- style="height: 50%" -->
		<div class="row">
			<div class="row-eq-height">
				<div class="col-md-5" style="background-color:lightblue">			
					<p style="font-weight: bold;"><input type="radio" name="algoritimo" value="round robin" checked onclick="selecionaAlgoritimo()">Round Robin</p>
					<p style="font-weight: bold;"><input type="radio" name="algoritimo" value="prioridade" onclick="selecionaAlgoritimo()">Prioridade</p>
					<p style="font-weight: bold;"><input type="radio" name="algoritimo" value="filas multiplas" onclick="selecionaAlgoritimo()">Filas Multiplas</p>
					<p style="font-weight: bold;"><input type="radio" name="algoritimo" value="proximo mais curto" onclick="selecionaAlgoritimo()">Proximo mais curto</p>
					<p style="font-weight: bold;"><input type="radio" name="algoritimo" value="loteria" onclick="selecionaAlgoritimo()">Loteria</p>			
				</div>
			
				<div class="col-md-7" style="background-color:lightblue">
					<!-- Breve descricao do algoritimo-->
					<h4 id="titulo_algoritimo">Round Robin</h4>
					<p id="descricao_algoritimo">But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain 
							was born and I will give you a complete account of the system, and expound the actual 
							teachings of the great explorer of the truth, the master-builder of human happiness. 
							No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because
					</p>
					
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
		<div class="row" style="background-color:lightgrey">
			<div class="col-md-12">
				<h2>2 - Adicione processos para serem escalonados</h2>
			</div>
		</div>	

		<div class="row" style="background-color:orange">
			<div class="col-md-5">
				<!-- Menu para adicionar novos processos -->
				<h4>Tempo de Execucao</h4>
				<input type="text" id='tempo_novo_processo' placeholder="Tempo de Execucao" onfocus="tempoExecucaoSelecionado()">			
			
				<!-- Campo opcional para alguns algoritimos -->
				<div>
					<h4  id="titulo_opcional" hidden="true">Valor Opcional</h4>
					<input type="text" id="valor_opcional" placeholder="prioridade ou tickets" hidden="true" onfocus="valorOpcionalSelecionado()">
				</div>			
			
				<!-- CPU ou IO bound -->
				<div>
					<h4>Tipo do Processo</h4>
					<p><input type="radio" name="bound" value="cpu" checked onclick="tipoSelecionado()">CPU bound</p>
					<p><input type="radio" name="bound" value="io" onclick="tipoSelecionado()">I/O bound</p>
				</div>			
			
				<button type="button" onclick="addProcesso()">Adicionar</button>
			</div>	
			
			<div class="col-md-7">
				<h4 id="titulo_secao2">Titulo</h4>
				<p id="descricao_secao2">But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain 
						was born and I will give you a complete account of the system, and expound the actual 
						teachings of the great explorer of the truth, the master-builder of human happiness. 
						No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because
				</p>				
			</div>	
		</div>
		
		<div class="row" style="background-color:gray">		
			<div class="col-md-12">
				<table style="width:100%">
				  <tr>
					<th>Nome</th>
					<th>Tempo</th> 
					<th>Tipo</th>
				  </tr>
				  <tr>
					<td>A</td>
					<td>100</td> 
					<td>I/O bound</td>
				  </tr>
				  
				  <tr>
					<td>B</td>
					<td>70</td> 
					<td>CPU bound</td>
				  </tr>

				  <tr>
					<td>C</td>
					<td>120</td> 
					<td>I/O bound</td>
				  </tr>				  
				</table>
			</div>
		</div>	
			
		<div class="row" style="background-color:blue">	
			<div class="col-md-12">
				<!-- Executar Simulacao -->
				<center>
					<input type="submit" value="Executar Simulacao" style="width: 50%">
				</center>
			</div>
		</div>
			
		</div><!-- Fim div container -->
		
    	<?php
    	    $xml = simplexml_load_file("lang/english.xml") or die("Error: Cannot create object");

            // ################### Descricoes dos algoritimos ###################
    	    foreach($xml->algorithm as $algoritimo):
    	    		$campo = $algoritimo['name'];
    	            $titulo = $algoritimo->title;
    	            $descricao = $algoritimo->description;
    	            $vantagens = $algoritimo->pros;
    	            $desvantagens = $algoritimo->cons;
    	            
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
		?>
		<!-- Script javascript para as funcoes -->
		<script type="text/javascript" src="game.js"></script>		
	</body>
</html>

<?php
// restart no apache
// sudo systemctl restart apache2
?>
