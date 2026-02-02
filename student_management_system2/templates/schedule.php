<?php
require_once __DIR__ . '/../src/Schedule.php';
require_once __DIR__ . '/../src/Grade.php';

$scheduleModel = new Schedule($pdo);
$gradeModel = new Grade($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_schedule'])) {
        $scheduleModel->addSchedule($_POST['course_id'], $_POST['day'], $_POST['start_time'], $_POST['end_time'], $_POST['room']);
    } elseif (isset($_POST['edit_schedule'])) {
        $scheduleModel->updateSchedule($_POST['id'], $_POST['course_id'], $_POST['day'], $_POST['start_time'], $_POST['end_time'], $_POST['room']);
    } elseif (isset($_POST['delete_schedule'])) {
        $scheduleModel->deleteSchedule($_POST['id']);
    }
}

$schedules = $scheduleModel->getAll();
$courses = $gradeModel->getCourses();
?>

<div class="header">
    <button onclick="openModal('addScheduleModal')" class="btn btn-primary" style="width: auto;">+ Add Class</button>
</div>

<table>
    <thead>
        <tr>
            <th>Day</th>
            <th>Time</th>
            <th>Course</th>
            <th>Room</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($schedules as $item): ?>
        <tr>
            <td><span class="badge"><?php echo htmlspecialchars($item['day_of_week']); ?></span></td>
            <td><?php echo date('h:i A', strtotime($item['start_time'])) . ' - ' . date('h:i A', strtotime($item['end_time'])); ?></td>
            <td><?php echo htmlspecialchars($item['code'] . ' - ' . $item['course_name']); ?></td>
            <td><?php echo htmlspecialchars($item['room']); ?></td>
            <td>
                <button onclick='openEditModal(<?php echo json_encode($item); ?>)' class="btn" style="padding:0.25rem 0.5rem; font-size:0.85rem; background:#fbbf24; color:#92400e;">Edit</button>
                
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this schedule?');">
                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                    <button type="submit" name="delete_schedule" class="btn" style="padding:0.25rem 0.5rem; font-size:0.85rem; background:#fecaca; color:#b91c1c;">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div id="addScheduleModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:1rem; width:400px; margin: 10% auto; position: relative;">
        <h3 style="margin-bottom:1rem;">Add Class Schedule</h3>
        <span onclick="closeModal('addScheduleModal')" style="position:absolute; top:1rem; right:1.5rem; cursor:pointer; font-size:1.5rem;">&times;</span>
        <form method="POST">
            <div class="form-group">
                <label>Course</label>
                <select name="course_id" class="form-control" required>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Day</label>
                <select name="day" class="form-control" required>
                    <option>Monday</option>
                    <option>Tuesday</option>
                    <option>Wednesday</option>
                    <option>Thursday</option>
                    <option>Friday</option>
                    <option>Saturday</option>
                    <option>Sunday</option>
                </select>
            </div>
            <div style="display:flex; gap:1rem;">
                <div class="form-group" style="flex:1;">
                    <label>Start Time</label>
                    <input type="time" name="start_time" class="form-control" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>End Time</label>
                    <input type="time" name="end_time" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label>Room</label>
                <input type="text" name="room" class="form-control" placeholder="Room 101" required>
            </div>
            <button type="submit" name="add_schedule" class="btn btn-primary">Save Schedule</button>
        </form>
    </div>
</div>

<div id="editScheduleModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:1rem; width:400px; margin: 10% auto; position: relative;">
        <h3 style="margin-bottom:1rem;">Edit Class Schedule</h3>
        <span onclick="closeModal('editScheduleModal')" style="position:absolute; top:1rem; right:1.5rem; cursor:pointer; font-size:1.5rem;">&times;</span>
        <form method="POST">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Course</label>
                <select name="course_id" id="edit_course_id" class="form-control" required>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Day</label>
                <select name="day" id="edit_day" class="form-control" required>
                    <option>Monday</option>
                    <option>Tuesday</option>
                    <option>Wednesday</option>
                    <option>Thursday</option>
                    <option>Friday</option>
                    <option>Saturday</option>
                    <option>Sunday</option>
                </select>
            </div>
            <div style="display:flex; gap:1rem;">
                <div class="form-group" style="flex:1;">
                    <label>Start Time</label>
                    <input type="time" name="start_time" id="edit_start_time" class="form-control" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>End Time</label>
                    <input type="time" name="end_time" id="edit_end_time" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label>Room</label>
                <input type="text" name="room" id="edit_room" class="form-control" required>
            </div>
            <button type="submit" name="edit_schedule" class="btn btn-primary">Update Schedule</button>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function openEditModal(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_course_id').value = data.course_id;
    document.getElementById('edit_day').value = data.day_of_week;
    document.getElementById('edit_start_time').value = data.start_time;
    document.getElementById('edit_end_time').value = data.end_time;
    document.getElementById('edit_room').value = data.room;
    
    openModal('editScheduleModal');
}

window.onclick = function(event) {
    if (event.target.className === 'modal') {
        event.target.style.display = "none";
    }
}
</script>
