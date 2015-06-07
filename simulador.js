window.onload = run;

var mensagens;
var status;
var step = 0;

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
		mensagens = new Array();
		var n = document.getElementById("msg").innerHTML;
		for(var i = 0; i < n; i++) {
			var valor = document.getElementById("msg" + i).innerHTML;
			mensagens.push(valor);
		}
	}

	if(status == null) {
		status = new Array();
		var n = document.getElementById("status").innerHTML;
		for(var i = 0; i < n; i++) {
			var valor = document.getElementById("status" + i).innerHTML;
			status.push(valor);
		}
	}
}

function home() {
	window.history.back();	
}

function next() {
	if(step + 1 < mensagens.length) {
		step = step + 1;
		document.getElementById("descricao_algoritimo").innerHTML = mensagens[step];
	}
}

function previous() {
	if(step > 0) {
		step = step - 1;
		document.getElementById("descricao_algoritimo").innerHTML = mensagens[step];
	}
}

function auto() {
}

function reset() {
	
}