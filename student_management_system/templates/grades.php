<?php
require_once __DIR__ . '/../src/Grade.php';
require_once __DIR__ . '/../src/Student.php';

$gradeModel = new Grade($pdo);
$studentModel = new Student($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_course'])) {
        $gradeModel->addCourse($_POST['name'], $_POST['code'], $_POST['description']);
    } elseif (isset($_POST['assign_grade'])) {
        $gradeModel->assignGrade($_POST['student_id'], $_POST['course_id'], $_POST['grade'], $_POST['remarks']);
    }
}

$courses = $gradeModel->getCourses();
$students = $studentModel->getAll();
?>

<div class="header">
    <div style="display:flex; gap:1rem;">
        <button onclick="document.getElementById('addCourseModal').style.display='block'" class="btn btn-primary" style="width: auto;">+ New Course</button>
        <button onclick="document.getElementById('assignGradeModal').style.display='block'" class="btn btn-primary" style="width: auto; background-color: var(--secondary-color);">+ Assign Grade</button>
    </div>
</div>

<h3>All Courses</h3>
<div class="card-grid">
    <?php foreach ($courses as $course): ?>
    <div class="stat-card">
        <div class="stat-title"><?php echo htmlspecialchars($course['code']); ?></div>
        <div class="stat-value" style="font-size: 1.5rem;"><?php echo htmlspecialchars($course['name']); ?></div>
        <p style="margin-top:0.5rem; color:var(--text-muted); font-size:0.9rem;"><?php echo htmlspecialchars($course['description']); ?></p>
    </div>
    <?php endforeach; ?>
</div>

<div id="addCourseModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:1rem; width:400px; margin: 10% auto; position: relative;">
        <h3 style="margin-bottom:1rem;">Add New Course</h3>
        <span onclick="document.getElementById('addCourseModal').style.display='none'" style="position:absolute; top:1rem; right:1.5rem; cursor:pointer; font-size:1.5rem;">&times;</span>
        <form method="POST">
            <div class="form-group">
                <label>Course Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Course Code</label>
                <input type="text" name="code" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <button type="submit" name="add_course" class="btn btn-primary">Save Course</button>
        </form>
    </div>
</div>

<div id="assignGradeModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:1rem; width:400px; margin: 10% auto; position: relative;">
        <h3 style="margin-bottom:1rem;">Assign Grade</h3>
        <span onclick="document.getElementById('assignGradeModal').style.display='none'" style="position:absolute; top:1rem; right:1.5rem; cursor:pointer; font-size:1.5rem;">&times;</span>
        <form method="POST">
            <div class="form-group">
                <label>Student</label>
                <select name="student_id" class="form-control" required>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['id']; ?>"><?php echo htmlspecialchars($student['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Course</label>
                <select name="course_id" class="form-control" required>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Grade</label>
                <input type="text" name="grade" class="form-control" placeholder="A, B+, 95..." required>
            </div>
            <div class="form-group">
                <label>Remarks</label>
                <textarea name="remarks" class="form-control"></textarea>
            </div>
            <button type="submit" name="assign_grade" class="btn btn-primary">Save Grade</button>
        </form>
    </div>
</div>
