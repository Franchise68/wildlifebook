<?php
header('Content-Type: application/json');

try {
    require_once 'db_connect.php'; // Your database connection file
    
    $destination = $_POST['destination'] ?? '';
    $departureDate = $_POST['departureDate'] ?? '';
    $returnDate = $_POST['returnDate'] ?? '';
    
    // Input validation
    if (empty($destination) || empty($departureDate) || empty($returnDate)) {
        echo json_encode(['error' => 'Missing required parameters']);
        exit;
    }
    
    // Query to check availability (adjust this to match your database schema)
    $stmt = $pdo->prepare("
        SELECT available_spots 
        FROM tour_availability 
        WHERE destination = :destination 
        AND date BETWEEN :departureDate AND :returnDate
        ORDER BY available_spots ASC
        LIMIT 1
    ");
    
    $stmt->bindParam(':destination', $destination);
    $stmt->bindParam(':departureDate', $departureDate);
    $stmt->bindParam(':returnDate', $returnDate);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If no result found, assume maximum capacity available
    $availableSpots = $result ? intval($result['available_spots']) : 20;
    
    echo json_encode(['available' => $availableSpots]);
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>