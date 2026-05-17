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
                    <a href="../controllers/subject_controller.php?action=delete&id=<?php echo $subject['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
