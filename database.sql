CREATE DATABASE IF NOT EXISTS student_grading;
USE student_grading;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    matric_number VARCHAR(50) NOT NULL,
    subject_name VARCHAR(150) NOT NULL,
    subject_code VARCHAR(30) NOT NULL,
    test1 DECIMAL(5,2) NOT NULL DEFAULT 0,
    test2 DECIMAL(5,2) NOT NULL DEFAULT 0,
    final_exam DECIMAL(5,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Optional sample record matching the example shown in your screenshot:
INSERT INTO students
(student_name, matric_number, subject_name, subject_code, test1, test2, final_exam)
SELECT 'mimi', 'CRM12347', 'Web Programming with PHP', 'CRM6136', 10, 20, 50
WHERE NOT EXISTS (SELECT 1 FROM students WHERE matric_number = 'CRM12347');
