<?php
// models/SettingsModel.php
require_once __DIR__ . '/../config/db.php';

class SettingsModel {
    public static function getAll() {
        return db_fetch_all("SELECT * FROM platform_settings");
    }

    public static function update($key, $value) {
        return db_query("UPDATE platform_settings SET setting_value = ? WHERE setting_key = ?", [$value, $key], "ss");
    }

    public static function getValue($key) {
        $res = db_fetch_one("SELECT setting_value FROM platform_settings WHERE setting_key = ?", [$key]);
        return $res ? $res['setting_value'] : null;
    }
}
?>
