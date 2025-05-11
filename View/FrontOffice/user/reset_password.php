<?php
session_start();


require_once __DIR__ . '/../../../Controller/UserC.php';
require_once __DIR__ . '/../../assets/Utils/Mailer.php';

$error = '';
$success = '';
$userController = new UserC();

function generateVerificationCode()
{
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function sendVerificationCode($email, $code)
{
    try {
        $mailer = new Mailer(
            'smtp.gmail.com',
            587,
            'culeks.here@gmail.com',
            'elck jgub honm vsop',
            'culeks.here@gmail.com',
            'TransitX'
        );
        $mailer->setDebug(true); // Enable debug mode

        $subject = 'Code de réinitialisation de mot de passe - TransitX';

        $message = "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #333;'>Réinitialisation de mot de passe</h2>
            <p>Voici votre code de vérification pour réinitialiser votre mot de passe :</p>
            <div style='background: #f4f4f4; padding: 15px; text-align: center; font-size: 24px; letter-spacing: 5px; margin: 20px 0;'>
                <strong>{$code}</strong>
            </div>
            <p>Ce code est valable pendant 15 minutes.</p>
            <p>Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet email.</p>
        </div>";

        return $mailer->send($email, $subject, $message);
    } catch (Exception $e) {
        error_log('Email sending failed: ' . $e->getMessage());
        return false;
    }
}

// Handle request for new verification code
if (isset($_GET['new_code']) && isset($_SESSION['reset_password'])) {
    $email = $_SESSION['reset_password']['email'];
    $code = generateVerificationCode();
    $_SESSION['reset_password'] = [
        'email' => $email,
        'code' => $code,
        'expires' => time() + 900, // 15 minutes
        'code_sent' => false
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email']) && empty($_POST['verification_code'])) {
        // Step 1: Send verification code
        $email = $_POST['email'];
        $user = $userController->getUserByEmail($email);

        if ($user) {
            $code = generateVerificationCode();
            $_SESSION['reset_password'] = [
                'email' => $email,
                'code' => $code,
                'expires' => time() + 900, // 15 minutes
                'code_sent' => false
            ];

            if (sendVerificationCode($email, $code)) {
                $_SESSION['reset_password']['code_sent'] = true;
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $error = "Erreur lors de l'envoi de l'email. Veuillez réessayer.";
                unset($_SESSION['reset_password']);
            }
        } else {
            $error = "Aucun compte n'est associé à cette adresse email.";
        }
    } else if (!empty($_POST['verification_code']) && !empty($_POST['new_password'])) {
        // Step 2: Verify code and update password
        if (
            !isset($_SESSION['reset_password']) ||
            time() > $_SESSION['reset_password']['expires']
        ) {
            $error = "Le code de vérification a expiré. Veuillez recommencer.";
            unset($_SESSION['reset_password']);
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else if ($_POST['verification_code'] !== $_SESSION['reset_password']['code']) {
            $error = "Code de vérification incorrect.";
        } else if ($_POST['new_password'] !== $_POST['confirm_password']) {
            $error = "Les mots de passe ne correspondent pas.";
        } else {
            $email = $_SESSION['reset_password']['email'];
            $new_password = $_POST['new_password'];

            if ($userController->updatePassword($email, $new_password)) {
                $success = "Votre mot de passe a été réinitialisé avec succès.";
                unset($_SESSION['reset_password']);
                header('Location: ../../../index.php');
                exit;
                $success = "Votre mot de passe a été mis à jour avec succès.";
                header("refresh:3;url=../../../index.php");
            } else {
                $error = "Erreur lors de la mise à jour du mot de passe.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe - TransitX</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <style>
        .verification-code {
            letter-spacing: 8px;
            font-size: 24px;
            text-align: center;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            position: relative;
        }

        .step.active {
            background: #86b391;
            color: white;
        }

        .step::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 2px;
            background: #ddd;
            right: -20px;
            top: 50%;
        }

        .step:last-child::after {
            display: none;
        }
    </style>
</head>

<body>
    <div class="auth-page">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="logo-container">
                        <img src="../../assets/images/logo.png" alt="TransitX Logo" class="auth-logo">
                        <span class="logo-text">TransitX</span>
                    </div>
                    <h1>Mot de passe oublié</h1>
                    <p>Entrez votre adresse email pour réinitialiser votre mot de passe.</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <?php if (empty($success)): ?>
                    <div class="step-indicator">
                        <div class="step <?= empty($_SESSION['reset_password']['code_sent']) ? 'active' : '' ?>">1</div>
                        <div class="step <?= !empty($_SESSION['reset_password']['code_sent']) ? 'active' : '' ?>">2</div>
                    </div>

                    <form class="auth-form" method="post">
                        <?php if (empty($_SESSION['reset_password']) || empty($_SESSION['reset_password']['code_sent'])): ?>
                            <!-- Step 1: Email Input -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" id="email" name="email" required
                                        placeholder="Entrez votre adresse email"
                                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                Envoyer le code de vérification
                            </button>
                        <?php elseif (!empty($_SESSION['reset_password']['code_sent'])): ?>
                            <div class="form-section">
                                <div class="form-group">
                                    <p class="text-muted" style="margin-bottom: 0.5rem;">
                                        Un code a été envoyé à
                                        <strong><?= htmlspecialchars($_SESSION['reset_password']['email']) ?></strong>
                                    </p>
                                    <input type="text" id="verification_code" name="verification_code" maxlength="6" required
                                        placeholder="Entrez le code à 6 chiffres">
                                </div>

                                <div class="form-group">
                                    <label for="new_password">Nouveau mot de passe</label>
                                    <input type="password" id="new_password" name="new_password" required minlength="8"
                                        placeholder="8 caractères minimum">
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8"
                                        placeholder="Répétez le mot de passe">
                                </div>

                                <button type="submit" class="btn btn-primary btn-block">
                                    Réinitialiser le mot de passe
                                </button>

                                <div class="text-center mt-3">
                                    <a href="?new_code=1" class="text-primary"
                                        style="color: #86b391; text-decoration: none; font-size: 0.9em;">
                                        Renvoyer un nouveau code
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
                <div class="auth-footer">
                    <p>Vous vous souvenez de votre mot de passe? <a href="../../../index.php">Se connecter</a></p>
                </div>
            </div>
        </div>
        <div class="auth-image">
            <div class="overlay"></div>
        </div>
    </div>
</body>

</html>