<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = "localhost";
$dbname = "wildlife_booking";
$username = "root";
$password = "";

// Create database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p class='success'>Database connection successful!</p>";
} catch (PDOException $e) {
    die("<p class='error'>Database connection failed: " . $e->getMessage() . "</p>");
}

// Function to sanitize string inputs
function sanitizeString($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Function to get available dates
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
        error_log("Error fetching available dates: " . $e->getMessage());
        return [];
    }
}

// Function to get base price for a destination
function getBasePrice($destination) {
    global $pdo;
    
    try {
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
            return isset($basePrices[$destination]) ? $basePrices[$destination] : 2000;
        }
        
        return $basePrice;
    } catch (PDOException $e) {
        error_log("Error fetching base price: " . $e->getMessage());
        // Fallback
        $basePrices = [
            'serengeti' => 1999,
            'amazon' => 2299,
            'galapagos' => 3499,
            'yellowstone' => 1499,
            'borneo' => 2799,
            'barrier-reef' => 2199
        ];
        return isset($basePrices[$destination]) ? $basePrices[$destination] : 2000;
    }
}

// Function to calculate total price
function calculateTotalPrice($destination, $participants, $package) {
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
    
    $basePrice = getBasePrice($destination);
    $modifier = isset($packageModifiers[$package]) ? $packageModifiers[$package] : 1.0;
    
    return $basePrice * $participants * $modifier;
}

