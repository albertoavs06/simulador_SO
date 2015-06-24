I3S: Interactive Systems Scheduling Simulator

Authors:
	Marcelo Koti Kamada	(marcelokkamada@gmail.com)
	Maria Lydia Fioravanti  (mlfioravanti@usp.br)
	
################ Project Organization ###############

root:
	| - > bootstrap (Css and js files to customize the page presentation)
	|
	| - > engine (Simulation engines: each algorithm has its own directory)
	|
	| - > img (Images and logos of the project)
	|
	| - > lang (Xml files to support different languages)
	|
	| - > util (Css and jquery files to align columns)
	|
	| - > index.php (Homepage, receives the user simulation parameters)
	|
	| - > index.js (Interactivity of homepage)
	|
	| - > simulador.php (Simulation page)
	|
	| - > simulador.js (Interprets the simulation parameters, runs the appropriate engine and displays the results in simulador.php)
	

################ Instructions to Install and Run  ###############

Linux:
	1- Install Apache server
		sudo apt-get install apache2
	
	2- Install PHP 
		sudo apt-get install php5 php5-mysql libapache2-mod-php5
		
	3- Restart Apache
		Ubuntu 15.04 -> sudo systemctl restart apache2
		Ubuntu prior to 15.04 -> sudo service apache2 restart
	
	4- Download the latest version of simulator
		https://github.com/marcelokk/simulador_SO
		
	5- (Optional) Change the www apache directory  to eliminate the need to be an administrator when deploying the simulator 
	    in the configuration file /etc/apache2/sites-available/000-default.conf
		Modify the DocumentRoot value with the path of the desired directory
	
	6- Extract the contents of the downloaded .zip to Apache www directory
		If step 5 was not carried out, it should be /var/www/html
		
	7- Rename the extracted folder simulador_SO-master for simulador_SO
	
	8- Visit locally by URL: localhost/simulador_SO/index.php 


Instructions on how to install Apache and PHP taken from: http://www.unixmen.com/how-to-install-lamp-stack-on-ubuntu-15-04/


Windows:
	1- Install WAMP
		http://www.wampserver.com/en/
		
	2- Run WAMP, an icon should appear one on the left side of the clock on the taskbar
	
	3- Left-click WAMP icon
	
	4- Select the www directory from the options
	
	5- Download the latest version of simulator
		https://github.com/marcelokk/simulador_SO

	6- Extract the contents of the downloaded .zip to Wamp www directory
		
	7- Rename the extracted folder simulador_SO-master for simulador_SO
	
	8- Visit locally by URL: localhost/simulador_SO/index.php 
