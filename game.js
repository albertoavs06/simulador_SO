// printf do javascript: console.log(selecionado);
// sudo apt-get install spyder3
//http://getbootstrap.com/javascript/#collapse
window.onload=run;

// lista de processos
var processos = new Array();
var old_selecionado;

// algoritimos
var loteria;
var filas;
var prioridade;
var round_robin;
var proximo_mais_curto;

// executa quando a pagina carrega, uso para evitar inconsistencias em refreshes
function run() {
	// inicializacao das variaveis com os valores dos radios buttons
	round_robin = document.getElementById('valor_round_robin').innerHTML;
	filas = document.getElementById('valor_queues').innerHTML;
	prioridade = document.getElementById('valor_priority').innerHTML;
	proximo_mais_curto = document.getElementById('valor_shortest').innerHTML;
	loteria = document.getElementById('valor_lotery').innerHTML;

	// cada algoritimo e' uma lista e processos e' uma lista com as listas de processos dos algoritimos
	processos[round_robin] = new Array();
	processos[filas] = new Array();
	processos[prioridade] = new Array();
	processos[proximo_mais_curto] = new Array();
	processos[loteria] = new Array();

	// inicializa a descricao do algoritimo selecionado
	//old_selecionado = round_robin;
	selecionaAlgoritimo();
	form = document.getElementById('myForm');
}

