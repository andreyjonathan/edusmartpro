<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Roles/Index', [
            'roles' => \Spatie\Permission\Models\Role::with('permissions')->get(),
            'permissions' => \Spatie\Permission\Models\Permission::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = \Spatie\Permission\Models\Role::create(['name' => $validated['name']]);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->back()->with('message', 'Role created successfully.');
    }

    public function update(Request $request, \Spatie\Permission\Models\Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->back()->with('message', 'Role updated successfully.');
    }

    public function destroy(\Spatie\Permission\Models\Role $role)
    {
        $protectedRoles = ['admin', 'teacher', 'student', 'parent'];
        
        if (in_array($role->name, $protectedRoles)) {
            return redirect()->back()->with('error', 'Cannot delete system-protected roles.');
        }

        $role->delete();

        return redirect()->back()->with('message', 'Role deleted successfully.');
    }
}
