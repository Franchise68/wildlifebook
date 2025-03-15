<?php
// Database configuration
$host = "localhost";
$dbname = "wildlife_booking";
$username = "root";
$password = "";

// Create database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Process booking form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $participants = filter_input(INPUT_POST, 'participants', FILTER_VALIDATE_INT);
    $destination = filter_input(INPUT_POST, 'destination', FILTER_SANITIZE_STRING);
    $tourPackage = filter_input(INPUT_POST, 'tourPackage', FILTER_SANITIZE_STRING);
    $departureDate = filter_input(INPUT_POST, 'departureDate', FILTER_SANITIZE_STRING);
    $returnDate = filter_input(INPUT_POST, 'returnDate', FILTER_SANITIZE_STRING);
    $specialRequirements = filter_input(INPUT_POST, 'specialRequirements', FILTER_SANITIZE_STRING);
    
    // Validate required fields
    $errors = [];
    
    if (empty($fullName)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($participants) || $participants < 1 || $participants > 20) {$errors[] = "Number of participants must be between 1 and 20";
    }
    
    if (empty($destination)) {
        $errors[] = "Destination is required";
    }
    
    if (empty($tourPackage)) {
        $errors[] = "Tour package is required";
    }
    
    if (empty($departureDate)) {
        $errors[] = "Departure date is required";
    }
    
    if (empty($returnDate)) {
        $errors[] = "Return date is required";
    }
    
    // Validate dates
    $departureDateObj = new DateTime($departureDate);
    $returnDateObj = new DateTime($returnDate);
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    
    if ($departureDateObj < $today) {
        $errors[] = "Departure date must be in the future";
    }
    
    if ($returnDateObj <= $departureDateObj) {
        $errors[] = "Return date must be after departure date";
    }
    
    // If there are no errors, proceed with booking
    if (empty($errors)) {
        try {
            // Generate a unique booking reference
            $bookingReference = 'WV' . date('Ymd') . rand(1000, 9999);
            
            // Calculate the trip duration
            $interval = $departureDateObj->diff($returnDateObj);
            $duration = $interval->days;
            
            // Get base prices from destinations table
            $stmt = $pdo->prepare("SELECT base_price FROM destinations WHERE code = :destination");
            $stmt->execute(['destination' => $destination]);
            $basePrice = $stmt->fetchColumn();
            
            if (!$basePrice) {
                // Fallback prices if not found in database
                $basePrices = [
                    'serengeti' => 1999,
                    'amazon' => 2299,
                    'galapagos' => 3499,
                    'yellowstone' => 1499,
                    'borneo' => 2799,
                    'barrier-reef' => 2199
                ];
                $basePrice = isset($basePrices[$destination]) ? $basePrices[$destination] : 2000;
            }
            
            // Calculate total price
            $totalPrice = $basePrice * $participants;
            
            // Package price modifiers
            $packageModifiers = [
                'standard' => 1.0,
                'premium' => 1.3,
                'luxury' => 1.8,
                'photography' => 1.5,
                'migration' => 1.4,
                'river' => 1.2,
                'indigenous' => 1.35,
                'cruise' => 1.6,
                'diving' => 1.4,
                'geyser' => 1.15,
                'wildlife' => 1.25,
                'orangutan' => 1.4,
                'jungle' => 1.3,
                'island' => 1.25
            ];
            
            $modifier = isset($packageModifiers[$tourPackage]) ? $packageModifiers[$tourPackage] : 1.0;
            $totalPrice = $totalPrice * $modifier;
            
            // Insert booking into database
            $stmt = $pdo->prepare("
                INSERT INTO bookings (
                    booking_reference, 
                    full_name, 
                    email, 
                    phone, 
                    participants, 
                    destination, 
                    tour_package, 
                    departure_date, 
                    return_date, 
                    duration,
                    total_price,
                    special_requirements, 
                    booking_date,
                    status
                ) VALUES (
                    :booking_reference,
                    :full_name,
                    :email,
                    :phone,
                    :participants,
                    :destination,
                    :tour_package,
                    :departure_date,
                    :return_date,
                    :duration,
                    :total_price,
                    :special_requirements,
                    NOW(),
                    'pending'
                )
            ");
            
            $stmt->execute([
                'booking_reference' => $bookingReference,
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'participants' => $participants,
                'destination' => $destination,
                'tour_package' => $tourPackage,
                'departure_date' => $departureDate,
                'return_date' => $returnDate,
                'duration' => $duration,
                'total_price' => $totalPrice,
                'special_requirements' => $specialRequirements
            ]);
            
            // Send confirmation email
            $to = $email;
            $subject = "WildVentures Booking Confirmation - $bookingReference";
            
            $message = "
            <html>
            <head>
                <title>Booking Confirmation</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #2c5e1a; color: white; padding: 15px; text-align: center; }
                    .booking-details { background-color: #f9f9f9; padding: 20px; margin: 20px 0; }
                    .total-price { font-size: 18px; font-weight: bold; color: #2c5e1a; }
                    .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>WildVentures Booking Confirmation</h1>
                    </div>
                    
                    <p>Dear $fullName,</p>
                    
                    <p>Thank you for booking your wildlife adventure with WildVentures. We're excited to have you join us!</p>
                    
                    <div class='booking-details'>
                        <h2>Booking Details</h2>
                        <p><strong>Booking Reference:</strong> $bookingReference</p>
                        <p><strong>Destination:</strong> " . ucfirst(str_replace('-', ' ', $destination)) . "</p>
                        <p><strong>Tour Package:</strong> " . ucfirst(str_replace('-', ' ', $tourPackage)) . "</p>
                        <p><strong>Departure Date:</strong> " . date('F j, Y', strtotime($departureDate)) . "</p>
                        <p><strong>Return Date:</strong> " . date('F j, Y', strtotime($returnDate)) . "</p>
                        <p><strong>Number of Participants:</strong> $participants</p>
                        <p class='total-price'><strong>Total Price:</strong> $" . number_format($totalPrice, 2) . "</p>
                    </div>
                    
                    <p>A WildVentures representative will contact you within 24 hours to confirm your booking details and provide further information about your upcoming adventure.</p>
                    
                    <p>If you have any questions or need to make changes to your booking, please contact our customer support team at support@wildventures.com or call us at +1 (800) 123-4567.</p>
                    
                    <p>We look forward to providing you with an unforgettable wildlife experience!</p>
                    
                    <p>Warm regards,<br>
                    The WildVentures Team</p>
                    
                    <div class='footer'>
                        <p>© 2025 WildVentures. All rights reserved.</p>
                        <p>123 Wildlife Way, Adventure City, AC 12345</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            // Set email headers
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: WildVentures <bookings@wildventures.com>" . "\r\n";
            
            // Send email
            mail($to, $subject, $message, $headers);
            
            // Redirect to confirmation page
            header("Location: booking_confirmation.php?ref=$bookingReference");
            exit();
            
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
    
    // If there are errors, store them in session and redirect back to form
    if (!empty($errors)) {
        session_start();
        $_SESSION['booking_errors'] = $errors;
        $_SESSION['form_data'] = $_POST; // Store form data for repopulation
        header("Location: index.php#booking");
        exit();
    }
}

// Function to generate available dates for a destination
function getAvailableDates($destination) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT available_date, spots_available 
            FROM availability 
            WHERE destination = :destination 
            AND available_date >= CURDATE()
            AND spots_available > 0
            ORDER BY available_date
        ");
        
        $stmt->execute(['destination' => $destination]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// API endpoint to get available dates for a destination
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_dates') {
    header('Content-Type: application/json');
    
    $destination = filter_input(INPUT_GET, 'destination', FILTER_SANITIZE_STRING);
    
    if (empty($destination)) {
        echo json_encode(['error' => 'Destination is required']);
        exit();
    }
    
    $availableDates = getAvailableDates($destination);
    echo json_encode(['dates' => $availableDates]);
    exit();
}



// API endpoint to check availability and price for selected dates
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'check_availability') {
    header('Content-Type: application/json');
    
    $destination = filter_input(INPUT_GET, 'destination', FILTER_SANITIZE_STRING);
    $departureDate = filter_input(INPUT_GET, 'departure_date', FILTER_SANITIZE_STRING);
    $returnDate = filter_input(INPUT_GET, 'return_date', FILTER_SANITIZE_STRING);
    $participants = filter_input(INPUT_GET, 'participants', FILTER_VALIDATE_INT);
    $package = filter_input(INPUT_GET, 'package', FILTER_SANITIZE_STRING);
    
    // Validate inputs
    if (empty($destination) || empty($departureDate) || empty($returnDate) || empty($participants) || $participants < 1) {
        echo json_encode(['error' => 'All fields are required']);
        exit();
    }
    
    try {
        // Check availability for the selected dates
        $stmt = $pdo->prepare("
            SELECT MIN(spots_available) as min_spots
            FROM availability 
            WHERE destination = :destination 
            AND available_date BETWEEN :departure_date AND :return_date
        ");
        
        $stmt->execute([
            'destination' => $destination,
            'departure_date' => $departureDate,
            'return_date' => $returnDate
        ]);
        
        $availability = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($availability && $availability['min_spots'] !== null) {
            $spotsAvailable = (int) $availability['min_spots'];
            
            if ($spotsAvailable >= $participants) {
                // Calculate price
                $stmt = $pdo->prepare("SELECT base_price FROM destinations WHERE code = :destination");
                $stmt->execute(['destination' => $destination]);
                $basePrice = $stmt->fetchColumn();
                
                if (!$basePrice) {
                    // Fallback prices
                    $basePrices = [
                        'serengeti' => 1999,
                        'amazon' => 2299,
                        'galapagos' => 3499,
                        'yellowstone' => 1499,
                        'borneo' => 2799,
                        'barrier-reef' => 2199
                    ];
                    $basePrice = isset($basePrices[$destination]) ? $basePrices[$destination] : 2000;
                }
                
                // Package modifiers
                $packageModifiers = [
                    'standard' => 1.0,
                    'premium' => 1.3,
                    'luxury' => 1.8,
                    'photography' => 1.5,
                    'migration' => 1.4,
                    'river' => 1.2,
                    'indigenous' => 1.35,
                    'cruise' => 1.6,
                    'diving' => 1.4,
                    'geyser' => 1.15,
                    'wildlife' => 1.25,
                    'orangutan' => 1.4,
                    'jungle' => 1.3,
                    'island' => 1.25
                ];
                
                $modifier = isset($packageModifiers[$package]) ? $packageModifiers[$package] : 1.0;
                
                // Calculate total price
                $totalPrice = $basePrice * $participants * $modifier;
                
                echo json_encode([
                    'available' => true,
                    'spots_available' => $spotsAvailable,
                    'total_price' => $totalPrice,
                    'formatted_price' => '$' . number_format($totalPrice, 2)
                ]);
            } else {
                echo json_encode([
                    'available' => false,
                    'message' => "Only $spotsAvailable spots available for the selected dates"
                ]);
            }
        } else {
            echo json_encode([
                'available' => false,
                'message' => "No availability information found for the selected dates"
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error checking availability: ' . $e->getMessage()]);
    }
    
    exit();
}
?>