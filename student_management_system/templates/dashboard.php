<?php
$stmt = $pdo->query("SELECT COUNT(*) FROM students");
$studentCount = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM courses");
$courseCount = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC LIMIT 5");
$recentStudents = $stmt->fetchAll();

$currentDay = date('l'); 
$currentTime = date('H:i:s');

$sql = "SELECT COUNT(*) FROM schedules 
        WHERE day_of_week = ? 
        AND start_time <= ? 
        AND end_time > ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$currentDay, $currentTime, $currentTime]);
$activeSessions = $stmt->fetchColumn();
?>

<div class="card-grid">
    <div class="stat-card">
        <div class="stat-title">Total Students</div>
        <div class="stat-value"><?php echo $studentCount; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Total Courses</div>
        <div class="stat-value"><?php echo $courseCount; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-title">Active Sessions</div>
        <div class="stat-value"><?php echo $activeSessions; ?></div>
        <div class="stat-title" style="font-size:0.7rem;">Classes in progress</div> 
    </div>
</div>

<h3 style="margin-top: 2rem; margin-bottom: 1rem;">Recent Students</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Joined</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($recentStudents as $student): ?>
        <tr>
            <td>#<?php echo $student['id']; ?></td>
            <td><?php echo htmlspecialchars($student['name']); ?></td>
            <td><?php echo htmlspecialchars($student['email']); ?></td>
            <td><?php echo date('M d, Y', strtotime($student['created_at'])); ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($recentStudents)): ?>
        <tr>
            <td colspan="4">No students found.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
