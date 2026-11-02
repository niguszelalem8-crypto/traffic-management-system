<?php
require_once __DIR__.'/config/auth.php';

$officerCount = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='officer'")->fetchColumn();
$firstOfficerSetup = ($officerCount === 0);

// First officer can be created only while no officer account exists.
// After that, only a signed-in officer may create another officer.
if (!$firstOfficerSetup) {
    require_role('officer');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Recheck during submission to prevent a stale page creating extra first officers.
    $officerCount = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='officer'")->fetchColumn();
    if ($officerCount > 0 && (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'officer')) {
        http_response_code(403);
        exit('Officer registration is restricted to an authenticated officer.');
    } elseif ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid full name and email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must contain at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $q = $pdo->prepare("INSERT INTO users(full_name,email,password_hash,role) VALUES(?,?,?,'officer')");
            $q->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            notice('Traffic officer account created. Sign in using the Traffic Officer account type.');
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            $error = 'That email may already be registered. Try another email address.';
        }
    }
}
$pageTitle = 'Traffic Officer Registration';
require __DIR__.'/pages/_header.php';
?>
<div class="auth-shell">
  <form class="auth-card" method="post">
    <div class="brand-mark">TP</div>
    <span class="eyebrow"><?= $firstOfficerSetup ? 'INITIAL OFFICER SETUP' : 'AUTHORIZED OFFICER MANAGEMENT' ?></span>
    <h1><?= $firstOfficerSetup ? 'Create the first officer account' : 'Register a traffic officer' ?></h1>
    <p><?= $firstOfficerSetup ? 'This setup is available only while the database has no officer account.' : 'Create an account for an authorized traffic officer.' ?></p>
    <?php if ($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?>
    <input type="hidden" name="csrf" value="<?=e(csrf_token())?>">
    <label>Full name<input name="full_name" required maxlength="120" value="<?=e($_POST['full_name']??'')?>"></label>
    <label>Email address<input type="email" name="email" required value="<?=e($_POST['email']??'')?>"></label>
    <label>Password<input type="password" name="password" minlength="8" required autocomplete="new-password"></label>
    <label>Confirm password<input type="password" name="confirm_password" minlength="8" required autocomplete="new-password"></label>
    <button class="button full">Create officer account</button>
    <p class="auth-bottom"><a href="login.php">Return to sign in</a></p>
  </form>
</div>
<?php require __DIR__.'/pages/_footer.php'; ?>
