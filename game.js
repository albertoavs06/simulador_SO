// printf do javascript: console.log(selecionado);
window.onload=run;

// executa quando a pagina carrega, uso para evitar inconsistencias em refreshes
function run() {
	selecionaAlgoritimo();
}

// lista de processos
var processos = new Array();

var old_selecionado = "round robin";

// adicionar um novo processo na lista
function addProcesso() {
	
	var name = String.fromCharCode(65 + processos.length); // 65 = 'A'
	var tempo = document.getElementById('execution_time').value;
	var valor_opcional = document.getElementById('valor_opcional').value;
	var selecionado = $('input[name="bound"]:checked').val();	
    var algoritimo = $('input[name="algoritimo"]:checked').val();	

	// checa se e' um numero e se esta dentro dos limites 0 e 100
	if(!isNumber(tempo) || tempo < 0 || tempo > 100) {
		var campo = document.getElementById('execution_time');
		var msg = document.getElementById('execution_time_error').innerHTML;
		campo.value = "";
		campo.focus();
		alert(msg);
		return;
	}
	
	if(valor_opcional == "" || valor_opcional == null) {
		if(algoritimo == "loteria" || algoritimo == "prioridade" || algoritimo == "filas multiplas") {
			var campo = document.getElementById('valor_opcional');
			var msg;
				
			if(algoritimo == "loteria") {
				msg = document.getElementById('missing_tickets').innerHTML;
			} else {
				msg = document.getElementById('missing_priority').innerHTML;
			}
		
			campo.focus();
			alert(msg);
			return;
		}
	}

	var processo = {
		nome : name,
		tempo : tempo,
		tipo : selecionado,
		valor : valor_opcional		// pode ser o tempo de execucao, prioridade
	};
	
	processos.push(processo);

	// ############# Insercao na Tabela #################
	var table = document.getElementById("myTable");

	// Create an empty <tr> element and add it to the 1st position of the table:
	var row = table.insertRow(processos.length);

	// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);

	// Add some text to the new cells:
	cell1.innerHTML = name;
	cell2.innerHTML = tempo;
	cell3.innerHTML = selecionado;
	
	// se for algum desses tres algoritimos, tem uma coluna a mais
	if(algoritimo == "loteria" || algoritimo == "prioridade" || algoritimo == "filas multiplas") {
		var cell4 = row.insertCell(3);
		cell4.innerHTML = valor_opcional;
	}

	// alinha todas as colunas
	for(i = 0; i < row.cells.length; i++) {
		row.cells[i].style = "text-align:center";
	}
}

