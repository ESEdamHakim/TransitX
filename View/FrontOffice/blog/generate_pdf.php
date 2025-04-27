<?php
require_once('C:/xampp/htdocs/TransitX-main/fpdf186/fpdf.php');

$article_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : 0;
$pdo = new PDO("mysql:host=localhost;dbname=transitx", "root", "");

$stmt = $pdo->prepare("SELECT * FROM article WHERE id_article = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    echo "Article introuvable !";
    exit;
}

$commentStmt = $pdo->prepare("SELECT * FROM commentaire WHERE id_article = ? ORDER BY date_commentaire DESC");
$commentStmt->execute([$article_id]);
$commentaires = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

// Création du PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetLeftMargin(10); // Marge gauche
$pdf->SetTopMargin(10); // Marge en haut
$pdf->SetRightMargin(10); // Marge droite

// Ajouter le logo TransitX centré en haut
$logoX = ($pdf->GetPageWidth() - 40) / 2; // Calculer la position X pour centrer le logo
$pdf->Image('C:/xampp/htdocs/TransitX-main/logo.png', $logoX, 8, 40); // Ajuste le chemin, la position, et la taille
$pdf->Ln(50); // Espace après l'image

// Ajouter le texte "TransitX" en gros juste en dessous du logo
$pdf->SetFont('Arial', 'B', 30);
$pdf->SetTextColor(76, 175, 80); // Vert comme TransitX
$pdf->Cell(0, 20, utf8_decode('TransitX'), 0, 1, 'C'); // Centrer le texte

// Titre de l’article
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(0); // Remettre la couleur du texte à noir
$pdf->MultiCell(0, 10, utf8_decode("« " . $article['titre'] . " »"), 0, 'C');
$pdf->Ln(5);

// Infos auteur et date
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0);
$pdf->Cell(0, 10, utf8_decode("Publié le : " . $article['date_publication']), 0, 1);
$pdf->Cell(0, 10, utf8_decode("Auteur : " . $article['auteur']), 0, 1);
$pdf->Ln(5);

// Contenu de l’article
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 8, utf8_decode(strip_tags($article['contenu'])));
$pdf->Ln(10);

// Titre section commentaires
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(31, 79, 101); // Bleu foncé
$pdf->Cell(0, 10, utf8_decode("Commentaires"), 0, 1);
$pdf->SetTextColor(0);

// Affichage des commentaires
$pdf->SetFont('Arial', '', 11);
if (count($commentaires) === 0) {
    $pdf->MultiCell(0, 8, utf8_decode("Aucun commentaire pour cet article."));
} else {
    foreach ($commentaires as $c) {
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 6, utf8_decode("Posté le : " . $c['date_commentaire']), 0, 1);
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 8, utf8_decode(html_entity_decode($c['contenu_commentaire'])));
        $pdf->Ln(5);
    }
}

// Export PDF
$pdf->Output('D', 'article-' . $article_id . '.pdf');
?>
