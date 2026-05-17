<?php
// views/manage_subjects.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/SubjectModel.php';

$subjects = SubjectModel::getAll();
?>

<div class="table-container" style="margin-bottom: 30px;">
    <h2>Add New Subject</h2>
    <form action="../controllers/subject_controller.php" method="POST" class="flex-form">
        <input type="hidden" name="action" value="create">
        <div style="flex: 1;">
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Subject Name</label>
            <input type="text" name="name" class="form-control" required placeholder="e.g. Computer Science">
        </div>
        <div style="flex: 2;">
            <label style="font-weight: 500; margin-bottom: 5px; display: block;">Description</label>
            <input type="text" name="description" class="form-control" placeholder="Brief overview of the subject">
        </div>
        <div style="flex: 0 0 auto;">
            <button type="submit" class="btn btn-approve" style="width: 150px; padding: 10px;">Add Subject</button>
        </div>
    </form>
</div>

<div class="table-container">
    <h2>Academic Subjects</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subjects as $subject): ?>
            <tr>
                <td><?php echo $subject['id']; ?></td>
                <td><?php echo htmlspecialchars($subject['name']); ?></td>
                <td><?php echo htmlspecialchars($subject['description']); ?></td>
                <td class="actions-cell">
                    <button class="btn btn-edit" style="background: var(--accent); color: white; padding: 6px 12px; border-radius: 4px; border: none; font-weight: 500; font-size: 13px; cursor: pointer;" onclick="openEditModal(<?php echo $subject['id']; ?>, '<?php echo htmlspecialchars($subject['name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($subject['description'], ENT_QUOTES); ?>')">Edit</button>
                    <a href="../controllers/subject_controller.php?action=delete&id=<?php echo $subject['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Edit Subject Modal -->
<div id="editSubjectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="table-container" style="background: white; width: 500px; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); border: 1px solid #ddd; margin: auto;">
        <h2 style="margin-top: 0; margin-bottom: 20px; font-size: 20px; font-weight: 600;">Rename / Edit Subject</h2>
        <form action="../controllers/subject_controller.php" method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_subject_id">
            
            <div style="margin-bottom: 15px;">
                <label style="font-weight: 500; margin-bottom: 5px; display: block; text-align: left;">Subject Name</label>
                <input type="text" name="name" id="edit_subject_name" class="form-control" required style="width: 100%; display: block; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 25px;">
                <label style="font-weight: 500; margin-bottom: 5px; display: block; text-align: left;">Description</label>
                <input type="text" name="description" id="edit_subject_description" class="form-control" style="width: 100%; display: block; box-sizing: border-box;">
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-reject" style="background: #78909c; color: white; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer;" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-approve" style="background: #2e7d32; color: white; padding: 8px 16px; border-radius: 6px; border: none; cursor: pointer;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, description) {
    document.getElementById('edit_subject_id').value = id;
    document.getElementById('edit_subject_name').value = name;
    document.getElementById('edit_subject_description').value = description;
    
    let modal = document.getElementById('editSubjectModal');
    modal.style.display = 'flex';
}

function closeEditModal() {
    let modal = document.getElementById('editSubjectModal');
    modal.style.display = 'none';
}
</script>

</body>
</html>
