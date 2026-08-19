<?php
require "db.php";
$query = "SELECT * FROM document_ocr_records ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>OCR Records</title>
</head>
<body>
<h1>OCR Stored Records</h1>
<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>File Name</th>
    <th>Document Type</th>
    <th>OCR Applied</th>
    <th>Status</th>
    <th>Extracted Text</th>
    <th>JSON</th>
    <th>Date</th>
</tr>
<?php
while($row = mysqli_fetch_assoc($result)){
?>
<tr>
<td>
<?php echo $row['id']; ?>
</td>
<td>
<?php echo $row['file_name']; ?>
</td>
<td>
<?php echo $row['document_type']; ?>
</td>
<td>
<?php echo $row['ocr_applied']; ?>
</td>
<td>
<?php echo $row['ocr_status']; ?>
</td>
<td>
<?php echo nl2br($row['extracted_text']); ?>
</td>
<td>
<pre>
<?php echo $row['extracted_json']; ?>
</pre>
</td>
<td>
<?php echo $row['created_at']; ?>
</td>
</tr>
<?php
}
?>
</table>
</body>
</html>