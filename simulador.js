window.onload = run;

var mensagens = null;
var estados = null;
var step = 0;

function clearTable(tabela) {
	// para cada linha na tabela, exceto a primeira que e' o header
	var nrows = tabela.rows.length;
	for(i = 1; i < nrows; i++) {
        tabela.deleteRow(i);
	}
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

			processos_prontos = new Array();
			processos_bloqueados = new Array();

			// quebra a string na virgula
			var texto = valor.split(/,/);
			var myRegex = /(\w):(\d):(\d)/;
		
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
    
    reset();
}

function home() {
	window.history.back();	
}

function atualiza() {
	// atualiza a mensagem
	document.getElementById("descricao_algoritimo").innerHTML = mensagens[step];

	// atualiza a tabela
	var tabela = document.getElementById("myTable");
	clearTable(tabela);
		
	// processos prontos
	var processos_prontos = estados[step][0];
	for(var i = 0; i < processos_prontos.length; i++) {
		var row = tabela.insertRow(tabela.rows.length);
				
		// cabecalho padrao de todos
		var cell1 = row.insertCell(0);
	    var cell2 = row.insertCell(1);
	    var cell3 = row.insertCell(2);
			
	    // Add some text to the new cells:
	    cell1.innerHTML = processos_prontos[i][0];
	    cell2.innerHTML = processos_prontos[i][1];
	    cell3.innerHTML = processos_prontos[i][2];
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