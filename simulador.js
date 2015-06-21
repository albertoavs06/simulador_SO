/* 
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
*/

window.onload = run;

var mensagens = null;
var estados = null;
var cpu = null;
var tempo_execucao = null;
var switches = null;
var step = 0;
var algoritmo = null;

function clearTable(tabela) {
	// para cada linha na tabela, exceto a primeira que e' o header
	var nrows = tabela.rows.length;
	for(i = 1; i < nrows; i++) {
        tabela.deleteRow(1);
	}
}

function load(id) {
	array = new Array();
	array.push("");
	var n = document.getElementById(id).innerHTML;
	for(var i = 0; i < n; i++) {
		var valor = document.getElementById(id + i).innerHTML;
		array.push(valor);
	}
	return array;
}

function run() {
	var offset = $('#ponto_alinhamento').offset().top;
	$('html, body').scrollTop(offset);
	
	// inicializa os parametros de simulacao
	var aux = ['quantum', 'switch_cost', 'io_time', 'processing_until_io'];
	for(var i = 0; i < aux.length; i++) {
		var tmp = document.getElementById(aux[i]).innerHTML;
		document.getElementById('campo_' + aux[i]).innerHTML = tmp;
	}
	
	// inicializa o titulo
	algoritmo = document.getElementById('current_algorithm').innerHTML;
	
	if(document.getElementById(algoritmo) == null) {
	
		var i = 0;
		while(true) {
			var tab = document.getElementById('tabela' + i);
			if(tab == null) {
				break;
			}

			var dados = document.getElementById('resultado' + i).innerHTML;
			dados = dados.substring(1, dados.length - 1);

			var tmp = dados.split(/},{/);

			for(var j = 0; j < tmp.length; j++) {
				// primeiro elemento falta um }
				if(tmp.length == 1) {
					// tudo certo
				}
				else if(j == 0) {
					tmp[j] = tmp[j] + "}";
				} 
				// ultimo falta um {
				else if(j == tmp.length-1) {
					tmp[j] = "{" + tmp[j];
				} 
				// os outros faltam { e }
				else {
					tmp[j] = "{" + tmp[j] + "}";
				}

				var object = jQuery.parseJSON(tmp[j]);
				var row = tab.insertRow(tab.rows.length);
	
				var cell = row.insertCell(0);
				cell.innerHTML = object.nome;
				cell.style = "text-align:center";

				cell = row.insertCell(1);
				cell.innerHTML = object.tempo;
				cell.style = "text-align:center";

				cell = row.insertCell(2);
				cell.innerHTML = object.tipo;
				cell.style = "text-align:center";
				
				if(object.valor != "") {
					cell = row.insertCell(3);
					cell.innerHTML = object.valor;
					cell.style = "text-align:center";
				}
			}
			i = i + 1;
		}
		return;
	}

	var valor = document.getElementById(algoritmo).innerHTML;
	document.getElementById('current_algorithm').innerHTML = valor;

	if(mensagens == null) {
		//mensagens = load('msg');
		mensagens = new Array();
		mensagens.push("");
		var n = document.getElementById('msg').innerHTML;
		for(var i = 0; i < n; i++) {
			var node_list = document.getElementsByName('msg' + i);
			var array = new Array();
			
			for(var j = 0; j < node_list.length; j++) {
				var valor = node_list[j].innerHTML;
				array.push(valor);
			}
			mensagens.push(array);
		}
	}
	
	if(switches == null) {
		switches = load('switches');
	}
	
	if(cpu == null) {
		cpu = load('cpu');
	}
	
	if(tempo_execucao == null) {
		tempo_execucao = load('tte');
		tempo_execucao.shift();		// pq tem um elemento a mais no tempo de execucao
	}
	
	// se array de estados nao esta inicializado, inicializa ele
	if(estados == null) {
		estados = new Array();	// instancia um array
		var n = document.getElementById("status").innerHTML; // status tem o numero de estados 
		for(var i = 0; i < n; i++) {
			var valor = document.getElementById("status" + i).innerHTML; // recupera as informacoes de cada estado

			// cada estado tem processos prontos e bloqueados
			processos_prontos = new Array();
			processos_bloqueados = new Array();

			// quebra a string na virgula
			var texto = valor.split(/,/);
			var myRegex = /(\w+):(\w+):(\d+):(\d+):(\S+)]/;
		
			for(var k = 0; k < texto.length; k++) {
				// quebra os processos prontos pelo espaco em branco
				var tmp = texto[k].split(/\s/);
						
				for(var j = 0; j < tmp.length; j++) {
					if(tmp[j] != "") {
						var match = myRegex.exec(tmp[j]);
						var processo = new Array(match[1], match[2], match[3], match[4], match[5]);
						
						if(k == 0) {
							processos_prontos.push(processo);
						} else {
							processos_bloqueados.push(processo);
						}
					}
				}
			}
			estados.push([processos_prontos, processos_bloqueados]);
		} 
		// adiciona um estado a mais para compensar a mensagem atrasada
		estados.push([new Array(), new Array()]);
	} 
	console.log('estados ' + estados.length);
	console.log('mensagens ' + mensagens.length);
	console.log('cpu ' + cpu.length);
	console.log('tempo execucao ' + tempo_execucao.length);
	console.log('switches ' + switches.length);
	
	var ready_process_value = document.getElementById('ready_process').innerHTML;
	var blocked_process 	= document.getElementById('blocked_process').innerHTML;
	var name_value 			= document.getElementById('name').innerHTML;
	var remaining_time_value = document.getElementById('remaining_time').innerHTML;
	var io_remaining_time_value = document.getElementById('io_remaining_time').innerHTML;
	var process_type_value 	= document.getElementById('type').innerHTML;

	var tickets_value = document.getElementById('tickets').innerHTML;
	
	var campos_tabela = new Array(name_value, process_type_value, remaining_time_value);
	var campos_bloqueados = new Array(name_value, process_type_value, remaining_time_value, io_remaining_time_value);
	
	if(algoritmo == 'lotery') {
		campos_tabela.push(tickets_value);
		campos_bloqueados.push(tickets_value);
	}

	inicializa_tabela('myTable', campos_tabela);
	inicializa_tabela('myTable2', campos_bloqueados);
	
	// tabelas extras
	var tabelas_extras = document.getElementById('tabelas_extras');
	if(algoritmo == 'priority' || algoritmo == 'queues') {
		inicializa_tabela('myTable3', campos_tabela)
		inicializa_tabela('myTable4', campos_tabela)
		inicializa_tabela('myTable5', campos_tabela)
		
		tmp = document.getElementById("myHeader1").innerHTML;
		document.getElementById("myHeader1").innerHTML = tmp + " [1]";

		var tmp = document.getElementById("myHeader3").innerHTML;
		document.getElementById("myHeader3").innerHTML = tmp + " [2]";

		tmp = document.getElementById("myHeader4").innerHTML;
		document.getElementById("myHeader4").innerHTML = tmp + " [3]";

		tmp = document.getElementById("myHeader5").innerHTML;
		document.getElementById("myHeader5").innerHTML = tmp + " [4]";
		
		document.getElementById('coluna1').className = "col-md-2";
		document.getElementById('coluna2').className = "col-md-4";
	} else {
		tabelas_extras.style = 'display:none';
		document.getElementById('coluna1').className = "col-md-6";
		document.getElementById('coluna2').className = "col-md-6";
	}
    
    reset();
}

