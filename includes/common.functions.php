<?php
function queryUniqueValue(PDO $pdo, string $query, array $params = []) {
    try {
        // Append LIMIT 1 if not already in the query
        if (stripos($query, 'LIMIT') === false) {
            $query .= ' LIMIT 1';
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC); // fetches the full row as an associative array
    } catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage();
        return false;
    }
}


function deleteRecord($pdo, $table, $column, $value) {
    try {
        $sql = "DELETE FROM $table WHERE $column = :value";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['value' => $value]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Dynamic function call handler
function handleRequest() {
    // Check if the 'f' parameter exists
    if (isset($_GET['f'])) {
        $function = $_GET['f'];  // Function name passed as a GET parameter

        // Check if the function exists
        if (function_exists($function)) {
            // Call the function dynamically
            call_user_func($function);
        } else {
            // If function doesn't exist, return an error
            echo json_encode(['success' => false, 'message' => 'Function not found.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No function specified.']);
    }
}

// Dynamic function to add a record to any table
function addRecord($pdo, $table, $data) {
    try {
        // Extract column names and values
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        
        // Prepare the SQL query dynamically
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        // Prepare the statement
        $stmt = $pdo->prepare($sql);
        
        // Bind the values dynamically
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        // Execute the query
        $stmt->execute();
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Dynamic function to edit a record in any table
function editRecord($pdo, $table, $data, $idColumn, $id) {
    try {
        // Build the SET part of the SQL query dynamically
        $setClause = "";
        foreach ($data as $key => $value) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ", ");  // Remove the last comma
        
        // Prepare the SQL query dynamically
        $sql = "UPDATE $table SET $setClause WHERE $idColumn = :id";
        
        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Bind the values dynamically
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        // Bind the ID value
        $stmt->bindValue(":id", $id);

        // Execute the query
        $stmt->execute();
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
?>