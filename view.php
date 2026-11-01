<?php
require 'db.php';

// Securely display text
function h($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Calculate final grade
function gradeFor($total) {
    if ($total >= 80) return 'A';
    if ($total >= 70) return 'B';
    if ($total >= 60) return 'C';
    if ($total >= 50) return 'D';
    return 'F';
}

// Get student ID from URL
$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

// Find student record
$stmt = $pdo->prepare(
    'SELECT * FROM students WHERE id = ?'
);

$stmt->execute([$id ?: 0]);

$student = $stmt->fetch();

// Stop if record does not exist
if (!$student) {
    http_response_code(404);
    exit(
        'Student record not found. ' .
        '<a href="index.php">Back to records</a>'
    );
}

// Calculate total marks
$total =
    (float)$student['test1']
    + (float)$student['test2']
    + (float)$student['final_exam'];

// Get grade
$grade = gradeFor($total);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Student Result | Student Grading System
    </title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<main class="container">

    <h1>Student Grading System</h1>

    <p class="subtitle">
        View student assessment results and final grades.
    </p>

    <nav>
        <a href="index.php">Records</a>
        <a href="add.php">Add Result</a>
        <a href="index.php">Logout (admin)</a>
    </nav>

    <h2>
        Result for <?= h($student['student_name']) ?>
    </h2>

    <?php if (isset($_GET['saved'])): ?>
        <p class="notice">
            Student result saved successfully!
        </p>
    <?php endif; ?>

    <table class="result-table">

        <tbody>

            <tr>
                <th>Student Name</th>
                <td>
                    <?= h($student['student_name']) ?>
                </td>
            </tr>

            <tr>
                <th>Matric Number</th>
                <td>
                    <?= h($student['matric_number']) ?>
                </td>
            </tr>

            <tr>
                <th>Subject Name</th>
                <td>
                    <?= h($student['subject_name']) ?>
                </td>
            </tr>

            <tr>
                <th>Subject Code</th>
                <td>
                    <?= h($student['subject_code']) ?>
                </td>
            </tr>

            <tr>
                <th>Test 1 (out of 20)</th>
                <td>
                    <?= number_format(
                        (float)$student['test1'],
                        2
                    ) ?>
                </td>
            </tr>

            <tr>
                <th>Test 2 (out of 30)</th>
                <td>
                    <?= number_format(
                        (float)$student['test2'],
                        2
                    ) ?>
                </td>
            </tr>

            <tr>
                <th>Final Examination (out of 50)</th>
                <td>
                    <?= number_format(
                        (float)$student['final_exam'],
                        2
                    ) ?>
                </td>
            </tr>

            <tr>
                <th>Total Marks (out of 100)</th>
                <td>
                    <strong>
                        <?= number_format($total, 2) ?>
                    </strong>
                </td>
            </tr>

            <tr>
                <th>Final Grade</th>

                <td>
                    <span class="grade grade-<?= h($grade) ?>">
                        <?= h($grade) ?>
                    </span>
                </td>
            </tr>

        </tbody>

    </table>

    <div class="actions">

        <a class="button"
           href="edit.php?id=<?= (int)$student['id'] ?>">
            Edit Result
        </a>

        <a href="index.php">
            Back to Records
        </a>

        <form
            method="post"
            action="delete.php"
            onsubmit="return confirm('Are you sure you want to delete this student result?');"
        >

            <input
                type="hidden"
                name="id"
                value="<?= (int)$student['id'] ?>"
            >

            <button class="danger" type="submit">
                Delete Result
            </button>

        </form>

    </div>

    <p class="small">
        Subject: <?= h($student['subject_code']) ?>
        &mdash;
        <?= h($student['subject_name']) ?>
    </p>

</main>

</body>
</html>