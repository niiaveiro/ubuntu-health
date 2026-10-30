<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Appointment Request';
$success = false; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 verify_csrf();
 $name = clean_text($_POST['name'] ?? '', 100);
 $email = trim((string)($_POST['email'] ?? ''));
 $phone = clean_text($_POST['phone'] ?? '', 40);
 $facility = clean_text($_POST['facility'] ?? '', 150);
 $reason = clean_text($_POST['reason'] ?? '', 500);
 if ($name === '' || $facility === '' || $reason === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $error = 'Please enter your name, a valid email address, facility, and reason for the request.';
 } else {
  try {
   $stmt=db()->prepare('INSERT INTO appointment_requests (name, email, phone, facility, reason) VALUES (?, ?, ?, ?, ?)');
   $stmt->execute([$name,$email,$phone,$facility,$reason]);
   $success=true;
  } catch(Throwable $ex) { $error='The request could not be saved. Please check the database setup or contact the facility directly.'; }
 }
}
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-heading"><span class="eyebrow">ACCESS TO CARE</span><h1>Request an appointment</h1><p>Send a request for follow-up. This prototype does not contact a clinic automatically or confirm an appointment.</p></section>
<?php if($success): ?><div class="result-panel info"><strong>Request saved</strong><p>Your demo request has been saved. Contact the selected facility directly to confirm availability. Avoid sending urgent cases through this form.</p></div><?php endif; ?>
<?php if($error): ?><div class="result-panel danger"><?= e($error) ?></div><?php endif; ?>
<form class="form-card" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="form-row"><div><label for="name">Name</label><input id="name" name="name" maxlength="100" required></div><div><label for="email">Email address</label><input id="email" name="email" type="email" maxlength="190" required></div></div>
<label for="phone">Phone (optional)</label><input id="phone" name="phone" maxlength="40">
<label for="facility">Health facility you want to contact</label><input id="facility" name="facility" maxlength="150" placeholder="Enter a facility name" required>
<label for="reason">Reason for the request (avoid highly sensitive details)</label><textarea id="reason" name="reason" rows="4" maxlength="500" required></textarea>
<button class="btn btn-primary" type="submit">Submit request</button><p class="form-note">This demo stores submitted contact details in your local database. Use only test data during development.</p></form>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
