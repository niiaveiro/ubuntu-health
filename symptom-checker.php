<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Symptom Guidance';
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $symptom = clean_text($_POST['symptom'] ?? '', 80);
    $duration = clean_text($_POST['duration'] ?? '', 40);
    $severity = clean_text($_POST['severity'] ?? '', 30);
    $redFlags = isset($_POST['red_flags']) && is_array($_POST['red_flags']) ? $_POST['red_flags'] : [];
    $emergencyFlags = ['breathing','chest_pain','unconscious','stroke','severe_bleeding'];
    $urgent = count(array_intersect($emergencyFlags, $redFlags)) > 0;
    if ($urgent) {
        $result = ['level'=>'Urgent warning','class'=>'danger','text'=>'These warning signs may indicate a medical emergency. Contact local emergency services or go to the nearest health facility now. Do not wait for this website or an online reply.'];
    } elseif ($severity === 'severe') {
        $result = ['level'=>'Seek prompt medical care','class'=>'warning','text'=>'Because you reported severe symptoms, please contact a qualified health professional or visit a health facility promptly.'];
    } elseif ($duration === 'several_days' || $duration === 'getting_worse') {
        $result = ['level'=>'Arrange professional advice','class'=>'warning','text'=>'Symptoms that persist or get worse should be assessed by a qualified health professional. Please arrange medical advice.'];
    } else {
        $result = ['level'=>'General guidance only','class'=>'info','text'=>'This form cannot identify the cause of your symptom. Monitor how you feel, use reliable health information, and contact a qualified health professional if symptoms persist, worsen, or concern you.'];
    }
    try {
        $stmt = db()->prepare('INSERT INTO symptom_checks (symptom, duration, severity, guidance_level) VALUES (?, ?, ?, ?)');
        $stmt->execute([$symptom, $duration, $severity, $result['level']]);
    } catch (Throwable $ex) { /* The guidance still works if the database is not configured. */ }
}
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-heading"><span class="eyebrow">SYMPTOM GUIDANCE</span><h1>What are you experiencing?</h1><p>This tool offers general next-step information only. It does not diagnose or rule out illness.</p></section>
<?php if ($result): ?><section class="result-panel <?= e($result['class']) ?>"><strong><?= e($result['level']) ?></strong><p><?= e($result['text']) ?></p><a href="appointments.php" class="btn btn-primary">Request an appointment</a></section><?php endif; ?>
<form class="form-card" method="post">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label for="symptom">Main symptom (do not include your full name or ID)</label>
<select name="symptom" id="symptom" required><option value="">Choose a general category</option><option value="fever">Fever / feeling hot</option><option value="cough">Cough / sore throat</option><option value="headache">Headache</option><option value="stomach">Stomach discomfort</option><option value="skin">Skin concern</option><option value="other">Other</option></select>
<label for="duration">How long has it been happening?</label>
<select name="duration" id="duration" required><option value="">Choose duration</option><option value="today">Started today</option><option value="one_two_days">1–2 days</option><option value="several_days">Several days</option><option value="getting_worse">Getting worse</option></select>
<label for="severity">How would you describe it?</label>
<select name="severity" id="severity" required><option value="">Choose severity</option><option value="mild">Mild</option><option value="moderate">Moderate</option><option value="severe">Severe</option></select>
<fieldset><legend>Do any of these warning signs apply?</legend>
<label class="check"><input type="checkbox" name="red_flags[]" value="breathing"> Serious difficulty breathing</label>
<label class="check"><input type="checkbox" name="red_flags[]" value="chest_pain"> Severe or persistent chest pain</label>
<label class="check"><input type="checkbox" name="red_flags[]" value="unconscious"> Unconsciousness or cannot be woken</label>
<label class="check"><input type="checkbox" name="red_flags[]" value="stroke"> Sudden face/arm weakness or trouble speaking</label>
<label class="check"><input type="checkbox" name="red_flags[]" value="severe_bleeding"> Severe bleeding</label>
</fieldset>
<button class="btn btn-primary" type="submit">Get general guidance</button>
<p class="form-note">If a warning sign is happening now, seek urgent help immediately rather than waiting to submit this form.</p>
</form>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
