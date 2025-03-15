<?php
// setup-database.php - Run this file once to set up your database

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = "localhost";
$dbname = "wildlife_booking";
$username = "root";
$password = "";

// HTML header for nicer output
echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Setup</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        pre { background: #f4f4f4; padding: 10px; overflow: auto; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>WildVentures Database Setup</h1>
        <hr>";

try {
    // First, create the database if it doesn't exist
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "<p class='success'>Database '$dbname' created or already exists.</p>";
    
    // Connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS destinations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(50) NOT NULL UNIQUE,
            name VARCHAR(100) NOT NULL,
            base_price DECIMAL(10,2) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS availability (
            id INT AUTO_INCREMENT PRIMARY KEY,
            destination VARCHAR(50) NOT NULL,
            available_date DATE NOT NULL,
            spots_available INT NOT NULL DEFAULT 10,
            UNIQUE KEY destination_date (destination, available_date)
        );
        
        CREATE TABLE IF NOT EXISTS bookings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            booking_reference VARCHAR(20) NOT NULL UNIQUE,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(50) NOT NULL,
            participants INT NOT NULL,
            destination VARCHAR(50) NOT NULL,
            tour_package VARCHAR(50) NOT NULL,
            departure_date DATE NOT NULL,
            return_date DATE NOT NULL,
            duration INT NOT NULL,
            total_price DECIMAL(10,2) NOT NULL,
            special_requirements TEXT,
            booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status VARCHAR(20) DEFAULT 'pending',
            INDEX idx_booking_reference (booking_reference),
            INDEX idx_email (email),
            INDEX idx_destination (destination),
            INDEX idx_dates (departure_date, return_date)
        );
      
        CREATE TABLE IF NOT EXISTS payment_transactions (
            transaction_id INT AUTO_INCREMENT PRIMARY KEY,
            booking_id INT NOT NULL,
            amount DECIMAL(10, 2) NOT NULL,
            transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            payment_method ENUM('Credit Card', 'PayPal', 'Bank Transfer', 'Cash', 'Other') NOT NULL,
            transaction_reference VARCHAR(100),
            notes TEXT,
            FOREIGN KEY (booking_id) REFERENCES bookings(id) -- Corrected foreign key reference
        );

        CREATE TABLE IF NOT EXISTS tour_packages (
            package_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            price_modifier DECIMAL(5, 2) DEFAULT 1.00,
            includes_guide BOOLEAN DEFAULT TRUE,
            includes_meals BOOLEAN DEFAULT TRUE,
            includes_accommodation BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS customers (
            customer_id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(20),
            address TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY (email)
        );
    ");
    echo "<p class='success'>Tables created successfully.</p>";
    
    // Insert destinations data
    $insertDestinations = "
    INSERT IGNORE INTO destinations (code, name, base_price, description) VALUES
    ('serengeti', 'Serengeti National Park', 1999.00, 'Experience the Great Migration in Tanzania'),
    ('amazon', 'Amazon Rainforest', 2299.00, 'Explore the lungs of the Earth in Brazil'),
    ('galapagos', 'Galapagos Islands', 3499.00, 'Discover unique wildlife in Ecuador'),
    ('yellowstone', 'Yellowstone National Park', 1499.00, 'See geysers and wildlife in the USA'),
    ('borneo', 'Borneo Rainforest', 2799.00, 'Meet orangutans in their natural habitat'),
    ('barrier-reef', 'Great Barrier Reef', 2199.00, 'Dive in the world\\'s largest coral reef system')
    ";
    $pdo->exec($insertDestinations);
    echo "<p class='success'>Destinations data inserted successfully.</p>";
    
    // Generate availability data
    $destinations = ['serengeti', 'amazon', 'galapagos', 'yellowstone', 'borneo', 'barrier-reef'];
    $insertCount = 0;
    
    // Check if there's already availability data
    $checkStmt = $pdo->query("SELECT COUNT(*) FROM availability");
    $availabilityCount = $checkStmt->fetchColumn();
    
    if ($availabilityCount < 100) { // Only generate if we don't have much data
        // Prepare the statement once outside the loops
        $availStmt = $pdo->prepare("
            INSERT IGNORE INTO availability (destination, available_date, spots_available)
            VALUES (:destination, :available_date, :spots_available)
        ");
        
        foreach ($destinations as $dest) {
            for ($i = 0; $i < 30; $i++) {
                $currDate = date('Y-m-d', strtotime("+$i days"));
                $spotsAvailable = floor(5 + (mt_rand() / mt_getrandmax()) * 15); // More reliable than RAND()
                
                $availStmt->execute([
                    'destination' => $dest,
                    'available_date' => $currDate,
                    'spots_available' => $spotsAvailable
                ]);
                
                if ($availStmt->rowCount() > 0) {
                    $insertCount++;
                }
            }
        }
        echo "<p class='success'>Generated $insertCount availability records.</p>";
    } else {
        echo "<p>Availability data already exists. Skipping generation.</p>";
    }
    
    echo "<p class='success'>Database setup complete! Your WildVentures database is ready to use.</p>";
    echo "<p><a href='index.php'>Go to homepage</a></p>";
    
} catch (PDOException $e) {
    echo "<p class='error'>Database setup error: " . $e->getMessage() . "</p>";
}

echo "</div>
</body>
</html>";
?>