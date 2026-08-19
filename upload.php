<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload PDF</title>
</head>
<body>
<h2>Upload PDF Document</h2>
<form action="upload.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="pdf_file" accept=".pdf" required>
    <br><br>
    <button type="submit" name="upload">
        Upload PDF
    </button>
</form>
<?php
if(isset($_POST['upload'])){
    $file = $_FILES['pdf_file'];
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    if($fileError != 0){
        echo "File upload error.";
        exit;
    }
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if($extension != "pdf"){
        echo "Only PDF files are allowed.";
        exit;
    }
    if($fileSize > 10 * 1024 * 1024){
        echo "File size must be less than 10MB.";
        exit;
    }
    $uploadPath = "uploads/" . $fileName;
    if(move_uploaded_file($fileTmp, $uploadPath)){
        echo "<h3>PDF uploaded successfully</h3>";
        echo "File Name: " . $fileName;
        echo "<br><br>";
        echo "<a href='process_pdf.php?file=$fileName'>
        Process PDF
        </a>";
    }
    else{
        echo "Upload failed";
    }
}
?>
</body>
</html>