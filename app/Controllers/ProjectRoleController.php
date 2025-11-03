<?php

namespace App\Controllers;

use App\Models\ProjectRoleModel;

class ProjectRoleController extends BaseController
{
    public function index()
    {
        $model = new ProjectRoleModel();
        $roles = $model->findAll();
        return view('roles/index', ['roles' => $roles]);
    }

    public function create()
    {
        return view('roles/form', ['role' => null]);
    }

    public function store()
    {
        $model = new ProjectRoleModel();
        $model->insert([
            'role_name' => $this->request->getPost('role_name'),
            'rate_per_day' => $this->request->getPost('rate_per_day')
        ]);

        return redirect()->to('/roles')->with('success', 'Role berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $model = new ProjectRoleModel();
        $role = $model->find($id);

        if (!$role) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Role tidak ditemukan");
        }

        return view('roles/form', ['role' => $role]);
    }

    public function update($id)
    {
        $model = new ProjectRoleModel();
        $model->update($id, [
            'role_name' => $this->request->getPost('role_name'),
            'rate_per_day' => $this->request->getPost('rate_per_day')
        ]);

        return redirect()->to('/roles')->with('success', 'Role berhasil diperbarui!');
    }

    public function delete($id)
    {
        $model = new ProjectRoleModel();
        $model->delete($id);

        return redirect()->to('/roles')->with('success', 'Role berhasil dihapus!');
    }
}
