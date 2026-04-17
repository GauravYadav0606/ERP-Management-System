<?php
require_once __DIR__ . '/../src/Attendance.php';
require_once __DIR__ . '/../src/Grade.php';
$attendanceModel = new Attendance($pdo);
$gradeModel = new Grade($pdo);
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : null;
$courses = $gradeModel->getCourses();
if (!$course_id && !empty($courses)) {
    $course_id = $courses[0]['id'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mark_attendance'])) {
        foreach ($_POST['status'] as $studentId => $status) {
            $remarks = isset($_POST['remarks'][$studentId]) ? $_POST['remarks'][$studentId] : '';
            $attendanceModel->markAttendance($studentId, $_POST['course_id'], $date, $status, $remarks);
        }
        $message = "Attendance marked for $date";
    }
}
$attendanceList = [];
if ($course_id) {
    $attendanceList = $attendanceModel->getByDateAndCourse($date, $course_id);
}
?>
<div class="header">
    <form method="GET" style="display:flex; gap:1rem; align-items:center;">
        <input type="hidden" name="page" value="attendance">     
        <label>Course:</label>
        <select name="course_id" class="form-control" onchange="this.form.submit()">
            <?php foreach ($courses as $c): ?>
                <option value="<?php echo $c['id']; ?>" <?php echo $course_id == $c['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($c['name'] . ' (' . $c['code'] . ')'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>Date:</label>
        <input type="date" name="date" value="<?php echo $date; ?>" class="form-control" onchange="this.form.submit()">
    </form>
</div>
<?php if (empty($courses)): ?>
    <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
        Please add courses in the "Grades" section before marking attendance.
    </div>
<?php else: ?>
    <?php if (isset($message)) echo "<div style='color:green; margin-bottom:1rem;'>$message</div>"; ?>
    <form method="POST">
        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendanceList as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['name']); ?></td>
                    <td>
                        <label style="margin-right:1rem;">
                            <input type="radio" name="status[<?php echo $record['id']; ?>]" value="Present" <?php echo ($record['status'] == 'Present' || !$record['status']) ? 'checked' : ''; ?>> Present
                        </label>
                        <label style="margin-right:1rem;">
                            <input type="radio" name="status[<?php echo $record['id']; ?>]" value="Absent" <?php echo ($record['status'] == 'Absent') ? 'checked' : ''; ?>> Absent
                        </label>
                        <label>
                            <input type="radio" name="status[<?php echo $record['id']; ?>]" value="Late" <?php echo ($record['status'] == 'Late') ? 'checked' : ''; ?>> Late
                        </label>
                    </td>
                    <td>
                        <input type="text" name="remarks[<?php echo $record['id']; ?>]" value="<?php echo htmlspecialchars($record['remarks'] ?? ''); ?>" class="form-control" placeholder="Optional">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <button type="submit" name="mark_attendance" class="btn btn-primary">Save Attendance</button>
    </form>
<?php endif; ?>