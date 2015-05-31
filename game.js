// printf do javascript: console.log(selecionado);

// lista de processos
var processos = [];

// adicionar um novo processo na lista
function addProcesso() {
	
	var name = String.fromCharCode(65 + processos.length); // 65 = 'A'
	var tempo = document.getElementById('tempo_novo_processo').value;
	var valor_opcional = document.getElementById('valor_opcional').value;
	
	if(!isNumber(tempo)) {
		return;
	}
	
	var processo = {
		nome : name,
		tempo : tempo,
		valor : valor_opcional		// pode ser o tempo de execucao, prioridade
	};
	
	processos.push(processo);
}

// remove o ultimo processo da lista
function rmProcesso() {
	processos.pop();
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function selecionaAlgoritimo() {
	var selecionado = $('input[name="algoritimo"]:checked').val();
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
		
		nextTitulo = document.getElementById('titulo_mais_curto').innerHTML;
		nextDescricao = document.getElementById('descricao_mais_curto').innerHTML;
		nextVantangens = document.getElementById('vantagens_mais_curto').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_mais_curto').innerHTML;	
		
	} else if (selecionado == 'loteria') {
		segundo_campo_input.hidden = false;
		titulo_opcional.hidden = false;		
		
		nextTitulo_opcional = "Numero de Tickets";
		segundo_campo_input.placeholder = "Numero de Tickets";
		
		nextTitulo = document.getElementById('titulo_loteria').innerHTML;
		nextDescricao = document.getElementById('descricao_loteria').innerHTML;
		nextVantangens = document.getElementById('vantagens_loteria').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_loteria').innerHTML;		
		
	} else if(selecionado == 'filas multiplas') {
		segundo_campo_input.hidden = false;
		titulo_opcional.hidden = false;				
		
		nextTitulo_opcional = "Prioridade na Fila";		
		segundo_campo_input.placeholder = "Prioridade na Fila";
		
		nextTitulo = document.getElementById('titulo_filas_multiplas').innerHTML;
		nextDescricao = document.getElementById('descricao_filas_multiplas').innerHTML;
		nextVantangens = document.getElementById('vantagens_filas_multiplas').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_filas_multiplas').innerHTML;
		
	} else if(selecionado == 'prioridade') {
		segundo_campo_input.hidden = false;
		titulo_opcional.hidden = false;				
		
		nextTitulo_opcional = "Prioridade na Fila";		
		segundo_campo_input.placeholder = "Prioridade na Fila";
		
		nextTitulo = document.getElementById('titulo_prioridade').innerHTML;
		nextDescricao = document.getElementById('descricao_prioridade').innerHTML;
		nextVantangens = document.getElementById('vantagens_prioridade').innerHTML;
		nextDesvantagens = document.getElementById('desvantagens_prioridade').innerHTML;
	}
	
	titulo.innerHTML = nextTitulo;	
	descricao.innerHTML = nextDescricao;	
	vantagens.innerHTML = nextVantangens;	
	desvantagens.innerHTML = nextDesvantagens;	
	titulo_opcional.innerHTML = nextTitulo_opcional;	
}

function tempoExecucaoSelecionado() {
	var titulo = document.getElementById('titulo_secao2');	
	var descricao = document.getElementById('descricao_secao2');

	var nextTitulo = document.getElementById('titulo_tempo_execucao').innerHTML;
	var nextDescricao = document.getElementById('descricao_tempo_execucao').innerHTML;
	
	titulo.innerHTML = nextTitulo;	
	descricao.innerHTML = nextDescricao;		
}

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
	
	var selecionado = $('input[name="algoritimo"]:checked').val();
	
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

