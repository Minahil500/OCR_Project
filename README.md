1. List of required libraries, tools, extensions, and server configurations
1)Libraries
Composer
Smalot PDF Parser

2)Tools
ImageMagick
Ghostscript
Tesseract OCR

3)PHP Extensions
Imagick
mysqli
json

4)Server Configuration
MAMP
PHP
MySQL Database
Apache Web Server


2. Findings
The system successfully detects whether a PDF is searchable or scanned.
Searchable PDFs are processed using Smalot PDF Parser without OCR.
Scanned PDFs are converted into images using ImageMagick.
Tesseract OCR successfully extracts text from scanned PDF images.
Extracted text and JSON data are stored successfully in the MySQL database.


3. Limitations
Although the system works successfully,it has a few limitations.The accuracy of OCR mainly depends on the quality of the scanned PDF.If the document is blurry, tilted, or has low resolution,the extracted text may contain mistakes.At the moment, the system performs OCR only in English,so it may not produce accurate results for documents in other languages.In addition, processing large or multi-page PDF files can take more time and use more system memory.The current implementation focuses on extracting and storing text,and it does not provide advanced document analysis or intelligent data recognition.




