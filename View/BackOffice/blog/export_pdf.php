<?php
require_once('C:/xampp/htdocs/TransitX-main/fpdf186/fpdf.php');

$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");
$pdo->exec("SET NAMES 'utf8mb4'");

$stmt = $pdo->prepare("SELECT * FROM article WHERE id_article = ?");
$stmt->execute([$_GET['id']]);
$article = $stmt->fetch();

$pdf = new FPDF();
$pdf->AddPage();

// Titre de l'article
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0, 102, 204); // Bleu
$pdf->MultiCell(0, 10, utf8_decode($article['titre']), 0, 'C');
$pdf->SetTextColor(0);
$pdf->Ln(5);

// Tableau des informations
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(0, 10, utf8_decode("Informations de l'article"), 1, 1, 'C', true);

// Chaque info en ligne distincte
$pdf->SetFont('Arial', '', 11);
function infoRow($pdf, $label, $value) {
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(50, 10, utf8_decode($label), 1, 0, 'L', true);
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(140, 10, utf8_decode($value), 1, 1);
}

infoRow($pdf, "Auteur", $article['auteur']);
infoRow($pdf, "Date", $article['date_publication']);
infoRow($pdf, "Catégorie", $article['categorie']);
infoRow($pdf, "Tags", $article['tags']);

// Contenu
$pdf->SetFont('Arial', 'B', 12);
$pdf->Ln(5);
$pdf->Cell(0, 10, utf8_decode("Contenu de l'article"), 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 10, utf8_decode($article['contenu']), 1);

// Affichage de la photo si elle existe
if (!empty($article['photo'])) {
    $photoPath = 'C:/xampp/htdocs/TransitX-main/images/' . $article['photo'];
    if (file_exists($photoPath)) {
        $pdf->Ln(5);
        $pdf->Cell(0, 10, utf8_decode("Image associée à l'article:"), 0, 1);
        $pdf->Image($photoPath, 10, $pdf->GetY(), 60);
        $pdf->Ln(65);
    }
}

// Commentaires
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, "Commentaires :", 0, 1);
$pdf->Ln(3);

$stmt = $pdo->prepare("SELECT contenu_commentaire, date_commentaire FROM commentaire WHERE id_article = ?");
$stmt->execute([$_GET['id']]);
$commentaires = $stmt->fetchAll();

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(130, 10, "Contenu", 1, 0, 'C', true);
$pdf->Cell(60, 10, "Date", 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 11);
if (empty($commentaires)) {
    $pdf->Cell(190, 10, "Aucun commentaire.", 1, 1);
} else {
    foreach ($commentaires as $commentaire) {
        $yBefore = $pdf->GetY();
        $pdf->MultiCell(130, 10, utf8_decode($commentaire['contenu_commentaire']), 1);
        $yAfter = $pdf->GetY();
        $height = $yAfter - $yBefore;

        $pdf->SetXY(140, $yBefore);
        $date = new DateTime($commentaire['date_commentaire']);
        $pdf->MultiCell(60, $height, $date->format('d/m/Y H:i'), 1);
    }
}

$pdf->Output();
exit;