// adicionar um novo processo na lista
function addProcesso() {
	
	var tempo = document.getElementById('execution_time').value;
	var valor_opcional = document.getElementById('valor_opcional').value;
	var selecionado = $('input[name="bound"]:checked').val();	
	var algoritimo = $('input[name="algoritimo"]:checked').val();	
	var name = String.fromCharCode(65 + processos[algoritimo].length); // 65 = 'A'

	// checa se e' um numero e se esta dentro dos limites 0 e 100
	if(!isNumber(tempo) || tempo <= 0 || tempo > 100) {
		var campo = document.getElementById('execution_time');
		var msg = document.getElementById('execution_time_error').innerHTML;
		campo.value = "";
		campo.focus();
		alert(msg);
		return;
	}
	
	// se for algum desses algoritimos, tem que ter valor no campo opcional
	if(algoritimo == loteria || algoritimo == prioridade || algoritimo == filas) {
		// se o campo esta vazio, mostra uma mensagem de erro
		if(valor_opcional == "" || valor_opcional == null) {
			var campo = document.getElementById('valor_opcional');
			var msg;
				
			if(algoritimo == loteria) {
				msg = document.getElementById('missing_tickets').innerHTML;
			} else {
				msg = document.getElementById('missing_priority').innerHTML;
			}
		
			campo.focus();
			alert(msg);
			return;
		} 
		// se o campo estiver preenchido, tem que ver se o valor esta dentro do esperado
		else {
			if(algoritimo == loteria) {
				if(!isNumber(valor_opcional) || valor_opcional <= 0 || valor_opcional > 100) {
					var msg = document.getElementById('missing_tickets').innerHTML;
					alert(msg);
				}
			} else {
				if(!isNumber(valor_opcional) || valor_opcional <= 0 || valor_opcional > 4) {
					var msg = document.getElementById('missing_priority').innerHTML;
					alert(msg);
                }
			}
		}
	}

	var processo = {
		nome : name,
		tempo : tempo,
		tipo : selecionado,
		valor : valor_opcional		// pode ser o tempo de execucao, prioridade
	};
	
	processos[algoritimo].push(processo);

	// ############# Insercao na Tabela #################
	var table = document.getElementById("myTable");

	// Create an empty <tr> element and add it to the 1st position of the table:
	var row = table.insertRow(processos[algoritimo].length);

	// Insert new cells (<td> elements) at the 1st and 2nd position of the "new" <tr> element:
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);

	// Add some text to the new cells:
	cell1.innerHTML = name;
	cell2.innerHTML = tempo;
	cell3.innerHTML = selecionado;
	
	// se for algum desses tres algoritimos, tem uma coluna a mais
	if(algoritimo == loteria || algoritimo == prioridade || algoritimo == filas) {
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
	var algoritimo = $('input[name="algoritimo"]:checked').val();

	if(processos[algoritimo].length > 0) {
        processos[algoritimo].pop();
//        document.getElementById("myTable").deleteRow(processos.lenght-1);
        document.getElementById("myTable").deleteRow(processos[algoritimo].length+1);
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
	
	if(selecionado == round_robin) {
		segundo_campo_input.hidden = true;
		titulo_opcional.hidden = true;
		
		nextTitulo = document.getElementById('titulo_round_robin').innerHTML;
		nextDescricao = document.getElementById('descricao_round_robin').innerHTML;
		nextVantangens = document.getElementById('vantagens_round_robin').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_round_robin').innerHTML;
		
	} else if(selecionado == proximo_mais_curto) {
		segundo_campo_input.hidden = true;
		titulo_opcional.hidden = true;		
		
		nextTitulo = document.getElementById('titulo_shortest').innerHTML;
		nextDescricao = document.getElementById('descricao_shortest').innerHTML;
		nextVantangens = document.getElementById('vantagens_shortest').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_shortest').innerHTML;	
		
	} else if (selecionado == loteria) {
		segundo_campo_input.hidden = false;
		titulo_opcional.hidden = false;		
		
		nextTitulo_opcional = "Numero de Tickets";
		segundo_campo_input.placeholder = "Numero de Tickets";
		
		nextTitulo = document.getElementById('titulo_lotery').innerHTML;
		nextDescricao = document.getElementById('descricao_lotery').innerHTML;
		nextVantangens = document.getElementById('vantagens_lotery').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_lotery').innerHTML;		
		
	} else if(selecionado == filas) {
		segundo_campo_input.hidden = false;
		titulo_opcional.hidden = false;				
		
		nextTitulo_opcional = "Prioridade na Fila";		
		segundo_campo_input.placeholder = "Prioridade na Fila";
		
		nextTitulo = document.getElementById('titulo_queues').innerHTML;
		nextDescricao = document.getElementById('descricao_queues').innerHTML;
		nextVantangens = document.getElementById('vantagens_queues').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_queues').innerHTML;
		
	} else if(selecionado == prioridade) {
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
	
	var nrows = tabela.rows.length;
	for(i = 0; i < nrows; i++) {
        tabela.deleteRow(0);
	}
	//processos = new Array();

	// novo cabecalho
	var row = tabela.insertRow(0);
	
	// cabecalho padrao de todos
	var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);

    // Add some text to the new cells:
    cell1.innerHTML = document.getElementById('name').innerHTML;
    cell2.innerHTML = document.getElementById('time').innerHTML;
    cell3.innerHTML = document.getElementById('type').innerHTML;
	
	// ajusta o cabecalho das colunas
	if(selecionado == filas || selecionado == prioridade) {
		var cell4 = row.insertCell(3);
		cell4.innerHTML = document.getElementById('priority').innerHTML;
	} else if(selecionado == loteria) {
		var cell4 = row.insertCell(3);
		cell4.innerHTML = document.getElementById('tickets').innerHTML;
	}
	
	for(i = 0; i < row.cells.length; i++) {
		row.cells[i].style = "text-align:center";
	}

	// agora insere de volta os processos desse algoritimo
	for(i = 0; i < processos[selecionado].length; i++) {
		var insert_point = tabela.rows.length;
		var row = tabela.insertRow(insert_point); // ?

		var cell1 = row.insertCell(0);
    	var cell2 = row.insertCell(1);
    	var cell3 = row.insertCell(2);

    	var processo = processos[selecionado][i];
    	cell1.innerHTML = processo.nome;
   		cell2.innerHTML = processo.tempo;
   		cell3.innerHTML = processo.tipo;
	
   		// se for algum desses tres algoritimos, tem uma coluna a mais
   		if(selecionado == loteria || selecionado == prioridade || selecionado == filas) {
   			var cell4 = row.insertCell(3);
   			cell4.innerHTML = processo.valor_opcional;
   		}

   		// alinha todas as colunas
   		for(var j = 0; j < row.cells.length; j++) {
   			row.cells[j].style = "text-align:center";
   		}
	}
	old_selecionado = selecionado;
}

function tempoExecucaoSelecionado() {
	var titulo = document.getElementById('titulo_secao2');	
	var descricao = document.getElementById('descricao_secao2');

	var nextTitulo = document.getElementById('titulo_execution_time').innerHTML;
	var nextDescricao = document.getElementById('descricao_execution_time').innerHTML;
	
	titulo.innerHTML = nextTitulo;	
	descricao.innerHTML = nextDescricao;		
}

function terceiraSecaoSelecionada(element) {
	var id = element.id;

	var titulo = document.getElementById('titulo_secao3');	
	var descricao = document.getElementById('descricao_secao3');

	var nextTitulo;
	var nextDescricao;

	if(id == "quantum") {
		nextTitulo = document.getElementById('titulo_quantum').innerHTML;
		nextDescricao = document.getElementById('descricao_quantum').innerHTML;
	} else if(id == "switch") {
		nextTitulo = document.getElementById('titulo_switch_cost').innerHTML;
		nextDescricao = document.getElementById('descricao_switch_cost').innerHTML;
	} else if(id == "io_time") {
		nextTitulo = document.getElementById('titulo_io_time').innerHTML;
		nextDescricao = document.getElementById('descricao_io_time').innerHTML;
	} else if(id == "until_io") {
		nextTitulo = document.getElementById('titulo_processing_until_io').innerHTML;
		nextDescricao = document.getElementById('descricao_processing_until_io').innerHTML;
	}
	
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

function simular() {
	
	var mensagem = "";
	var flag = 0;
	
	if(processos[round_robin].length != 0) 	{ 
		mensagem = mensagem + "round_robin=" + JSON.stringify(processos[round_robin]) + "&";
		flag = 1;
	} else {
		mensagem = mensagem + "round_robin=null&";
	}

	if(processos[loteria].length != 0) { 
		mensagem = mensagem + "lotery=" + JSON.stringify(processos[loteria]) + "&";
		flag = 1;
	} else {
		mensagem = mensagem + "lotery=null&";
	}

	if(processos[filas].length != 0) { 
		mensagem = mensagem + "queues=" + JSON.stringify(processos[filas]) + "&";
		flag = 1;
	} else {
		mensagem = mensagem + "queues=null&";
	}

	if(processos[prioridade].length != 0) { 
		mensagem = mensagem + "priority=" + JSON.stringify(processos[prioridade]) + "&";
		flag = 1;
	} else {
		mensagem = mensagem + "priority=null&";
	}

	if(processos[proximo_mais_curto] != 0) 	{ 
		mensagem = mensagem + "shortest=" + JSON.stringify(processos[proximo_mais_curto]) + "&";
		flag = 1;
	} else {
		mensagem = mensagem + "shortest=null&";
	}

	if(flag == 0) {
		var error = document.getElementById('no_process').innerHTML;
		alert(error);
	} else {
		// para mandar coisas pela url como JSON
		mensagem = mensagem.substring(0, mensagem.length-1);
		window.location.href = "teste.php?" + mensagem;
	}
}

