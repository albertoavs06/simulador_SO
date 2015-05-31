// printf do javascript: console.log(selecionado);

// Recupera referencias aos canvas
var canvas_cpu = document.getElementById('canvas_cpu');
var context_cpu = canvas_cpu.getContext('2d');

var canvas_processos = document.getElementById('canvas_processos');
var context_processos = canvas_processos.getContext('2d');

//canvas.width = 800;
canvas_cpu.height = 250;
canvas_processos.height = 30;

// lista de processos
var processos = [];

// estrutura de dados para a CPU
var cpu = {
	x : 20,
	y : 20,
	altura : 50,	
	largura : 50,
	cor : '#FFF'
};

// adicionar um novo processo na lista
function addProcesso() {
	
	var name = String.fromCharCode(65 + processos.length); // 65 = 'A'
	var value = document.getElementById('tempo_novo_processo').value;	
	
	if(!isNumber(value)) {
		return;
	}
	
	var processo = {
		nome : name,
		valor : value,		// pode ser o tempo de execucao, prioridade
		x : 0,
		y : 0,
		offset : 10,
		altura : 50,
		largura : 50,
		cor : '#c11'
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
	
	if(selecionado == "round robin" || selecionado == 'proximo mais curto') {
		segundo_campo_input.hidden = true;
	} else {
		segundo_campo_input.hidden = false;
		
		if(selecionado == "loteria") {
			segundo_campo_input.placeholder = "Numero de Tickets";
		} else {
			segundo_campo_input.placeholder = "Prioridade na Fila";
		}
	}
}

function update() {
}

function render() {
	// background CPU
    context_cpu.fillStyle = '#000';
    context_cpu.fillRect(0, 0, canvas_cpu.width, canvas_cpu.height);	

	// background processos
    context_processos.fillStyle = '#000';
    context_processos.fillRect(0, 0, canvas_processos.width, canvas_processos.height);	
	
	// para cada processo
	for(i = 0; i < processos.length; i++) {
		var p = processos[i];
		
		// quadrados dos processos
		context_processos.fillStyle = p.cor;
		context_processos.fillRect(p.x + i * (p.largura + p.offset), p.y, p.largura, p.altura);	// fillRect = x inicio, y inicio, largura em x, altura em y
		
		// mensagem
		context_processos.font = '12pt Arial';
		context_processos.fillStyle = '#fff';
		context_processos.textBaseline = 'top';
		context_processos.fillText('nome ' + p.nome, p.x + i * (p.largura + p.offset), p.y);
		context_processos.fillText('valor ' + p.valor, p.x + i * (p.largura + p.offset), p.y + 12);
	}		
	
	// CPU
	context_cpu.fillStyle = cpu.cor;
	context_cpu.fillRect(cpu.x, cpu.y, cpu.largura, cpu.altura);	
}

function run() {
    update((Date.now() - time) / 1000);
    render();
    time = Date.now();
}

var time = Date.now();
setInterval(run, 10);