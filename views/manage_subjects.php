<?php
// views/manage_subjects.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/SubjectModel.php';

$subjects = SubjectModel::getAll();
?>

<div class="table-container" style="margin-bottom: 30px;">
    <h2>Add New Subject</h2>
    <form action="../controllers/subject_controller.php" method="POST" style="display: flex; gap: 10px; align-items: end;">
        <input type="hidden" name="action" value="create">
        <div style="flex: 1;">
            <label>Subject Name</label><br>
            <input type="text" name="name" required style="width: 100%; padding: 8px;">
        </div>
        <div style="flex: 2;">
            <label>Description</label><br>
            <input type="text" name="description" style="width: 100%; padding: 8px;">
        </div>
        <button type="submit" class="btn btn-approve">Add Subject</button>
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
                <td>
                    <a href="../controllers/subject_controller.php?action=delete&id=<?php echo $subject['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
