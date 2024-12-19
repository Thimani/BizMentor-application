<?php
$host = "localhost"; 
$username = "root"; 
$password = "SRmysqlRoot@123"; 
$dbname = "temindu_pada";

$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $fileName = basename($_FILES['fileToUpload']['name']); 
    $fileTmpName = $_FILES['fileToUpload']['tmp_name'];
    $fileSize = $_FILES['fileToUpload']['size'];
    $fileType = $_FILES['fileToUpload']['type'];

    $uploadDirectory = 'uploads/';

    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    $filePath = $uploadDirectory . $fileName;

    if (file_exists($filePath)) {
        echo "Sorry, the file already exists.";
    } else {
        
        if (move_uploaded_file($fileTmpName, $filePath)) {

            $stmt = $conn->prepare("INSERT INTO uploaded_files (file_name, file_path, file_size, file_type) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $fileName, $filePath, $fileSize, $fileType); 

            if ($stmt->execute()) {
                header("Location: client_roadmap4.php");
                exit();
            } else {
                echo "Error saving file metadata to database: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>