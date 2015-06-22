Simulador de Algoritimos para Escalonamento de Processos em Sistemas Interativos.

Autores:
	Marcelo Koti Kamada	    (marcelokkamada@gmail.com)
	Maria Lydia Fioravanti  (mlfioravanti@gmail.com)
	
# ############### Organizacao do Projeto ###############

raiz:
	| - > bootstrap (arquivos de css e js para a aparencia das paginas)
	|
	| - > engine (motores da simulacao. Cada algoritimo tem o seu proprio diretorio
	|
	| - > img (imagens e logos do projeto)
	|
	| - > lang (arquivos xml para suporte a diferentes idiomas)
	|
	| - > util (arquivos css e jquery para alinhamento das colunas)
	|
	| - > index.php (pagina principal, recebe os parametros de simulacao do usuario)
	|
	| - > index.js (iteratividade da pagina principal)
	|
	| - > simulador.php (pagina de simulacao)
	|
	| - > simulador.js (interpreta os parametros de simulacao, executa o motor apropriado e exibe os resultados em simulador.php)
	

# ############### Instrucoes para Instalacao ###############

Linux:
	1- instale o servidor Apache
		sudo apt-get install apache2
	
	2- instale o PHP 
		sudo apt-get install php5 php5-mysql libapache2-mod-php5
		
	3- reinicie o Apache
		Ubuntu 15.04 -> sudo systemctl restart apache2
		Ubuntu anterior ao 15.04 -> sudo service apache2 restart
	
	4- baixe a ultima versao do simulador em
		https://github.com/simoesusp/Processador-ICMC
		
	5- (Opcional) altere o diretorio www do apache para nao precisar ser administrador na hora de disponibilizar o simulador
		no arquivo de configuracao /etc/apache2/sites-available/000-default.conf
		modifique o valor de DocumentRoot com o caminho do diretorio desejado
	
	6- extraia o conteudo do .zip baixado no github para o diretorio www do Apache
		se voce nao fez o passo 5 deve ser /var/www/html
		
	7- mude o nome da pasta extraida de simulador_SO-master para simulador_SO
	
	8- acesse localmente pela URL: localhost/simulador_SO/index.php 


Instrucoes para instalacao do Apache e PHP retiradas de: http://www.unixmen.com/how-to-install-lamp-stack-on-ubuntu-15-04/


Windows:
	1- instale o WAMP
		http://www.wampserver.com/en/
		
	2- execute o WAMP, deve aparecer um icone do lado esquerdo do relogio na barra de tarefas
	
	3- clique com o botao esquerdo no icone do WAMP
	
	4- selecione o diretorio www dentre as opcoes
	
	5- baixe a ultima versao do simulador em
		https://github.com/simoesusp/Processador-ICMC

	6- extraia o conteudo do .zip baixado no github para o diretorio www do WAMP
		
	7- mude o nome da pasta extraida de simulador_SO-master para simulador_SO
	
	8- acesse localmente pela URL: localhost/simulador_SO/index.php 
