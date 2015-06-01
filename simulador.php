<?php
// Prevent caching.
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 01 Jan 1996 00:00:00 GMT');

// The JSON standard MIME header.
header('Content-type: application/json');

// This ID parameter is sent by our javascript client.
$id = $_GET['id'];

// Here's some data that we want to send via JSON.
// We'll include the $id parameter so that we
// can show that it has been passed in correctly.
// You can send whatever data you like.
$data = array("Hello", $id);

// Send the data.
echo json_encode($data);
?>

<!DOCTYPE html>

<html>

<head>
        <meta charset="UTF-8">
        <title>Insert title here</title>
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
					<p id="descricao_algoritimo">But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain 
							was born and I will give you a complete account of the system, and expound the actual 
							teachings of the great explorer of the truth, the master-builder of human happiness. 
							No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because
					</p>
				</div>
			
                <div class="col-md-6" style="background-color:lightgreen">
                	<h3>CPU</h3>
                	<canvas id="canvas_cpu" class="col-md-12"> </canvas>
                </div>

				<div class="col-md-3" style="background-color:lightblue">			
					<h3>Opcoes</h3>
					<div class="row">
						<div class="col-md-12">
                            <button type="button" onclick="">Avancar</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
                    		<button type="button" onclick="">Voltar</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
                            <button type="button" onclick="">Automatico</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
                            <button type="button" onclick="">Resetar</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
                            <button type="button" onclick="">Home</button>
						</div>
					</div>
				</div>
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
		
		</div><!-- Fim div container -->
</body>

</html>