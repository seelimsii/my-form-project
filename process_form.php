<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "formDB";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize inputs
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $message = $conn->real_escape_string($_POST['message']);

    // Validate and save file
    $file = $_FILES['file'];
    $fileSize = $file['size'];
    $fileType = $file['type'];
    $filePath = 'uploads/' . basename($file['name']);

    if (($fileType == 'image/jpeg' || $fileType == 'image/jpg') && $fileSize <= 500000) {
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Insert data into database
            $sql = "INSERT INTO submissions (name, email, mobile, message, file_path)
                    VALUES ('$name', '$email', '$mobile', '$message', '$filePath')";
            
            if ($conn->query($sql) === TRUE) {
                // Send email
                $to = $email;
                $subject = "Form Submission Received";
                $body = "Hi $name,\n\nWe have received your form submission. Thank you!\n\nBest regards,\nYour Company";
                $headers = "Content-Type: text/plain; charset=utf-8\r\n";

                mail($to, $subject, $body, $headers);

                echo "Form submitted successfully.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "File upload failed.";
        }
    } else {
        echo "Invalid file. Only JPEG files up to 500KB are allowed.";
    }

    $conn->close();
}
?>
