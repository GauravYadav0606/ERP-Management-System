<?php
require_once __DIR__ . '/../src/Student.php';
require_once __DIR__ . '/../src/Grade.php';
require_once __DIR__ . '/../src/Attendance.php';
$studentModel = new Student($pdo);
$gradeModel = new Grade($pdo);
$attendanceModel = new Attendance($pdo);
if (!isset($_GET['id'])) {
    echo "Student ID required.";
    exit;
}
$student = $studentModel->getById($_GET['id']);
if (!$student) {
    echo "Student not found.";
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_grade'])) {
        $gradeModel->updateGrade($_POST['grade_id'], $_POST['grade'], $_POST['remarks']);
    } elseif (isset($_POST['delete_grade'])) {
        $gradeModel->deleteGrade($_POST['grade_id']);
    }
}
try {
    $stmt = $pdo->prepare("SELECT g.*, c.name as course_name, c.code FROM grades g JOIN courses c ON g.course_id = c.id WHERE g.student_id = ?");
    $stmt->execute([$student['id']]);
    $grades = $stmt->fetchAll();
    $attendanceRecords = $attendanceModel->getStudentAttendance($student['id']);    
    $totalClasses = count($attendanceRecords);
    $presentCount = 0;
    foreach ($attendanceRecords as $att) {
        if ($att['status'] == 'Present' || $att['status'] == 'Late') {
            $presentCount++;
        }
    }
    $percentage = $totalClasses > 0 ? round(($presentCount / $totalClasses) * 100) : 0;

} catch (Exception $e) {
    echo "Error loading data: " . $e->getMessage();
}
?>
<style>
.attendance-stat {
    text-align: center;
    margin: 1rem 0;
    padding: 1rem;
    background: #f0fdf4;
    border-radius: 0.5rem;
    border: 1px solid #bbf7d0;
}
.percentage-large {
    font-size: 2.5rem;
    font-weight: 800;
    color: #166534;
}
.label-text {
    font-size: 0.9rem;
    color: #166534;
    font-weight: 600;
}
</style>
<div class="header">
    <div style="display:flex; align-items:center; gap:1rem;">
        <a href="index.php?page=students" class="btn" style="background: var(--secondary-color); color: white;">&larr; Back</a>
    </div>
</div>
<div class="card-grid" style="grid-template-columns: 1fr 2fr; align-items: start;">    
    <div class="stat-card">
        <h3 style="margin-bottom:1rem; border-bottom:1px solid #eee; padding-bottom:0.5rem;">Personal Details</h3>      
        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
            <div style="flex:1;">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                <p><strong>ID:</strong> #<?php echo $student['id']; ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></p>
                <p><strong>DOB:</strong> <?php echo $student['dob']; ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($student['address']); ?></p>
                <p><strong>Joined:</strong> <?php echo date('M d, Y', strtotime($student['created_at'])); ?></p>
            </div>
            <div style="flex:0 0 120px; margin-left:1rem;">
                <div class="attendance-stat" style="margin:0; padding:1rem 0.5rem;">
                    <div class="percentage-large" style="font-size:2rem;"><?php echo $percentage; ?>%</div>
                    <div class="label-text" style="font-size:0.75rem;">Attendance</div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="stat-card" style="margin-bottom:1.5rem;">
            <h3 style="margin-bottom:1rem;">Academic Grades</h3>
            <?php if (empty($grades)): ?>
                <p style="color:var(--text-muted);">No grades recorded.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($grade['code'] . ' - ' . $grade['course_name']); ?></td>
                            <td><span style="font-weight:bold; color:var(--primary-color);"><?php echo htmlspecialchars($grade['grade']); ?></span></td>
                            <td><?php echo htmlspecialchars($grade['remarks']); ?></td>
                            <td>
                                <button onclick='openGradeModal(<?php echo json_encode($grade); ?>)' class="btn" style="padding:0.25rem 0.5rem; font-size:0.85rem; background:#fbbf24; color:#92400e;">Edit</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this grade?');">
                                    <input type="hidden" name="grade_id" value="<?php echo $grade['id']; ?>">
                                    <button type="submit" name="delete_grade" class="btn" style="padding:0.25rem 0.5rem; font-size:0.85rem; background:#fecaca; color:#b91c1c;">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>  
        <div id="editGradeModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
            <div style="background:white; padding:2rem; border-radius:1rem; width:400px; margin: 10% auto; position: relative;">
                <h3 style="margin-bottom:1rem;">Edit Grade</h3>
                <span onclick="document.getElementById('editGradeModal').style.display='none'" style="position:absolute; top:1rem; right:1.5rem; cursor:pointer; font-size:1.5rem;">&times;</span>
                <form method="POST">
                    <input type="hidden" name="grade_id" id="edit_grade_id">
                    <div class="form-group">
                        <label>Course</label>
                        <input type="text" id="edit_grade_course" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label>Grade</label>
                        <input type="text" name="grade" id="edit_grade_value" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea name="remarks" id="edit_grade_remarks" class="form-control"></textarea>
                    </div>
                    <button type="submit" name="edit_grade" class="btn btn-primary">Update Grade</button>
                </form>
            </div>
        </div>
        <script>
        function openGradeModal(data) {
            document.getElementById('edit_grade_id').value = data.id;
            document.getElementById('edit_grade_course').value = data.code + ' - ' + data.course_name;
            document.getElementById('edit_grade_value').value = data.grade;
            document.getElementById('edit_grade_remarks').value = data.remarks;
            document.getElementById('editGradeModal').style.display = 'flex';
        }
        </script>
        <div class="stat-card">
            <h3 style="margin-bottom:1rem;">Attendance History</h3>
            <?php if (empty($attendanceRecords)): ?>
                <p style="color:var(--text-muted);">No attendance records found.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendanceRecords as $att): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($att['date'])); ?></td>
                            <td>
                                <?php 
                                    if ($att['code']) {
                                        echo htmlspecialchars($att['code']);
                                    } elseif ($att['course_name']) {
                                        echo htmlspecialchars($att['course_name']);
                                    } else {
                                        echo '<span style="color:red; font-size:0.8rem;">(ID: ' . $att['course_id'] . ')</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <span style="
                                    padding: 0.25rem 0.5rem; 
                                    border-radius: 4px; 
                                    background: <?php echo $att['status'] == 'Present' ? '#dcfce7' : ($att['status'] == 'Absent' ? '#fee2e2' : '#fef9c3'); ?>;
                                    color: <?php echo $att['status'] == 'Present' ? '#166534' : ($att['status'] == 'Absent' ? '#991b1b' : '#854d0e'); ?>;
                                    font-size: 0.85rem; font-weight: 600;
                                ">
                                    <?php echo htmlspecialchars($att['status']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($att['remarks']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