// API endpoint to get available dates for a destination
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_dates') {
    header('Content-Type: application/json');
    
    $destination = sanitizeString($_GET['destination'] ?? '');
    
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
    
    $destination = sanitizeString($_GET['destination'] ?? '');
    $departureDate = sanitizeString($_GET['departure_date'] ?? '');
    $returnDate = sanitizeString($_GET['return_date'] ?? '');
    $participants = filter_input(INPUT_GET, 'participants', FILTER_VALIDATE_INT);
    $package = sanitizeString($_GET['package'] ?? '');
    
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
                // Calculate price using the function
                $totalPrice = calculateTotalPrice($destination, $participants, $package);
                
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
        error_log("Error checking availability: " . $e->getMessage());
        echo json_encode(['error' => 'Error checking availability. Please try again later.']);
    }
    
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<p>Form submitted. Processing...</p>";
    
    // Sanitize and validate input data
 // Instead of:
 $fullName = htmlspecialchars(trim($_POST['fullName']), ENT_QUOTES, 'UTF-8');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = sanitizeString($_POST['phone'] ?? '');
    $participants = filter_input(INPUT_POST, 'participants', FILTER_VALIDATE_INT);
    $destination = sanitizeString($_POST['destination'] ?? '');
    $tourPackage = sanitizeString($_POST['tourPackage'] ?? '');
    $departureDate = sanitizeString($_POST['departureDate'] ?? '');
    $returnDate = sanitizeString($_POST['returnDate'] ?? '');
    $specialRequirements = sanitizeString($_POST['specialRequirements'] ?? '');
    
    // Debug: Print received form data
    echo "<h2>Received Form Data:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
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
    
    if (empty($participants) || $participants < 1 || $participants > 20) {
        $errors[] = "Number of participants must be between 1 and 20";
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
    $departureDateObj = null;
    $returnDateObj = null;
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    
    try {
        $departureDateObj = new DateTime($departureDate);
        $returnDateObj = new DateTime($returnDate);
        
        if ($departureDateObj < $today) {
            $errors[] = "Departure date must be in the future";
        }
        
        if ($returnDateObj <= $departureDateObj) {
            $errors[] = "Return date must be after departure date";
        }
    } catch (Exception $e) {
        $errors[] = "Invalid date format";
    }
    
    // Check availability
    if (empty($errors)) {
        try {
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
                
                if ($spotsAvailable < $participants) {
                    $errors[] = "Not enough spots available for the selected dates. Only $spotsAvailable spots left.";
                }
            } else {
                $errors[] = "No availability information found for the selected dates";
            }
        } catch (PDOException $e) {
            error_log("Error checking availability: " . $e->getMessage());
            $errors[] = "Error checking availability. Please try again later.";
        }
    }
    
    // Display validation errors if any
    if (!empty($errors)) {
        echo "<h2>Validation Errors:</h2>";
        echo "<ul class='error'>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='success'>Validation passed. Proceeding with booking...</p>";
        
        try {
            // Generate a unique booking reference
            $bookingReference = 'WV' . date('Ymd') . rand(1000, 9999);
            echo "<p>Generated booking reference: $bookingReference</p>";
            
            // Calculate the trip duration
            $interval = $departureDateObj->diff($returnDateObj);
            $duration = $interval->days;
            
            // Calculate total price
            $totalPrice = calculateTotalPrice($destination, $participants, $tourPackage);
            
            // Show calculated price
            echo "<p>Total price calculated: $" . number_format($totalPrice, 2) . "</p>";
            
            // Start transaction
            $pdo->beginTransaction();
            
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
            
            // Update availability
            $stmt = $pdo->prepare("
                UPDATE availability
                SET spots_available = spots_available - :participants
                WHERE destination = :destination
                AND available_date BETWEEN :departure_date AND :return_date
                AND spots_available >= :participants
            ");
            
            $stmt->execute([
                'participants' => $participants,
                'destination' => $destination,
                'departure_date' => $departureDate,
                'return_date' => $returnDate
            ]);
            
            // Commit transaction
            $pdo->commit();
            
            echo "<p class='success'>Database operations successful!</p>";
            
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
                    <p>Thank you for booking your wildlife adventure with WildVentures. Your booking has been confirmed!</p>
                    
                    <div class='booking-details'>
                        <h2>Booking Details:</h2>
                        <p><strong>Booking Reference:</strong> $bookingReference</p>
                        <p><strong>Destination:</strong> " . ucfirst($destination) . "</p>
                        <p><strong>Package:</strong> " . ucfirst($tourPackage) . "</p>
                        <p><strong>Departure Date:</strong> " . date('F j, Y', strtotime($departureDate)) . "</p>
                        <p><strong>Return Date:</strong> " . date('F j, Y', strtotime($returnDate)) . "</p>
                        <p><strong>Duration:</strong> $duration days</p>
                        <p><strong>Number of Participants:</strong> $participants</p>
                        <p class='total-price'><strong>Total Price:</strong> $" . number_format($totalPrice, 2) . "</p>
                    </div>
                    
                    <p>Please note the following important information:</p>
                    <ul>
                        <li>A 20% deposit is required within 7 days to secure your booking.</li>
                        <li>Full payment is due 30 days before your departure date.</li>
                        <li>Please review our cancellation policy on our website.</li>
                        <li>You will receive a detailed itinerary and preparation guide 2 weeks before departure.</li>
                    </ul>
                    
                    <p>If you have any questions or need to make changes to your booking, please contact us at <a href='mailto:bookings@wildventures.com'>bookings@wildventures.com</a> or call us at +1-555-WILD-ADV.</p>
                    
                    <p>We look forward to providing you with an unforgettable wildlife experience!</p>
                    
                    <p>Best regards,<br>
                    The WildVentures Team</p>
                    
                    <div class='footer'>
                        <p>WildVentures Inc. | 123 Safari Way, Adventure City, AC 12345<br>
                        This email was sent to $email. Please do not reply to this email.</p>
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
            if (mail($to, $subject, $message, $headers)) {
                echo "<p class='success'>Confirmation email sent successfully!</p>";
            } else {
                echo "<p class='warning'>Failed to send confirmation email. Please check your email address.</p>";
            }
            
            // Show booking confirmation
            echo "
            <div class='confirmation'>
                <h2>Booking Successfully Confirmed!</h2>
                <p>Thank you for booking your wildlife adventure with WildVentures!</p>
                <div class='booking-details'>
                    <p><strong>Booking Reference:</strong> $bookingReference</p>
                    <p><strong>Destination:</strong> " . ucfirst($destination) . "</p>
                    <p><strong>Package:</strong> " . ucfirst($tourPackage) . "</p>
                    <p><strong>Departure Date:</strong> " . date('F j, Y', strtotime($departureDate)) . "</p>
                    <p><strong>Return Date:</strong> " . date('F j, Y', strtotime($returnDate)) . "</p>
                    <p><strong>Duration:</strong> $duration days</p>
                    <p><strong>Number of Participants:</strong> $participants</p>
                    <p class='total-price'><strong>Total Price:</strong> $" . number_format($totalPrice, 2) . "</p>
                </div>
                <p>A confirmation email has been sent to $email with all the details of your booking.</p>
                <p>For any questions or changes, please contact us at <a href='mailto:bookings@wildventures.com'>bookings@wildventures.com</a> or call +1-555-WILD-ADV.</p>
            </div>
            ";
            
        } catch (PDOException $e) {
            // Rollback transaction if something went wrong
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            
            echo "<p class='error'>Error processing booking: " . $e->getMessage() . "</p>";
            error_log("Booking error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WildVentures - Wildlife Adventure Booking</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f6f0;
            color: #333;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background-color: #2c5e1a;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
        
        h1 {
            margin: 0;
        }
        
        .booking-form {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            margin-top: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        button {
            background-color: #2c5e1a;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        button:hover {
            background-color: #3a7d23;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row > div {
            flex: 1;
        }
        
        .confirmation {
            background-color: #e7f4e4;
            border-left: 5px solid #2c5e1a;
            padding: 20px;
            margin-top: 20px;
            border-radius: 4px;
        }
        
        .booking-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }
        
        .total-price {
            font-size: 18px;
            color: #2c5e1a;
        }
        
        .error {
            color: #d9534f;
            background-color: #f2dede;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .success {
            color: #5cb85c;
            background-color: #dff0d8;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .warning {
            color: #f0ad4e;
            background-color: #fcf8e3;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .availability-info {
            margin-top: 10px;
            padding: 10px;
            border-radius: 4px;
            display: none;
        }
        
        .destination-info {
            margin-top: 20px;
            display: none;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        
        .price-estimate {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .featured-destination {
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .featured-destination img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .featured-content {
            padding: 15px;
        }
        
        .loading {
            display: none;
            text-align: center;
            margin-top: 10px;
        }
        
        footer {
            background-color: #2c5e1a;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>WildVentures - Wildlife Adventure Booking</h1>
        </div>
    </header>
    
    <main class="container">
        <div class="booking-form">
            <h2>Book Your Wildlife Adventure</h2>
            <form id="bookingForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="fullName">Full Name:</label>
                        <input type="text" id="fullName" name="fullName" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="participants">Number of Participants:</label>
                        <input type="number" id="participants" name="participants" min="1" max="20" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="destination">Destination:</label>
                    <select id="destination" name="destination" required>
                        <option value="">Select a destination</option>
                        <option value="serengeti">Serengeti National Park, Tanzania</option>
                        <option value="amazon">Amazon Rainforest, Brazil</option>
                        <option value="galapagos">Galapagos Islands, Ecuador</option>
                        <option value="yellowstone">Yellowstone National Park, USA</option>
                        <option value="borneo">Borneo Rainforest, Malaysia</option>
                        <option value="barrier-reef">Great Barrier Reef, Australia</option>
                    </select>
                </div>
                
                <div class="form-group" id="packageContainer">
                    <label for="tourPackage">Tour Package:</label>
                    <select id="tourPackage" name="tourPackage" required>
                        <option value="">Select a package</option>
                        <!-- Package options will be dynamically populated based on destination -->
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="departureDate">Departure Date:</label>
                        <input type="date" id="departureDate" name="departureDate" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="returnDate">Return Date:</label>
                        <input type="date" id="returnDate" name="returnDate" required>
                    </div>
                </div>
                
                <div id="availabilityInfo" class="availability-info"></div>
                
                <div class="form-group">
                    <label for="specialRequirements">Special Requirements:</label>
                    <textarea id="specialRequirements" name="specialRequirements" rows="4"></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit">Book Now</button>
                </div>
            </form>
        </div>
        
        <div id="destinationInfo" class="destination-info"></div>
        
        <div class="featured-destination">
            <img src="https://example.com/wildlife-image.jpg" alt="Wildlife Adventure">
            <div class="featured-content">
                <h3>Discover the Wild</h3>
                <p>Experience the thrill of witnessing nature's most magnificent creatures in their natural habitats. Our expert guides will ensure a safe and unforgettable adventure.</p>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2025 WildVentures. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        // Package options for each destination
        const packageOptions = {
            serengeti: [
                { value: 'migration', text: 'Great Migration Safari' },
                { value: 'photography', text: 'Wildlife Photography Safari' },
                { value: 'luxury', text: 'Luxury Safari Experience' },
                { value: 'standard', text: 'Standard Safari Package' }
            ],
            amazon: [
                { value: 'river', text: 'Amazon River Expedition' },
                { value: 'indigenous', text: 'Indigenous Cultures & Wildlife' },
                { value: 'photography', text: 'Rainforest Photography Adventure' },
                { value: 'standard', text: 'Standard Amazon Package' }
            ],
            galapagos: [
                { value: 'cruise', text: 'Island Cruise Expedition' },
                { value: 'diving', text: 'Diving & Wildlife Adventure' },
                { value: 'luxury', text: 'Luxury Island Experience' },
                { value: 'standard', text: 'Standard Galapagos Package' }
            ],
            yellowstone: [
                { value: 'geyser', text: 'Geyser & Wildlife Tour' },
                { value: 'photography', text: 'Photography Expedition' },
                { value: 'wildlife', text: 'Wildlife Spotting Focus' },
                { value: 'standard', text: 'Standard Yellowstone Package' }
            ],
            borneo: [
                { value: 'orangutan', text: 'Orangutan Conservation Tour' },
                { value: 'jungle', text: 'Jungle Exploration Adventure' },
                { value: 'photography', text: 'Wildlife Photography Focus' },
                { value: 'standard', text: 'Standard Borneo Package' }
            ],
            'barrier-reef': [
                { value: 'diving', text: 'Premium Diving Experience' },
                { value: 'snorkeling', text: 'Snorkeling & Marine Life Tour' },
                { value: 'research', text: 'Marine Research Expedition' },
                { value: 'standard', text: 'Standard Great Barrier Reef Package' }
            ]
        };
        
        // Destination information
        const destinationInfo = {
            serengeti: {
                description: "Experience the magnificent wildlife of Serengeti National Park, home to the Great Migration. Witness lions, elephants, giraffes and more in their natural habitat.",
                bestTime: "June to October for the Great Migration",
                priceRange: "$2,500 - $5,000 per person"
            },
            amazon: {
                description: "Explore the world's largest rainforest with incredible biodiversity. See exotic birds, monkeys, sloths, and possibly pink river dolphins.",
                bestTime: "May to June or November to December (dry seasons)",
                priceRange: "$2,200 - $4,500 per person"
            },
            galapagos: {
                description: "Discover Darwin's living laboratory with unique endemic species. Encounter giant tortoises, marine iguanas, and blue-footed boobies.",
                bestTime: "December to May for warmer waters and calmer seas",
                priceRange: "$3,500 - $6,000 per person"
            },
            yellowstone: {
                description: "America's first national park offers geysers, hot springs, and abundant wildlife including bison, wolves, and bears.",
                bestTime: "May to September for wildlife viewing and good weather",
                priceRange: "$1,800 - $3,500 per person"
            },
            borneo: {
                description: "Trek through ancient rainforests to spot orangutans, pygmy elephants, and proboscis monkeys in their natural habitat.",
                bestTime: "March to October for drier conditions",
                priceRange: "$2,300 - $4,800 per person"
            },
            'barrier-reef': {
                description: "Dive or snorkel the world's largest coral reef system with over 1,500 species of fish and 600 types of coral.",
                bestTime: "June to October for best visibility and weather",
                priceRange: "$2,800 - $5,500 per person"
            }
        };
        
        // Base prices for each destination and package type
        const basePrices = {
            serengeti: {
                standard: 2500,
                migration: 3800,
                photography: 3200,
                luxury: 5000
            },
            amazon: {
                standard: 2200,
                river: 3100,
                indigenous: 2800,
                photography: 3400
            },
            galapagos: {
                standard: 3500,
                cruise: 5200,
                diving: 4800,
                luxury: 6000
            },
            yellowstone: {
                standard: 1800,
                geyser: 2200,
                photography: 2500,
                wildlife: 2800
            },
            borneo: {
                standard: 2300,
                orangutan: 3200,
                jungle: 2800,
                photography: 3500
            },
            'barrier-reef': {
                standard: 2800,
                diving: 4200,
                snorkeling: 3300,
                research: 3700
            }
        };
        
        // Update package options when destination changes
        document.getElementById('destination').addEventListener('change', function() {
            const destination = this.value;
            const packageSelect = document.getElementById('tourPackage');
            const destinationInfoDiv = document.getElementById('destinationInfo');
            
            // Clear existing options
            packageSelect.innerHTML = '<option value="">Select a package</option>';
            
            // If a destination is selected
            if (destination) {
                // Add new options based on selected destination
                packageOptions[destination].forEach(function(pkg) {
                    const option = document.createElement('option');
                    option.value = pkg.value;
                    option.text = pkg.text;
                    packageSelect.appendChild(option);
                });
                
                // Show destination information
                const info = destinationInfo[destination];
                destinationInfoDiv.innerHTML = `
                    <h3>${document.getElementById('destination').options[document.getElementById('destination').selectedIndex].text}</h3>
                    <p>${info.description}</p>
                    <p><strong>Best Time to Visit:</strong> ${info.bestTime}</p>
                    <p><strong>Price Range:</strong> ${info.priceRange}</p>
                `;
                destinationInfoDiv.style.display = 'block';
            } else {
                destinationInfoDiv.style.display = 'none';
            }
        });
        
        // Calculate duration between departure and return dates
        function calculateDuration() {
            const departureDate = new Date(document.getElementById('departureDate').value);
            const returnDate = new Date(document.getElementById('returnDate').value);
            
            if (departureDate && returnDate && returnDate >= departureDate) {
                const diffTime = Math.abs(returnDate - departureDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Including both departure and return days
                return diffDays;
            }
            
            return null;
        }
        
        // Calculate price estimate
        function calculatePrice() {
            const destination = document.getElementById('destination').value;
            const tourPackage = document.getElementById('tourPackage').value;
            const participants = parseInt(document.getElementById('participants').value);
            const duration = calculateDuration();
            
            if (destination && tourPackage && participants && duration) {
                const basePrice = basePrices[destination][tourPackage];
                
                // Calculate total price based on participants and duration
                let totalPrice = basePrice * participants;
                
                // Apply discount for longer stays
                if (duration > 10) {
                    totalPrice *= 0.9; // 10% discount for trips longer than 10 days
                } else if (duration > 7) {
                    totalPrice *= 0.95; // 5% discount for trips longer than 7 days
                }
                
                // Apply group discount
                if (participants >= 6) {
                    totalPrice *= 0.9; // 10% discount for groups of 6 or more
                } else if (participants >= 3) {
                    totalPrice *= 0.95; // 5% discount for groups of 3 or more
                }
                
                return totalPrice.toFixed(2);
            }
            
            return null;
        }
        
        // Update availability and price info when dates or participants change
        function updateAvailabilityAndPrice() {
            const availabilityInfo = document.getElementById('availabilityInfo');
            const duration = calculateDuration();
            const totalPrice = calculatePrice();
            
            if (duration) {
                let infoHtml = `<p>Trip duration: ${duration} days</p>`;
                
                if (totalPrice) {
                    infoHtml += `<p class="price-estimate">Estimated total price: $${totalPrice}</p>`;
                }
                
                availabilityInfo.innerHTML = infoHtml;
                availabilityInfo.style.display = 'block';
                availabilityInfo.className = 'availability-info success';
            } else {
                availabilityInfo.style.display = 'none';
            }
        }
        
        // Add event listeners for date changes
        document.getElementById('departureDate').addEventListener('change', updateAvailabilityAndPrice);
        document.getElementById('returnDate').addEventListener('change', updateAvailabilityAndPrice);
        document.getElementById('participants').addEventListener('change', updateAvailabilityAndPrice);
        document.getElementById('tourPackage').addEventListener('change', updateAvailabilityAndPrice);
        
        // Form validation
        document.getElementById('bookingForm').addEventListener('submit', function(event) {
            const departureDate = new Date(document.getElementById('departureDate').value);
            const returnDate = new Date(document.getElementById('returnDate').value);
            const today = new Date();
            
            // Set minimum booking date (e.g., at least 7 days in advance)
            const minBookingDate = new Date();
            minBookingDate.setDate(today.getDate() + 7);
            
            if (departureDate < minBookingDate) {
                event.preventDefault();
                alert('Departure date must be at least 7 days from today.');
                return false;
            }
            
            if (returnDate <= departureDate) {
                event.preventDefault();
                alert('Return date must be after departure date.');
                return false;
            }
            
            // Show loading indicator
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'loading';
            loadingDiv.innerHTML = '<p>Processing your booking...</p>';
            document.querySelector('.form-group:last-child').appendChild(loadingDiv);
            loadingDiv.style.display = 'block';
            
            return true;
        });
        
        // Set minimum date for departure and return date inputs
        window.addEventListener('load', function() {
            const today = new Date();
            const minBookingDate = new Date();
            minBookingDate.setDate(today.getDate() + 7);
            
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };
            
            document.getElementById('departureDate').min = formatDate(minBookingDate);
            document.getElementById('returnDate').min = formatDate(minBookingDate);
        });
    </script>
</body>
</html>