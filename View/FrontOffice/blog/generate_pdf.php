<?php
require_once __DIR__ . '../../../fpdf186/fpdf.php';

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

// Initialiser PDF
$pdf = new FPDF();
$pdf->AddPage();

// Fond gris clair
$pdf->SetFillColor(245, 245, 245);
$pdf->Rect(0, 0, 210, 297, 'F');

// Bloc blanc central
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(10, 10, 190, 277, 'F');

// Marges
$pdf->SetLeftMargin(20);
$pdf->SetRightMargin(20);
$pdf->SetTopMargin(20);
$pdf->SetY(20);

// 💡 Titre coloré et lisible
$pdf->SetFont('Arial', 'B', 18);
// Changer la couleur du titre en bleu foncé
$pdf->SetTextColor(31, 79, 101); // Bleu foncé
$pdf->MultiCell(0, 12, utf8_decode('« ' . $article['titre'] . ' »'), 0, 'C');
$pdf->Ln(3);
$pdf->Ln(5);

// 📆 Date et ✍ Auteur
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 8, utf8_decode("Date : " . $article['date_publication']), 0, 1);
$pdf->Cell(0, 8, utf8_decode("Auteur : " . $article['auteur']), 0, 1);
$pdf->Ln(5);

// 📝 Contenu article
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0);
$pdf->MultiCell(0, 8, utf8_decode(strip_tags($article['contenu'])));
$pdf->Ln(10);

// 🏆 Ajout des sections à la fin du contenu
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(44, 62, 80); // Couleur pour les titres des sections

// Section 1
$pdf->Cell(0, 10, utf8_decode("Les Options de Trajets Flexibles"), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0);
$pdf->MultiCell(0, 8, utf8_decode("Les trajets flexibles permettent de choisir une option qui s'adapte à votre emploi du temps et à vos besoins..."));
$pdf->Ln(8);

// Section 2
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(44, 62, 80);
$pdf->Cell(0, 10, utf8_decode("Les Bénéfices Écologiques et Économiques"), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0);
$pdf->MultiCell(0, 8, utf8_decode("En réduisant le nombre de voitures sur les routes, TransitX contribue à diminuer l'empreinte carbone..."));
$pdf->Ln(8);

// Section 3
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(44, 62, 80);
$pdf->Cell(0, 10, utf8_decode("Des Trajets Plus Efficaces Grâce à la Technologie"), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0);
$pdf->MultiCell(0, 8, utf8_decode("Grâce à l'intelligence artificielle et aux données de trajets, TransitX optimise les itinéraires..."));
$pdf->Ln(15);

// 💬 Commentaires
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(44, 62, 80); // Bleu foncé-gris
$pdf->Cell(0, 10, utf8_decode(" Commentaires"), 0, 1);
$pdf->Ln(2);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '', 11);

if (count($commentaires) === 0) {
    $pdf->SetTextColor(120, 120, 120);
    $pdf->MultiCell(0, 8, utf8_decode("Aucun commentaire pour cet article."));
} else {
    foreach ($commentaires as $c) {
        $pdf->SetFillColor(240, 248, 255);
        $pdf->SetDrawColor(220, 220, 220);

        // 📅 Date du commentaire
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 6, utf8_decode(" " . $c['date_commentaire']), 0, 1);

        // Texte du commentaire
        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 8, utf8_decode(html_entity_decode($c['contenu_commentaire'])), 1, 'L', true);
        $pdf->Ln(4);
    }
}

// 🖼️ Petit logo en bas à droite
$pdf->SetY(-25); // Position verticale proche du bas
$pdf->Image('../../assets/images/logo.png', 170, $pdf->GetY(), 20); // 20mm de largeur (petit)

$pdf->Output('D', 'article-' . $article_id . '.pdf');
?>
