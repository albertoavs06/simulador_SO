window.onload = run;

var mensagens = null;
var estados = null;
var cpu = null;
var tempo_execucao = null;
var switches = null;
var step = 0;

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
	
	var aux = ['quantum', 'switch_cost', 'io_time', 'processing_until_io'];
	
	// inicializa o titulo
	var tmp = document.getElementById('current_algorithm').innerHTML;
	var valor = document.getElementById(tmp).innerHTML;
	document.getElementById('current_algorithm').innerHTML = valor;

	// e inicializa os parametros de simulacao
	for(var i = 0; i < aux.length; i++) {
		var tmp = document.getElementById(aux[i]).innerHTML;
		document.getElementById('campo_' + aux[i]).innerHTML = tmp;
	}

	if(mensagens == null) {
		mensagens = load('msg');
	}
	
	if(switches == null) {
		switches = load('switches');
	}
	
	if(cpu == null) {
		cpu = load('cpu');
	}
	
	if(tempo_execucao == null) {
		tempo_execucao = load('tte');
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
			var myRegex = /(\w):(\d+):(\d+)/;
		
			for(var k = 0; k < texto.length; k++) {
				// quebra os processos prontos pelo espaco em branco
				var tmp = texto[k].split(/\s/);
						
				for(var j = 0; j < tmp.length; j++) {
					if(tmp[j] != "") {
						var match = myRegex.exec(tmp[j]);
						var processo = new Array(match[1], match[2], match[3]);
						
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
	} 
	
	var ready_process_value = document.getElementById('ready_process').innerHTML;
	var blocked_process 	= document.getElementById('blocked_process').innerHTML;
	var name_value 			= document.getElementById('name').innerHTML;
	var remaining_time_value = document.getElementById('remaining_time').innerHTML;
	var io_remaining_time_value = document.getElementById('io_remaining_time').innerHTML;
	var process_type_value 	= document.getElementById('type').innerHTML;

	inicializa_tabela('myTable', new Array(name_value, remaining_time_value, process_type_value));
	inicializa_tabela('myTable2', new Array(name_value, remaining_time_value, process_type_value, io_remaining_time_value));
    
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
	window.history.back();	
}

function atualiza() {
	// atualiza a mensagem
	var mensagem = 'Uso da CPU: ' + cpu[step] + '\n';
	mensagem = mensagem + 'Trocas de Contexto: ' + switches[step] + '\n';
	mensagem = mensagem + 'Acao executada: ' + mensagens[step];
	
	document.getElementById("descricao_algoritimo").innerHTML = mensagem;

	// atualiza a tabela
	var tabela = document.getElementById("myTable");
	clearTable(tabela);
		
	// processos prontos
	var processos_prontos = estados[step][0];
	for(var i = 0; i < processos_prontos.length; i++) {
		var row = tabela.insertRow(tabela.rows.length);
				
		for(var j = 0; j < processos_prontos[i].length; j++) {
			var cell = row.insertCell(j);
			cell.innerHTML = processos_prontos[i][j];
			cell.style = "text-align:center";
			
		}
	}

	// atualiza a tabela
	var tabela = document.getElementById("myTable2");
	clearTable(tabela);
		
	// processos prontos
	var processos_bloqueados = estados[step][1];
	for(var i = 0; i < processos_bloqueados.length; i++) {
		var row = tabela.insertRow(tabela.rows.length);
				
		for(var j = 0; j < processos_bloqueados[i].length; j++) {
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
}

function reset() {
	step = 0;
	atualiza();
}