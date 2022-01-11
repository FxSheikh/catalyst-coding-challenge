<?php
    // echo "-------------------------------\n";

    // Declaring variables that we need to run the script
    $file_name = NULL;
    $mysql_username = 'user';
    $mysql_password = 'password';
    $mysql_host = 'db'; // servername
    $dry_run_active = false;
    $create_table_active = false;
    
    // Catch exceptions on mysqli extension and allow mysqli to throw exceptions
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

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
          $this->database_name = 'users_database';
          
          try {
              $this->connection = new mysqli($host,$username,$password);
              echo "Successfully connected to the database server \n";
              echo "-------------------------------\n";
          } catch (Exception $e) {
              echo "Connection error, please make sure the database credentials are correct \n";
              echo "-------------------------------\n";
          }
    }  

        public function createDB() {
            
            // Create database if it doesn't exist, only root user can do this
            $sql_query = "CREATE DATABASE IF NOT EXISTS $this->database_name";
            if ($this->connection->query($sql_query) === TRUE) {
                echo "Database created successfully \n";
                echo "-------------------------------\n";
            } else {
                echo "Error creating database: " . $this->connection->error . "\n";
                echo "-------------------------------\n";
            }
        }

        public function emptyArray(){
            $this->result = array();
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

    $conn = new Datastorage($mysql_host,$mysql_username,$mysql_password);
    $conn->createDB();
?>
