<?php
// Replace these database credentials with your own
$host = 'localhost';
$username = 'root';
$password = '@Mdata2002';
$database = 'testdata';

// Handle file upload and data insertion into MySQL
if (isset($_FILES['file']) && isset($_POST['x']) && isset($_POST['y'])) {
    // Check for valid file upload
    $file = $_FILES['file'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $filename = $file['mysql'];

        // Parse the uploaded file and get x and y values
        $xValues = $_POST['x'];
        $yValues = $_POST['y'];

        // Create a PDO connection to MySQL
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insert x and y values into the MySQL table
            $stmt = $conn->prepare("INSERT INTO xany (x, y) VALUES (:x, :y)");
            $stmt->bindParam(':x', $x);
            $stmt->bindParam(':y', $y);

            for ($i = 0; $i < count($xValues); $i++) {
                $x = $xValues[$i];
                $y = $yValues[$i];
                $stmt->execute();
            }

            // Close the database connection
            $conn = null;

            // Redirect back to the HTML page with a success message
            header('Location: index.html?success=1');
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Handle file upload error
        echo "File upload error.";
    }
}

// Retrieve data from the MySQL table and send it back to the HTML page
if (isset($_GET['retrieve']) && $_GET['retrieve'] === '1') {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve data from the MySQL table
        $stmt = $conn->prepare("SELECT id, x, y FROM xany");
        $stmt->execute();

        // Prepare data for JSON response
        $data = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        // Close the database connection
        $conn = null;

        // Send data as JSON response
        echo json_encode($data);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
