<?php
require_once __DIR__ . '/assets/PDFgen/fpdf.php';

$pdo = new PDO("mysql:host=localhost;dbname=transitx;charset=utf8", "root", "");

// Fetch data
$stmt = $pdo->query("
    SELECT r.*, c.lieu_depart, c.lieu_arrivee 
    FROM reclamation r 
    LEFT JOIN covoiturage c ON r.id_covoit = c.id_covoit
    ORDER BY r.date_rec DESC
");

$reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// PDF init
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(10, 5, 10);
$pdf->SetAutoPageBreak(true, 20);

// Logo
$logoPath = __DIR__ . '/../../assets/images/logo.png';
if (file_exists($logoPath)) {
    $logoX = ($pdf->GetPageWidth() - 30) / 2;
    $pdf->Image($logoPath, $logoX, 10, 25);
}
$pdf->Ln(40);

// Title at top
$pdf->SetFont('Arial', 'B', 22);
$pdf->SetTextColor(31, 79, 101); // #1f4f65
$pdf->Cell(0, 10, utf8_decode('Liste des Réclamations - TransitX'), 0, 1, 'C');
$pdf->Ln(8);
$pdf->SetDrawColor(31, 79, 101);
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(8);

// Body
if (count($reclamations) === 0) {
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->SetTextColor(100);
    $pdf->Cell(0, 10, utf8_decode("Aucune réclamation trouvée."), 0, 1, 'C');
} else {
    foreach ($reclamations as $index => $r) {
        // Box background
        if ($index % 2 === 0) {
            $pdf->SetFillColor(245, 245, 245); // Light gray background
        } else {
            $pdf->SetFillColor(255, 255, 255); // White
        }
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial', '', 11);

        // Box start
        $pdf->Cell(0, 10, "", 0, 1); // Spacer

        // Header
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(0, 8, utf8_decode("Réclamation #{$r['id_rec']}"), 0, 1, 'L', true);

        // Details
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, utf8_decode("Objet : {$r['objet']}"), 0, 1, 'L', true);
        $pdf->Cell(0, 8, utf8_decode("Date : {$r['date_rec']} | Statut : {$r['statut']}"), 0, 1, 'L', true);

        if ($r['lieu_depart'] && $r['lieu_arrivee']) {
            $pdf->Cell(0, 8, utf8_decode("Covoiturage : {$r['lieu_depart']} à {$r['lieu_arrivee']}"), 0, 1, 'L', true);
        }

        // Description
        $pdf->SetFont('Arial', 'I', 11);
        $pdf->MultiCell(0, 8, utf8_decode("Description : " . strip_tags($r['description'])), 0, 'L', true);

        // Divider
        $pdf->SetDrawColor(220, 220, 220);
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
    }
}

// Output
$pdf->Output('D', 'Les Reclamations.pdf');
