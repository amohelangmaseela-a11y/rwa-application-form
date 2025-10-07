<?php
// Database configuration
$host = "localhost";
$user = "root";      // Default username for local MySQL
$pass = "";          // No password
$db   = "call_center_app"; // Your database name

// Connect to MySQL
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from form
$fullName = $_POST['fullName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$location = $_POST['location'];
$languages = isset($_POST['languages']) ? implode(", ", $_POST['languages']) : '';
$experience = $_POST['experience'];
$experienceDescription = $_POST['experienceDescription'] ?? '';
$motivation = $_POST['motivation'];
$resume = $_POST['resume'];
$availability = $_POST['availability'];
$acknowledged = isset($_POST['acknowledgement']) ? 1 : 0;

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO applications 
(full_name, email, phone, location, languages, experience, experience_description, motivation, resume_url, availability, acknowledged) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssssssssi", 
    $fullName,
    $email,
    $phone,
    $location,
    $languages,
    $experience,
    $experienceDescription,
    $motivation,
    $resume,
    $availability,
    $acknowledged
);

// Execute
if ($stmt->execute()) {
    // Prepare email content
    $to = "amohelangagnesmaseela@gmail.com";
    $subject = "New Call Center Application from $fullName";
    $body = "
    Full Name: $fullName\n
    Email: $email\n
    Phone: $phone\n
    Location: $location\n
    Languages: $languages\n
    Experience: $experience\n
    Experience Description: $experienceDescription\n
    Motivation: $motivation\n
    Resume URL: $resume\n
    Availability: $availability\n
    Acknowledged: " . ($acknowledged ? 'Yes' : 'No') . "\n
    ";

    // Send email
    if (mail($to, $subject, $body)) {
        echo "<h2 style='text-align:center; color: green;'>✅ Application submitted successfully!</h2>";
    } else {
        echo "<h2 style='text-align:center; color: red;'>❌ Application submitted but failed to send email.</h2>";
    }
} else {
    echo "<h2 style='color: red;'>❌ Error: " . $stmt->error . "</h2>";
}

$stmt->close();
$conn->close();
?>
