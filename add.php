<?php
require 'db.php';
function h($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
$error = '';
$data = ['student_name'=>'','matric_number'=>'','subject_name'=>'','subject_code'=>'','test1'=>'0','test2'=>'0','final_exam'=>'0'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $key => $value) $data[$key] = trim($_POST[$key] ?? $value);
    if ($data['student_name'] === '' || $data['matric_number'] === '' || $data['subject_name'] === '' || $data['subject_code'] === '') {
        $error = 'Please fill in all student and subject fields.';
    } elseif (!is_numeric($data['test1']) || $data['test1'] < 0 || $data['test1'] > 20 ||
              !is_numeric($data['test2']) || $data['test2'] < 0 || $data['test2'] > 30 ||
              !is_numeric($data['final_exam']) || $data['final_exam'] < 0 || $data['final_exam'] > 50) {
        $error = 'Marks must be Test 1: 0–20, Test 2: 0–30, and Final examination: 0–50.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO students (student_name, matric_number, subject_name, subject_code, test1, test2, final_exam) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$data['student_name'], $data['matric_number'], $data['subject_name'], $data['subject_code'], $data['test1'], $data['test2'], $data['final_exam']]);
        header('Location: view.php?id=' . $pdo->lastInsertId() . '&saved=1'); exit;
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Add Result | Student Grading System</title><link rel="stylesheet" href="style.css"></head><body><main class="container">
<h1>Student Grading System</h1><nav><a href="index.php">Records</a> | <a href="add.php">Add Result</a> | <a href="index.php">Logout (admin)</a></nav>
<h2>Add Result</h2>
<?php if ($error): ?><p class="danger"><?= h($error) ?></p><?php endif; ?>
<form method="post">
<label>Student name<input name="student_name" value="<?= h($data['student_name']) ?>" required></label>
<label>Matric number<input name="matric_number" value="<?= h($data['matric_number']) ?>" required></label>
<label>Subject name<input name="subject_name" value="<?= h($data['subject_name']) ?>" required></label>
<label>Subject code<input name="subject_code" value="<?= h($data['subject_code']) ?>" required></label>
<label>Test 1 (out of 20)<input type="number" name="test1" min="0" max="20" step="0.01" value="<?= h($data['test1']) ?>" required></label>
<label>Test 2 (out of 30)<input type="number" name="test2" min="0" max="30" step="0.01" value="<?= h($data['test2']) ?>" required></label>
<label>Final examination (out of 50)<input type="number" name="final_exam" min="0" max="50" step="0.01" value="<?= h($data['final_exam']) ?>" required></label>
<div class="actions"><button type="submit">Save Result</button><a href="index.php">Back to records</a></div>
</form></main></body></html>
