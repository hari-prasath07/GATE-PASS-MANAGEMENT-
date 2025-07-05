<?php
// Check if 'name' and 'email' exist before using them
$name = isset($user['name']) ? $user['name'] : 'Default Name';
$email = isset($user['email']) ? $user['email'] : 'no-email@example.com';

// Start output buffering to avoid the output before PDF generation
ob_start();

// Example HTML output before generating PDF
echo "<h1>Welcome, $name</h1>";
echo "<p>Email: $email</p>";

// Clean the buffer to avoid any issues with PDF generation
ob_end_clean(); // This prevents unwanted output that could affect PDF creation

// Now include the mPDF library and create a new PDF
require_once __DIR__ . '/vendor/autoload.php';
use Mpdf\Mpdf;

// Initialize mPDF object
$pdf = new Mpdf();

// Add your content to the PDF
$htmlContent = '<h1>Leave Logs</h1>';
$htmlContent .= '<p>This is the content for the leave logs PDF.</p>';  // Add your dynamic content here
$pdf->WriteHTML($htmlContent);

// Output PDF to browser (force download)
$pdf->Output('leave_logs.pdf', 'D');
?>
