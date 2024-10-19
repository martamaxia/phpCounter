<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/plain');

// Get the database connection details from the environment variable
$dbConn = parse_url(getenv("DATABASE_URL"));

$pdo = new PDO("pgsql:" . sprintf(
    "host=%s;port=%s;user=%s;password=%s;dbname=%s",
    $dbConn['host'],
    $dbConn['port'],
    $dbConn['user'],
    $dbConn['pass'],
    ltrim($dbConn['path'], "/")
));

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $pdo->beginTransaction();
    $pdo->exec("UPDATE counter SET count = count + 1 WHERE id = 1");
    $stmt = $pdo->query("SELECT count FROM counter WHERE id = 1");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    $pdo->commit();
    echo $count['count'];
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Failed: " . $e->getMessage();
}

$pdo->close();
?>
