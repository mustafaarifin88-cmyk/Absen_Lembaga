<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingThemeModel;

class SettingTheme extends BaseController
{
    protected $themeModel;

    public function __construct()
    {
        $this->themeModel = new SettingThemeModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Pengaturan Tema',
            'theme' => $this->themeModel->first()
        ];
        return view('admin/setting_theme/index', $data);
    }

    public function update()
    {
        $dbData = $this->themeModel->first();
        $id = $dbData['id'] ?? 1;

        $loginType = $this->request->getPost('login_bg_type');
        $sidebarType = $this->request->getPost('sidebar_bg_type');

        $dataToUpdate = [
            'login_bg_type' => $loginType,
            'sidebar_bg_type' => $sidebarType
        ];

        if ($loginType == 'color') {
            $dataToUpdate['login_bg_value'] = $this->request->getPost('login_bg_color');
        } else {
            $file = $this->request->getFile('login_bg_image');
            if ($file && $file->isValid()) {
                $name = $file->getRandomName();
                $file->move('uploads/theme', $name);
                $dataToUpdate['login_bg_value'] = $name;
            }
        }

        if ($sidebarType == 'color') {
            $dataToUpdate['sidebar_bg_value'] = $this->request->getPost('sidebar_bg_color');
        } else {
            $file = $this->request->getFile('sidebar_bg_image');
            if ($file && $file->isValid()) {
                $name = $file->getRandomName();
                $file->move('uploads/theme', $name);
                $dataToUpdate['sidebar_bg_value'] = $name;
            }
        }

        if (!$dbData) {
            $this->themeModel->insert($dataToUpdate);
        } else {
            $this->themeModel->update($id, $dataToUpdate);
        }

        return redirect()->to('/admin/setting-theme')->with('success', 'Tema berhasil diperbarui.');
    }
}