// remove o ultimo processo da lista
function rmProcesso() {
	if(processos.length > 0) {
        processos.pop();
//        document.getElementById("myTable").deleteRow(processos.lenght-1);
        document.getElementById("myTable").deleteRow(processos.length+1);
	}
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function selecionaAlgoritimo() {
	var selecionado = $('input[name="algoritimo"]:checked').val();
	
	// selecionou o mesmo, nao faz nada
	if(selecionado == old_selecionado) {
		return;
	}
	old_selecionado = selecionado;
	
	var segundo_campo_input = document.getElementById('valor_opcional');
	var titulo_opcional = document.getElementById('titulo_opcional');
	
	var titulo = document.getElementById('titulo_algoritimo');		
	var descricao = document.getElementById('descricao_algoritimo');	
	var vantagens = document.getElementById('vantagens_algoritimo');	
	var desvantagens = document.getElementById('desvantagens_algoritimo');	
	
	var nextTitulo = "titulo";
	var nextDescricao = "descricao";
	var nextVantangens = "vantagens";
	var nextDesvantagens = "desvantagens";
	var nextTitulo_opcional = "titulo opcional";
	
	if(selecionado == "round robin") {
		segundo_campo_input.hidden = true;
		titulo_opcional.hidden = true;
		
		nextTitulo = document.getElementById('titulo_round_robin').innerHTML;
		nextDescricao = document.getElementById('descricao_round_robin').innerHTML;
		nextVantangens = document.getElementById('vantagens_round_robin').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_round_robin').innerHTML;
		
	} else if(selecionado == 'proximo mais curto') {
		segundo_campo_input.hidden = true;
		titulo_opcional.hidden = true;		
		
		nextTitulo = document.getElementById('titulo_shortest').innerHTML;
		nextDescricao = document.getElementById('descricao_shortest').innerHTML;
		nextVantangens = document.getElementById('vantagens_shortest').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_shortest').innerHTML;	
		
	} else if (selecionado == 'loteria') {
		segundo_campo_input.hidden = false;
		titulo_opcional.hidden = false;		
		
		nextTitulo_opcional = "Numero de Tickets";
		segundo_campo_input.placeholder = "Numero de Tickets";
		
		nextTitulo = document.getElementById('titulo_lotery').innerHTML;
		nextDescricao = document.getElementById('descricao_lotery').innerHTML;
		nextVantangens = document.getElementById('vantagens_lotery').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_lotery').innerHTML;		
		
	} else if(selecionado == 'filas multiplas') {
		segundo_campo_input.hidden = false;
		titulo_opcional.hidden = false;				
		
		nextTitulo_opcional = "Prioridade na Fila";		
		segundo_campo_input.placeholder = "Prioridade na Fila";
		
		nextTitulo = document.getElementById('titulo_queues').innerHTML;
		nextDescricao = document.getElementById('descricao_queues').innerHTML;
		nextVantangens = document.getElementById('vantagens_queues').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_queues').innerHTML;
		
	} else if(selecionado == 'prioridade') {
		segundo_campo_input.hidden = false;
		titulo_opcional.hidden = false;				
		
		nextTitulo_opcional = "Prioridade na Fila";		
		segundo_campo_input.placeholder = "Prioridade na Fila";
		
		nextTitulo = document.getElementById('titulo_priority').innerHTML;
		nextDescricao = document.getElementById('descricao_priority').innerHTML;
		nextVantangens = document.getElementById('vantagens_priority').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_priority').innerHTML;
	}
	
	titulo.innerHTML = nextTitulo;	
	descricao.innerHTML = nextDescricao;	
	vantagens.innerHTML = nextVantangens;	
	desvantagens.innerHTML = nextDesvantagens;	
	titulo_opcional.innerHTML = nextTitulo_opcional;	
	
	// reseta a tabela
	var i;
	var tabela = document.getElementById("myTable");
	for(i = 0; i < processos.length + 1; i++) {
        tabela.deleteRow(0);
	}
	processos = new Array();

	// novo cabecalho
	var row = tabela.insertRow(0);
	
	// cabecalho padrao de todos
	var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);

    // Add some text to the new cells:
    cell1.innerHTML = 'Nome';
    cell2.innerHTML = 'Tempo';
    cell3.innerHTML = 'Tipo';
	
	// ajusta o cabecalho das colunas
	if(selecionado == 'filas multiplas' || selecionado == 'prioridade') {
		var cell4 = row.insertCell(3);
		cell4.innerHTML = 'Prioridade';
	} else if(selecionado == 'loteria') {
		var cell4 = row.insertCell(3);
		cell4.innerHTML = 'Numero de Tickets';
	}
	
	for(i = 0; i < row.cells.length; i++) {
		row.cells[i].style = "text-align:center";
	}
}

function tempoExecucaoSelecionado() {
	var titulo = document.getElementById('titulo_secao2');	
	var descricao = document.getElementById('descricao_secao2');

	var nextTitulo = document.getElementById('titulo_execution_time').innerHTML;
	var nextDescricao = document.getElementById('descricao_execution_time').innerHTML;
	
	titulo.innerHTML = nextTitulo;	
	descricao.innerHTML = nextDescricao;		
}

// falta fazer
function valorOpcionalSelecionado() {
	var titulo = document.getElementById('titulo_secao2');	
	var descricao = document.getElementById('descricao_secao2');

	var nextTitulo = null;
	var nextDescricao = null;
	
	var selecionado = $('input[name="algoritimo"]:checked').val();
	if(selecionado == 'loteria') {
		nextTitulo = document.getElementById('titulo_lotery_value').innerHTML;
		nextDescricao = document.getElementById('descricao_lotery_value').innerHTML;
	} else {
		nextTitulo = document.getElementById('titulo_priority_value').innerHTML;
		nextDescricao = document.getElementById('descricao_priority_value').innerHTML;
	}
	titulo.innerHTML = nextTitulo;	
	descricao.innerHTML = nextDescricao;		
}

function tipoSelecionado() {
	var titulo = document.getElementById('titulo_secao2');	
	var descricao = document.getElementById('descricao_secao2');

	var nextTitulo = "titulo";
	var nextDescricao = "descricao";	
	
	var selecionado = $('input[name="bound"]:checked').val();
	
	if(selecionado == "cpu") {
		nextTitulo = document.getElementById('titulo_cpu_bound').innerHTML;
		nextDescricao = document.getElementById('descricao_cpu_bound').innerHTML;		
		
	} else {
		nextTitulo = document.getElementById('titulo_io_bound').innerHTML;
		nextDescricao = document.getElementById('descricao_io_bound').innerHTML;	
		
	}
	
	titulo.innerHTML = nextTitulo;	
	descricao.innerHTML = nextDescricao;		
}

function show(json) {
	alert(json);
}
//
//function run() {
//	$.getJSON(
//			"/server_test.php", // The server URL 
//			{ id: 567 }, // Data you want to pass to the server.
//			show // The function to call on completion.
//	);
//}	
function simular() {
	$.getJSON("/simulador_SO/simulador.php",
			{id : 567},
			show);
}
