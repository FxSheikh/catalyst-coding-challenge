<?php
    echo "----------------------------------------------\n";

    // Declaring variables that we need to run the script
    $file_name = "";
    $mysql_username = 'user';
    $mysql_password = 'password';
    $mysql_host = 'db'; // servername
    $dry_run_active = false;
    $create_table_active = false;
    $dry_run_active = false;
    $file_active = false;
    
    // Declaring the create table sql query that will be needed
    $create_query = "CREATE TABLE IF NOT EXISTS users(
        ID INT NOT NULL AUTO_INCREMENT,
        FirstName VARCHAR(50) NOT NULL,
        LastName VARCHAR(50) NOT NULL,
        Email VARCHAR(50) NOT NULL,
        UNIQUE KEY email_uniq (Email),
        PRIMARY KEY (`ID`));";  

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
        private $connection_status = false;

        // Constructor function for the DataStorage class used to create a database connection
        public function __construct($host,$username,$password){
          $this->host = $host;
          $this->user = $username;
          $this->password = $password;
          $this->database_name = 'users_database';
          
          try {
              $this->connection = new mysqli($host,$username,$password,$this->database_name);
              $this->connection_status = true;
              echo "Successfully connected to the database server \n";
              echo "----------------------------------------------\n";
          } catch (Exception $e) {
              echo "Connection error, please make sure the database credentials are correct \n";
              echo "----------------------------------------------\n";
          }
        }  

        // Method to create the users table
        public function createTable($sql_query) {
            try {
                mysqli_query($this->connection, $sql_query);
                echo "The users table was succesfully created \n";
                echo "----------------------------------------------\n";
            } catch (mysqli_sql_exception $e) {
                echo "There was an error in the query statement, The users table could not be created \n";
                echo "----------------------------------------------\n";
            }
        }

        // Method for reading the contents of the csv file
        public function readCSV($filename) {

          $results_array = [];
  
          if (file_exists($filename) and ($file = fopen($filename, "r"))!==false ) {
          
              echo "The file $filename exists and is successfully opened\n";
              
              # Skip reading of the first line because it is only headers
              fgetcsv($file);
      
              # Using a loop to iterate through the data
              while (($data = fgetcsv($file)) !== FALSE) {
  
                  # $[data][0] is name, $[data][1] is surname, $[data][2] is email from the csv file
                  # using trim to remove whitespaces from left and right side of the string
                  $first_name = trim($data[0]);
                  $surname = trim($data[1]);
                  $email = trim($data[2]);
                  
                  echo "First name is $first_name, Surname is $surname and email is $email \n";
  
                  #validate email before inserting into database, if invalid then no insert and report error to output                
                  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                      echo "The email address $email for $first_name $surname is invalid, this row will not be inserted into the database \n";
                      continue;   
                  }
  
                  # validate first name using regex before inserting into database, if invalid then no insert and report error to output
                  if (!preg_match("/^[a-zA-Z'. -]+$/",$first_name)) {  
                      echo "The firstname for $first_name $surname is invalid (only a-z A-Z ' . - and whitespace allowed), this row will not be inserted into the database \n";
                      continue;
                  }
                   
                  # validate surname using regex before inserting into database, if invalid then no insert and report error to output
                  if (!preg_match("/^[a-zA-Z'. -]+$/",$surname)) {  
                      echo "The surname for $first_name $surname is invalid (only a-z A-Z ' . - and whitespace allowed), this row will not be inserted into the database \n";
                      continue;
                  }                
  
                  #name and surname needs to be capitalised before being inserted into db
                  $first_name = ucfirst(strtolower($first_name));
                  $surname = ucfirst(strtolower($surname));
  
                  #email needs to be set to lowercase before being inserted into db
                  $email = strtolower($email);
  
                  #final array to be pushed into the results array
                  $final_array = array($first_name,$surname,$email);
                  
                  array_push($results_array,$final_array); 
              }
      
              # Close the csv file
              fclose($file);
          } 
          
          else {
              echo "The file $filename does not exist \n";
          }
          
          // Save the results in our class results variable
          $this->result = $results_array;

          // Return the results array
          return $results_array;
  
      }

      // Method for inserting the data from the csv file into the users table
      public function insertData($data_array) {
        
        $this->emptyArray();
        $this->result = $data_array;

        foreach ($this->result as $array){
            echo "----------------------------------------------\n";
            print_r($array);

            // echo $first_name; echo $surname; echo $email;

            try {
                // Using prepared statements and bound parameters for extra security
                $stmt = $this->connection->prepare("INSERT INTO users (firstname, lastname, email) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $first_name, $surname, $email);
                $first_name = $array[0]; $surname = $array[1]; $email = $array[2];
                $stmt->execute();                
                echo "The row for $first_name $surname was succesfully inserted into the users table. \n";
                echo "----------------------------------------------\n";

            } catch (mysqli_sql_exception $e) {
                if (strpos($e, 'Duplicate entry') !== false) {
                    echo "This email already exists. This row will not be inserted into the users table \n";
                    echo "----------------------------------------------\n";
                }
                else {
                    echo "Sorry an error occurred. This row will not be inserted into the users table. \n";
                    echo "----------------------------------------------\n";
                }
            }
        }
      } 
        
      // Method to empty the class array
        public function emptyArray(){
            $this->result = array();
        }

        // Method for returning the connection status of the user
        public function getConnectionStatus(){
            return $this->connection_status;
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

    // Function for printing the directives if --help directive is set
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
    if ($conn->getConnectionStatus() == true){
        // $conn->createDB();
        $conn->createTable($create_query);
        $results_arr = $conn->readCSV($file_name);
        $conn->insertData($results_arr);
    }
 
    // Create table, file with create, file with dry run, file by itself, else clause

    /* Method to create the database [this is not required]
    $this->connection = new mysqli($host,$username,$password);
    $sql_query2 = "CREATE DATABASE IF NOT EXISTS $this->database_name;";
    $this->connection->query($sql_query2);
    public function createDB() {
        // Create database if it doesn't exist, only root user can do this
        $sql_query = "CREATE DATABASE IF NOT EXISTS $this->database_name;";

        if ($this->connection->query($sql_query) === TRUE) {
            echo "Database created successfully \n";
            echo "----------------------------------------------\n";

        } else {
            echo "Error creating database: " . $this->connection->error . "\n";
            echo "----------------------------------------------\n";
        }
    } */
?>
