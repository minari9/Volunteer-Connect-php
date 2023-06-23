<?php
    require_once 'database.php';

    $pdo = connectToDatabase();

    // Retrieve the program ID from the query parameter
    $programId = $_GET['programId'];

    // Fetch the program details from the database based on the program ID
    $sql = "SELECT * FROM programs WHERE id = :programId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':programId', $programId, PDO::PARAM_INT);
    $stmt->execute();
    $program = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the program with the specified ID exists, display its details
    if ($program) {
        $programName = $program['Program'];
        $category = $program['Category'];
        $orgName = $program['orgName'];
        $email = $program['email'];
        $name = $program['firstName'];

        // Retrieve the response limit from the program
        $response_limit = $program['response_limit'];

        // Count the current number of responses for the program
        $sql = "SELECT COUNT(*) FROM form WHERE program_id = :programId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':programId', $programId, PDO::PARAM_INT);
        $stmt->execute();
        $currentResponses = $stmt->fetchColumn();

        // Compare the current number of responses with the response limit
        if ($currentResponses >= $response_limit) {
            // If the response limit has been reached, display an alert message and redirect the user
            echo '<script>alert("Response limit reached for this program."); window.location.href = "programs.php";</script>';
            exit; // Stop executing the rest of the code
        }
    } else {
        // If the program doesn't exist, display an error message and redirect the user
        echo "Program not found";
        echo '<script>window.location.href = "programs.php";</script>';
        exit; // Stop executing the rest of the code
    }

    // Process the form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve the form data
        $field1 = $_POST['field1'];
        $field2 = $_POST['field2'];
        // Add more fields as needed
    
        // Save the form data to the form table
        $sql = "INSERT INTO form (program_id, field1, field2) VALUES (:programId, :field1, :field2)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':programId', $programId, PDO::PARAM_INT);
        $stmt->bindParam(':field1', $field1);
        $stmt->bindParam(':field2', $field2);
        $stmt->execute();
    
        echo '<script>alert("Form submission successful!");</script>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Details</title>
</head>
<body>
    <h2>Program Details</h2>
    <p><strong>Program Name:</strong> <?php echo $programName; ?></p>
    <p><strong>Category:</strong> <?php echo $category; ?></p>
    <p><strong>Organization Name:</strong> <?php echo $orgName; ?></p>
    <p><strong>Email:</strong> <?php echo $email; ?></p>
    <p><strong>Name:</strong> <?php echo $name; ?></p>

    <h2>Form</h2>
    <form action="" method="POST">
        <input type="hidden" name="programId" value="<?php echo $programId; ?>">
        
        <label for="field1">Field 1:</label>
        <input type="text" id="field1" name="field1" required>

        <label for="field2">Field 2:</label>
        <input type="text" id="field2" name="field2" required>
        
        <!-- Add more form fields as needed -->

        <input type="submit" value="Submit">
    </form>
</body>
</html>
