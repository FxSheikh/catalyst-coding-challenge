<?php
    echo "-------------------------------\n";

    // Declaring variables that we need to run the script
    $file_name = NULL;
    $mysql_username = NULL;
    $mysql_password = NULL;
    $mysql_host = NULL; // servername
    $dry_run_active = false;
    $create_table_active = false;
    
    // Creating a class to help manage database operations
    class DataStorage{
        private $connection;
        private $user; 
        private $host;
        private $password;
        private $database_name; 
      
        private $result = array();
        private $number_rows;
        
        public function __construct($host,$username,$password){
          $this->host = $host;
          $this->user = $username;
          $this->password = $password;
          $this->database_name = 'db';
          
          $this->connection = new mysqli($this->host,$this->user,$this->password);
          // Check connection
          if ($this->connection->connect_error) {  
            die("Connection failed: " . $this->connection->connect_error . "\n");
          }
          echo "Connected successfully to the database server \n";         
        }
    }

    // Declaring short and long options to get from command line
    $shortopts  = "" . "u:" . "p:" . "h:";   
    $longopts  = array("file::", "create_table", "dry_run",);
    
    $passed_options = getopt($shortopts, $longopts);
    // var_dump($passed_options);
    
    if (isset($passed_options['file'])) {
        $file_name = $passed_options['file'];
        echo "Filename is " . $file_name . "\n";
    }
 
    if (isset($passed_options['create_table'])) {
        $create_table_active = true;
        echo "Create table active is " . $create_table_active . "\n";
    } 

    if (isset($passed_options['dry_run'])) {
        $dry_run_active = true;
        echo "Dry run active is " . $dry_run_active . "\n";
    } 

    if (isset($passed_options['u'])) {
        $mysql_username = $passed_options['u'];
        echo "Username is " . $mysql_username . "\n";
    }
    
    if (isset($passed_options['p'])) {
        $mysql_password = $passed_options['p'];
        echo "Password is " . $mysql_password . "\n";
    }    

    if (isset($passed_options['h'])) {
        $mysql_host = $passed_options['h'];
        echo "Host is " . $mysql_host . "\n";
    } 

    function printDirectives() {
        echo "The following command line options (directives) are available:\n";
        echo "--------------------------------------------------------------\n";
        echo "--file [csv file name] –> this is the name of the CSV to be parsed.\n";
        echo "--create_table – this will cause the MySQL users table to be built (and no further action will be taken).\n";
        echo "--dry_run – this will be used with the --file directive in case we want to run the script but not insert into the DB.\n";
        echo "All other functions will be executed, but the database won't be altered.\n";
        echo "-u – MySQL username\n";
        echo "-p – MySQL password\n";
        echo "-h – MySQL host\n";
        echo "--help – which will output the above list of directives with details.\n";
    }

    // If the first argument is --help then print the directives
    if (in_array($argv[1], array('--help'))){
        printDirectives();
    }     
    
    // If create table option passed and all of the database credentials create a connection to the database
    if ($create_table_active and ($mysql_host and $mysql_username and $mysql_password)) {
        // Create a new connection to the database
        $conn = new Datastorage($mysql_host,$mysql_username,$mysql_password);
    } 
?>