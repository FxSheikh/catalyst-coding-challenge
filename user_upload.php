<?php
    echo "-------------------------------\n";
    
    // Declaring variables that we need to run the script
    $file_name = NULL;
    $mysql_username = NULL;
    $mysql_password = NULL;
    $mysql_host = NULL;
    
    // Declaring short and long options to get from command line
    $shortopts  = "" . "u:" . "p:" . "h:";   
    $longopts  = array("file::", "create_table", "dry_run",);
    
    $passed_options = getopt($shortopts, $longopts);
    // var_dump($passed_options);
    
    if (isset($passed_options['file'])) {
        $file_name = $passed_options['file'];
        // echo $file_name;
    }
 
    if (isset($passed_options['u'])) {
        $mysql_username = $passed_options['u'];
        // echo $mysql_username;
    }
    
    if (isset($passed_options['p'])) {
        $mysql_password = $passed_options['p'];
        // echo $mysql_password;
    }    

    if (isset($passed_options['h'])) {
        $mysql_password = $passed_options['h'];
        // echo $mysql_host;
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
   
?>