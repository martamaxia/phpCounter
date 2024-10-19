<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/plain');

// Disable caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Get the database connection details from the environment variable
$dbConn = parse_url(getenv("DATABASE_URL"));

// Set up the PDO connection
$pdo = new PDO("pgsql:" . sprintf(
    "host=%s;port=%s;user=%s;password=%s;dbname=%s",
    $dbConn['host'],
    $dbConn['port'],
    $dbConn['user'],
    $dbConn['pass'],
    ltrim($dbConn['path'], "/")
));

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

try {
    $pdo->beginTransaction();
    // Update counter
    $updateStmt = $pdo->prepare("UPDATE counter SET count = count + 1 WHERE id = 1");
    $updateStmt->execute();

    // Retrieve the updated count
    $selectStmt = $pdo->prepare("SELECT count FROM counter WHERE id = 1");
    $selectStmt->execute();
    $count = $selectStmt->fetch(PDO::FETCH_ASSOC);

    $pdo->commit();

    // Output the current count
    echo "Current count is: " . $count['count'];
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Failed: " . $e->getMessage();
    error_log("Error: " . $e->getMessage());
}

// Close connection
$pdo = null;
?>

