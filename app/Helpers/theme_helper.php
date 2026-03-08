<?php

use App\Models\SettingThemeModel;

if (!function_exists('get_theme_setting')) {
    function get_theme_setting() {
        $model = new SettingThemeModel();
        $setting = $model->first();
        if (!$setting) {
            return [
                'login_bg_type' => 'color',
                'login_bg_value' => 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
                'sidebar_bg_type' => 'color',
                'sidebar_bg_value' => '#ffffff'
            ];
        }
        return $setting;
    }
}