<?php
require_once __DIR__ . '/../src/Student.php';
$studentModel = new Student($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_student'])) {
        $studentModel->create($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['dob'], $_POST['address']);
        header("Location: index.php?page=students");
        exit;
    } elseif (isset($_POST['delete_student'])) {
        $studentModel->delete($_POST['student_id']);
        header("Location: index.php?page=students");
        exit;
    }
}

$students = $studentModel->getAll();
?>

<div class="header">
    <button onclick="document.getElementById('addStudentModal').style.display='block'" class="btn btn-primary" style="width: auto;">+ Add Student</button>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $student): ?>
        <tr>
            <td>#<?php echo $student['id']; ?></td>
            <td><a href="index.php?page=student_profile&id=<?php echo $student['id']; ?>" style="color:var(--primary-color); font-weight:500;"><?php echo htmlspecialchars($student['name']); ?></a></td>
            <td><?php echo htmlspecialchars($student['email']); ?></td>
            <td><?php echo htmlspecialchars($student['phone']); ?></td>
            <td>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                    <button type="submit" name="delete_student" style="background:none; border:none; color: red; cursor:pointer;">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div id="addStudentModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:1rem; width:400px; margin: 10% auto; position: relative;">
        <h3 style="margin-bottom:1rem;">Add New Student</h3>
        <span onclick="document.getElementById('addStudentModal').style.display='none'" style="position:absolute; top:1rem; right:1.5rem; cursor:pointer; font-size:1.5rem;">&times;</span>
        
        <form method="POST">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" class="form-control">
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control"></textarea>
            </div>
            <button type="submit" name="add_student" class="btn btn-primary">Save Student</button>
        </form>
    </div>
</div>
