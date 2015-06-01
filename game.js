// printf do javascript: console.log(selecionado);

// lista de processos
var processos = new Array();

var old_selecionado = "round robin";

// adicionar um novo processo na lista
function addProcesso() {
	
	var name = String.fromCharCode(65 + processos.length); // 65 = 'A'
	var tempo = document.getElementById('execution_time').value;
	var valor_opcional = document.getElementById('valor_opcional').value;
	var selecionado = $('input[name="bound"]:checked').val();	

	if(!isNumber(tempo) || tempo < 0 || tempo > 100) {
		var campo = document.getElementById('execution_time');
		var msg = document.getElementById('execution_time_error').innerHTML;
		campo.value = "";
		campo.focus();
		alert(msg);
		return;
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
	cell1.style = "text-align:center";
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
	
	var i;
	var tabela = document.getElementById("myTable");
	for(i = 0; i < processos.length; i++) {
        tabela.deleteRow(1);
	}
	processos = new Array();
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

	var nextTitulo = document.getElementById('titulo_tempo_execucao').innerHTML;
	var nextDescricao = document.getElementById('descricao_tempo_execucao').innerHTML;
	
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

