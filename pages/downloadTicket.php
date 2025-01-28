<?php
require_once '../vendor/autoload.php'; // Include TCPDF if using Composer

// Retrieve ticket data from POST
$eventName = $_POST['EventName'] ?? 'Unknown Event';
$fullName = $_POST['FullName'] ?? 'Unknown Name';
$email = $_POST['Email'] ?? 'Unknown Email';
$ticketType = $_POST['TicketType'] ?? 'General Admission';
$imageSrc = $_SERVER['DOCUMENT_ROOT'] . '/Event Ticketing/images/qr code.png'; // Ensure this is a valid absolute path

// Initialize TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('TicketLokal');
$pdf->SetTitle('Ticket Lokal');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(10, 10, 10);

// Add a page
$pdf->AddPage();

// Ticket content styled to match the `ticketDetail` design
$html = "
    <div style='width:100%; max-width:600px; margin:0 auto; padding:20px; border:2px solid #333; border-radius:15px; font-family:Helvetica, Arial, sans-serif; background-color:#ffffff;'>
        <!-- Header -->
        <div style='font-size:36px; font-weight:bold; margin-bottom:15px; color:#000000; text-align:center; text-transform:uppercase;'>TicketLocal</div>

        <!-- QR Code -->
        <div style='text-align:center; margin-bottom:20px;'>
             " . ($imageSrc ? "<img src='$imageSrc' style='width:200px; height:200px;'>" : "<p style='color:red;'>QR Code not available</p>") . "
        </div>

        <!-- Ticket Info -->
        <div style='font-size:22px; font-weight:bold; color:#333; text-align:center; text-transform:uppercase; margin-bottom:10px;'>
            $fullName
        </div>
        <div style='font-size:18px; font-weight:bold; color:#000; text-align:center; margin-bottom:20px;'>
            $ticketType
        </div>

        <!-- Event and Email Info -->
        <div style='margin:20px 0; border-top:1px solid #ddd; padding-top:10px;'>
            <div style='font-size:16px; text-align:left; font-weight:bold; margin-bottom:5px;'>$eventName</div>
            <div style='font-size:14px; text-align:right;'>$email</div>
        </div>

        <!-- Terms & Conditions -->
        <div style='margin-top:20px; font-size:14px; color:#000; line-height:1.6; text-align:left;'>
            <p style='font-size:16px; font-weight:bold;'>Terms & Conditions</p>
            <ul>
                <li>Keep this ticket safe. It is required for entry to the event.</li>
                <li>This ticket is non-transferable and non-refundable.</li>
                <li>Ensure your details are correct before the event.</li>
                <li>The event organizer reserves the right to make changes to the event.</li>
                <li>Contact support at <a href='mailto:support@ticketlokal.com'>support@ticketlokal.com</a> for assistance.</li>
            </ul>
        </div>
    </div>
";

// Write content to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF for download
$pdf->Output('ticket.pdf', 'D');