function inicializa_tabela(nome, headers) {
	// inicializa a tabela
	var tabela = document.getElementById(nome);

	// novo cabecalho
	var row = tabela.insertRow(0);
	
	for(var i = 0; i < headers.length; i++) {
		// cabecalho padrao de todos
		var cell = row.insertCell(i);

		// Add some text to the new cells:
		cell.innerHTML = headers[i];

		// alinha todas as colunas
		cell.style = "text-align:center";
	}
}

function home() {
	//window.history.back();	
	window.location.href = "simulador.php";
}

function atualiza() {
	// atualiza a mensagem
	var mensagem = 'Tempo Total de Exeucao: ' + tempo_execucao[step] + '\n';
	mensagem = mensagem + 'Uso da CPU: ' + cpu[step] + '\n';
	mensagem = mensagem + 'Trocas de Contexto: ' + switches[step] + '\n';
	
	mensagem = mensagem + 'Acao executada: ';
	for(var i = 0; i < mensagens[step].length; i++) {
		mensagem = mensagem + mensagens[step][i] + '\n';
	}
	
	document.getElementById("descricao_algoritimo").innerHTML = mensagem;

	if(algoritmo == 'priority' || algoritmo == 'queues') {
		var tabelas = new Array(document.getElementById('myTable'),
								document.getElementById('myTable3'),
								document.getElementById('myTable4'),
								document.getElementById('myTable5')
								);
		
		// limpa as tabelas antes de inserir
		for(var i = 0; i < tabelas.length; i++) {
			clearTable(tabelas[i]);
		}
		
		// processos prontos
		var processos_prontos = estados[step][0];
		for(var i = 0; i < processos_prontos.length; i++) {
			var processo = processos_prontos[i];
			var tamanho = processo.length - 1;

			var tabela = tabelas[processo[4]];
			var row = tabela.insertRow(tabela.rows.length);

			for(var j = 0; j < tamanho; j++) {
				if(j != 3) {
					var cell = row.insertCell(row.cells.length);
					cell.innerHTML = processos_prontos[i][j];
					cell.style = "text-align:center";
				}
			}
		}
		
	} else {
		// atualiza a tabela
		var tabela = document.getElementById("myTable");
		clearTable(tabela);
				
		// processos prontos
		var processos_prontos = estados[step][0];
		for(var i = 0; i < processos_prontos.length; i++) {
			var row = tabela.insertRow(tabela.rows.length);
			var tamanho = processos_prontos[i].length;
						
			// se for o round robin ou o mais curto nao tem a ultima coluna
			if(algoritmo == 'round_robin' || algoritmo == 'shortest') {
				tamanho = tamanho - 1;
			}
		
			for(var j = 0; j < tamanho; j++) {
				if(j != 3) {
					var cell = row.insertCell(row.cells.length);
					cell.innerHTML = processos_prontos[i][j];
					cell.style = "text-align:center";
				}
			}
		}
	}

	// atualiza a tabela
	var tabela = document.getElementById("myTable2");
	clearTable(tabela);
		
	// processos bloquedos
	var processos_bloqueados = estados[step][1];
	for(var i = 0; i < processos_bloqueados.length; i++) {
		var row = tabela.insertRow(tabela.rows.length);
		var tamanho = processos_bloqueados[i].length;
				
		// se for o round robin ou o mais curto nao tem a ultima coluna
		if(algoritmo == 'round_robin' || algoritmo == 'shortest') {
			tamanho = tamanho - 1;
		}
				
		for(var j = 0; j < tamanho; j++) {
			var cell = row.insertCell(j);
			cell.innerHTML = processos_bloqueados[i][j];
			cell.style = "text-align:center";
		}
	}
}

function next() {
	if(step + 1 < mensagens.length) {
		step = step + 1;
		atualiza()
	}
}

function previous() {
	if(step > 0) {
		step = step - 1;
		atualiza();
	}
}

function auto() {
	step = estados.length - 1;
	atualiza();
}

function reset() {
	step = 0;
	atualiza();
}