<?php
require 'db.php';

// Get all student records
$students = $pdo->query(
    'SELECT * FROM students ORDER BY id DESC'
)->fetchAll();

// Securely display text
function h($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}

// Calculate the final grade
function gradeFor($total) {
    if ($total >= 80) return 'A';
    if ($total >= 70) return 'B';
    if ($total >= 60) return 'C';
    if ($total >= 50) return 'D';
    return 'F';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Student Records | Student Grading System</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<main class="container">

    <h1>Student Grading System</h1>

    <p class="subtitle">
        Manage student records and academic performance.
    </p>

    <nav>
        <a href="index.php">Records</a>
        <a href="add.php">Add Result</a>
        <a href="index.php">Logout (admin)</a>
    </nav>

    <h2>Student Records</h2>

    <p>
        <a class="button" href="add.php">
            + Add Result
        </a>
    </p>

    <?php if (isset($_GET['saved'])): ?>
        <p class="notice">
            Student result saved successfully!
        </p>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <p class="notice">
            Student result deleted successfully.
        </p>
    <?php endif; ?>

    <?php if (count($students) > 0): ?>

        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Matric Number</th>
                    <th>Subject</th>
                    <th>Total Marks</th>
                    <th>Grade</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($students as $student): ?>

                    <?php
                    $total =
                        (float)$student['test1']
                        + (float)$student['test2']
                        + (float)$student['final_exam'];

                    $grade = gradeFor($total);
                    ?>

                    <tr>
                        <td>
                            <?= h($student['student_name']) ?>
                        </td>

                        <td>
                            <?= h($student['matric_number']) ?>
                        </td>

                        <td>
                            <?= h($student['subject_name']) ?>
                        </td>

                        <td>
                            <?= number_format($total, 2) ?>
                        </td>

                        <td>
                            <span class="grade grade-<?= h($grade) ?>">
                                <?= h($grade) ?>
                            </span>
                        </td>

                        <td>
                            <a href="view.php?id=<?= (int)$student['id'] ?>">
                                View
                            </a>

                            |

                            <a href="edit.php?id=<?= (int)$student['id'] ?>">
                                Edit
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

    <?php else: ?>

        <p>
            No student records found. Click
            "Add Result" to create your first record.
        </p>

    <?php endif; ?>

    <p class="small">
        Student Grading System &copy; <?= date('Y') ?>
    </p>

</main>

</body>
</html>