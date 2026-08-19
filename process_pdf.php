<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
putenv("PATH=C:\\Program Files\\gs\\gs10.07.1\\bin;" . getenv("PATH"));
require 'vendor/autoload.php';
require 'db.php';
use Smalot\PdfParser\Parser;
if(isset($_GET['file'])){
    $fileName = urldecode($_GET['file']);
    $pdfPath = __DIR__ . "/uploads/" . $fileName;
    echo "<h2>PDF Processing</h2>";
    echo "File Name: ".$fileName."<br><br>";
    if(!file_exists($pdfPath)){
        echo "PDF file not found";
        exit;
    }
    try {
        $parser = new Parser();
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();
        if(trim($text) != ""){
            echo "<h3>Searchable PDF Detected</h3>";
            echo "<h4>Extracted Text:</h4>";
            echo nl2br($text);
            $documentType = "Searchable PDF";
            $ocrApplied = 0;
            $status = "success";
            $jsonData = json_encode([
                "file_name" => $fileName,
                "document_type" => $documentType,
                "text" => $text
            ]);
            $stmt = $conn->prepare(
            "INSERT INTO document_ocr_records
            (file_name, file_path, document_type, extracted_text, extracted_json, ocr_applied, ocr_status)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                "sssssis",
                $fileName,
                $pdfPath,
                $documentType,
                $text,
                $jsonData,
                $ocrApplied,
                $status
            );
            $stmt->execute();
            echo "<br>Data saved successfully";
        }
        else{
            echo "<h3>Scanned PDF Detected</h3>";
            echo "<h4>Running OCR...</h4>";
            $imageFolder = __DIR__ . "/temp_images/";
            if(!is_dir($imageFolder)){
                mkdir($imageFolder,0777,true);
            }
            $oldImages = glob($imageFolder."*.png");
            foreach($oldImages as $old){
                unlink($old);
            }
            echo "Converting PDF pages to images...<br>";
            $imagick = new Imagick();
            $imagick->setResolution(300,300);
            $imagick->readImage($pdfPath);
            $pageNumber = 1;
            $ocrText = "";
            foreach($imagick as $page){
                $page->setImageFormat("png");
                $imagePath = $imageFolder."page".$pageNumber.".png";
                $page->writeImage($imagePath);
                echo "Generated Image: ".$imagePath."<br>";
                $tesseract = "C:/Program Files/Tesseract-OCR/tesseract.exe";
                $ocrCommand = "\"$tesseract\" \"$imagePath\" stdout --psm 6 -l eng";
                $pageText = shell_exec($ocrCommand);
                $ocrText .= "\n".$pageText;
                $pageNumber++;
            }
            echo "<h4>OCR Extracted Text:</h4>";
            echo nl2br($ocrText);
            $documentType = "Scanned PDF";
            $ocrApplied = 1;
            $status = "success";
            $jsonData = json_encode([
                "file_name" => $fileName,
                "document_type" => $documentType,
                "ocr_text" => $ocrText
            ]);
            $stmt = $conn->prepare(
            "INSERT INTO document_ocr_records
            (file_name, file_path, document_type, extracted_text, extracted_json, ocr_applied, ocr_status)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                "sssssis",
                $fileName,
                $pdfPath,
                $documentType,
                $ocrText,
                $jsonData,
                $ocrApplied,
                $status
            );
            $stmt->execute();
            echo "<br>OCR data saved successfully";
            $imagick->clear();
            $imagick->destroy();
        }
    }
    catch(Exception $e){
        echo "Error: ".$e->getMessage();
    }
}
else{
    echo "No PDF received";
}
?>