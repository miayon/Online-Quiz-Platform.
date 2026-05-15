<?php
// views/platform_settings.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/SettingsModel.php';

$settings = SettingsModel::getAll();
?>

<div class="table-container" style="max-width: 800px;">
    <h2>Platform-wide Policies & Settings</h2>
    <p style="color: #666; font-size: 14px; margin-bottom: 25px;">Configure global parameters that affect all users and courses across the institution.</p>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            Settings updated successfully!
        </div>
    <?php endif; ?>

    <form action="../controllers/settings_controller.php" method="POST">
        <input type="hidden" name="action" value="update_settings">
        
        <table style="width: 100%; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="width: 40%;">Policy Setting</th>
                    <th>Configurable Value</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($settings as $s): ?>
                <tr>
                    <td>
                        <strong><?php echo ucwords(str_replace('_', ' ', $s['setting_key'])); ?></strong>
                        <br><small style="color: #888;">Global policy key: <?php echo $s['setting_key']; ?></small>
                    </td>
                    <td>
                        <input type="text" name="settings[<?php echo $s['setting_key']; ?>]" value="<?php echo htmlspecialchars($s['setting_value']); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="text-align: right;">
            <button type="submit" class="btn btn-approve" style="padding: 12px 30px; font-size: 16px;">Save All Policies</button>
        </div>
    </form>
</div>

<div class="table-container" style="max-width: 800px; margin-top: 30px; border-left: 5px solid var(--accent);">
    <h3>Institutional Note</h3>
    <p style="font-size: 14px; line-height: 1.6; color: #444;">
        These settings are applied globally. For example, changing the <strong>Max Quiz Duration</strong> will enforce a ceiling on all new quizzes created by instructors. Modifying <strong>Allow Instructor Registration</strong> will enable or disable the public sign-up form for new teaching staff.
    </p>
</div>

</body>
</html>
