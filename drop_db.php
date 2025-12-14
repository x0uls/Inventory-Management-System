<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to MySQL. Dropping database...\n";
    $pdo->exec("DROP DATABASE IF EXISTS inventorymanagement");
    echo "Database dropped.\n";
    
    echo "Recreating database...\n";
    $pdo->exec("CREATE DATABASE inventorymanagement");
    echo "Database recreated.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
