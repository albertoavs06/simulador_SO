window.onload = run;

var mensagens = null;
var estados = null;
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

	if(estados == null) {
		estados = new Array();
		var n = document.getElementById("status").innerHTML;
		for(var i = 0; i < n; i++) {
			var valor = document.getElementById("status" + i).innerHTML;
			estados.push(valor);
		}
	} 

	// inicializa a tabela
	var tabela = document.getElementById("myTable");

	// novo cabecalho
	var row = tabela.insertRow(0);
	
	// cabecalho padrao de todos
	var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);

    // Add some text to the new cells:
    cell1.innerHTML = 'Nome';
    cell2.innerHTML = 'Tempo Restante';
    cell3.innerHTML = 'Estado';
}

function home() {
	window.history.back();	
}

function next() {
	if(step + 1 < mensagens.length) {
		step = step + 1;
		document.getElementById("descricao_algoritimo").innerHTML = mensagens[step];
		
		var tabela = document.getElementById("myTable");

		// quebra a string na virgula
		var texto = estados[step].split(/,/);
		var myRegex = /(\w):(\d):(\d)/;

		for(var i = 0; i < texto.length; i++) {
			// quebra os processos prontos pelo espaco em branco
			var tmp = texto[i].split(/\s/);
			
			for(var j = 0; j < tmp.length; j++) {
				if(tmp[j] != "") {
					var match = myRegex.exec(tmp[j]);

					var row = tabela.insertRow(tabela.rows.length);
					var cell1 = row.insertCell(0);
				    var cell2 = row.insertCell(1);
				    var cell3 = row.insertCell(2);

				    cell1.innerHTML = match[1];
				    cell2.innerHTML = match[2];
				    cell3.innerHTML = match[3];
				}
			}
		}
	}
}

function previous() {
	if(step > 0) {
		step = step - 1;
		document.getElementById("descricao_algoritimo").innerHTML = mensagens[step];
		document.getElementById('teste').innerHTML = estados[step];
	}
}

function auto() {
}

function reset() {
	
}