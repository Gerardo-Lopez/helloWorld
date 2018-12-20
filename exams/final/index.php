<?php
  session_start();

    $httpMethod = strtoupper($_SERVER['REQUEST_METHOD']);

    switch($httpMethod) {
      case "OPTIONS":
        // Allows anyone to hit your API, not just this c9 domain
        header("Access-Control-Allow-Headers: X-ACCESS_TOKEN, Access-Control-Allow-Origin, Authorization, Origin, X-Requested-With, Content-Type, Content-Range, Content-Disposition, Content-Description");
        header("Access-Control-Allow-Methods: POST, GET");
        header("Access-Control-Max-Age: 3600");
        exit();

      case "GET":
          
        $servername = getenv('IP');
        $dbPort = 3306;
        
        // Which database (the name of the database in phpMyAdmin)?
        $database = "final";
        
        // My user information...I could have prompted for password, as well, or stored in the
        // environment, or, or, or (all in the name of better security)
        $username = getenv('C9_USER');
        $password = "";
        
        // Establish the connection and then alter how we are tracking errors (look those keywords up)
        $dbConn = new PDO("mysql:host=$servername;port=$dbPort;dbname=$database", $username, $password);
        $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Build the select statement (assuming the posted data has a field called dataFromPost)
        $whereSql = "SELECT * FROM final INNER JOIN status s ON p.status_id = s.id LEFT OUTER JOIN type t ON p.type_id = t.id WHERE status_id <> 0";
        
        // The prepare caches the SQL statement for N number of parameters imploded above
        $whereStmt = $dbConn->prepare($whereSql);
        
        // Just have to pop in the associative array that comes from json_decode
        $whereStmt->execute($postedAssocArray);
        
        // Returns associative array instead of a cursor
        $sqlQueryResultsAssocArray = $whereStmt->fetchAll(PDO::FETCH_ASSOC);
        // Allow any client to access
        header("Access-Control-Allow-Origin: *");

        break;
      case 'POST':
          
          
        // Get the body json that was sent
        $rawJsonString = file_get_contents("php://input");
        
        // Make it a associative array (by making second param = true)
        $postedJsonData = json_decode($rawJsonString, true);
        
        
        // Save the data to the database
        $servername = getenv('IP');
        $dbPort = 3306;
        
        // Which database (the name of the database in phpMyAdmin)?
        $database = "final";
        
        // My user information...I could have prompted for password, as well, or stored in the
        // environment, or, or, or (all in the name of better security)
        $username = getenv('C9_USER');
        $password = "";
        
        // Establish the connection and then alter how we are tracking errors (look those keywords up)
        $dbConn = new PDO("mysql:host=$servername;port=$dbPort;dbname=$database", $username, $password);
        $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
        $whereSql = "INSERT INTO final(race_id, race_date, start_time, password, race_location) VALUES (:raceID, :raceDate, :startTime, :password, :raceLocation)";
        
        // The prepare caches the SQL statement for N number of parameters imploded above
        $whereStmt = $dbConn->prepare($whereSql);
        
        // Just have to pop in the associative array that comes from json_decode
        $whereStmt->execute($postedJsonData);
        
        // Let the client know the format of the data being returned
        header("Content-Type: application/json");
        // Allow any client to access
        header("Access-Control-Allow-Origin: *");
        
        
        // Make some status data to send back down in an associative array
        
        // Sending back down as JSON
        //echo json_encode($postedJsonData);


        break;
      case 'PUT':
        header("Access-Control-Allow-Origin: *");
        http_response_code(401);
        echo "Not Supported";
        break;
      case 'DELETE':
        header("Access-Control-Allow-Origin: *");
        http_response_code(401);
        break;
    }
?>