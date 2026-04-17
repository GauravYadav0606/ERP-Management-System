<?php
require_once __DIR__ . '/../src/Student.php';
$studentModel = new Student($pdo);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_student'])) {
        $studentModel->create($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['dob'], $_POST['address']);
        header("Location: index.php?page=students");
        exit;
    }
    elseif (isset($_POST['delete_student'])) {
        $studentModel->delete($_POST['student_id']);
        header("Location: index.php?page=students");
        exit;
    }
    elseif (isset($_POST['edit_student'])) {
        $studentModel->update($_POST['edit_student_id'], $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['dob'], $_POST['address']);
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
            <td style="display:flex; gap:0.5rem;">
                <button 
                    onclick="openEditStudentModal(
                        <?php echo $student['id']; ?>,
                        '<?php echo addslashes($student['name']); ?>',
                        '<?php echo addslashes($student['email']); ?>',
                        '<?php echo addslashes($student['phone']); ?>',
                        '<?php echo addslashes($student['dob']); ?>',
                        '<?php echo addslashes($student['address']); ?>'
                    )"
                    style="background-color:#3b82f6; color:white; border:none; padding:0.3rem 0.7rem; border-radius:0.3rem; cursor:pointer;">
                    Edit
                </button>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                    <button type="submit" name="delete_student" class="btn" style=" background:#fecaca; color:#b91c1c;">Delete</button>
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
<div id="editStudentModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:1rem; width:400px; margin: 10% auto; position: relative;">
        <h3 style="margin-bottom:1rem;">Edit Student</h3>
        <span onclick="document.getElementById('editStudentModal').style.display='none'" style="position:absolute; top:1rem; right:1.5rem; cursor:pointer; font-size:1.5rem;">&times;</span>        
        <form method="POST">
            <input type="hidden" name="edit_student_id" id="edit_student_id">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="edit_student_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="edit_student_email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" id="edit_student_phone" class="form-control">
            </div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" id="edit_student_dob" class="form-control">
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" id="edit_student_address" class="form-control"></textarea>
            </div>
            <button type="submit" name="edit_student" class="btn btn-primary">Update Student</button>
        </form>
    </div>
</div>
<script>
function openEditStudentModal(id, name, email, phone, dob, address) {
    document.getElementById('edit_student_id').value = id;
    document.getElementById('edit_student_name').value = name;
    document.getElementById('edit_student_email').value = email;
    document.getElementById('edit_student_phone').value = phone;
    document.getElementById('edit_student_dob').value = dob;
    document.getElementById('edit_student_address').value = address;
    document.getElementById('editStudentModal').style.display = 'block';
}
</script>