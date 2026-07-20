<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleController extends Controller
{
    public function allPermission(Request $request)
    {
        $permissions = Permission::orderBy('group_name')->get();
        return view('admin.pages.permission.all-permission',compact('permissions'));
    }

    public function storePermission(Request $request)
    {
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->group_name = $request->group_name;

        $permission->save();

        $notification = array(
        'message' => 'Permission Succesfully Added',
        'alert-type' =>'success'
        );

        return redirect()->route('all.permission')->with($notification);

    }
     public function updatePermission(Request $request,$id)
        {
           $permission = Permission::findOrFail($id);
           $permission->name=$request->name;
           $permission->group_name=$request->group_name;

           $permission->update();

            $notification = array(
                'message' => 'Permission Succesfully Updated',
                'alert-type' =>'success'
            );
            return redirect()->route('all.permission')->with($notification);
        }

        public function deletePermission($id)
        {
            Permission::findOrFail($id)->delete();
                $notification = array(
                    'message' => 'Permission Succesfully Deleted',
                    'alert-type' =>'success'
                );

            return redirect()->route('all.permission')->with($notification);
        }

        public function allRoles(Request $request)
        {
            $roles = Role::all();
            return view('admin.pages.roles.all-roles',compact('roles'));
        }

        public function storeRoles(Request $request)
        {
            $roles = new Role();
            $roles->name = $request->name;

            $roles->save();

            $notification = array(
            'message' => 'Role Succesfully Added',
            'alert-type' =>'success'
            );

            return redirect()->route('all.roles')->with($notification);

        }

        public function updateRole(Request $request,$id)
        {
           $roles = Role::findOrFail($id);
           $roles->name=$request->name;

           $roles->update();

            $notification = array(
                'message' => 'Role Succesfully Updated',
                'alert-type' =>'success'
            );
            return redirect()->route('all.roles')->with($notification);
        }


        public function deleteRole($id)
        {
            Role::findOrFail($id)->delete();
                $notification = array(
                    'message' => 'Role Succesfully Deleted',
                    'alert-type' =>'success'
                );

            return redirect()->route('all.roles')->with($notification);
        }

        public function AddRolePermission()
        {
            $roles= Role::all();
            $permissions = Permission::all();
            $permission_groups = User::getpermissionGroups();

            return view('admin.pages.rolesetup.add-roles-permission',compact('roles','permissions','permission_groups'));

        }

        public function storeRolePermission(Request $request)
        {
            $request->validate([
                'role_id' => 'required',
                'permissions' => 'required|array',
            ]);

            $data = [];

            foreach ($request->permissions as $permission_id) {

                $data[] = [
                    'role_id' => $request->role_id,
                    'permission_id' => $permission_id,
                ];

            }

            DB::table('role_has_permissions')->insert($data);


            $notification = [
                'message' => 'Role Permission Successfully Added',
                'alert-type' => 'success'
            ];

            return redirect()->route('all.roles.permission')->with($notification);
        }

        public function allRolesPermission()
        {
            $roles = Role::all();
            return view('admin.pages.rolesetup.all-roles-permissions',compact('roles'));
        }

        public function editRolePermission($id)
        {
            $role = Role::with('permissions')->findOrFail($id);
            $permissions = Permission::all();
            $permission_groups = User::getpermissionGroups();

            $rolePermissionIds = $role->permissions->pluck('id')->toArray();

            return view('admin.pages.rolesetup.edit-roles-permission',
                compact('role', 'permissions', 'permission_groups', 'rolePermissionIds')
            );
        }

        public function updateRolePermission(Request $request, $id)
        {
            $request->validate([
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,id',
            ]);

            $role = Role::findOrFail($id);

            $role->permissions()->sync($request->permissions ?? []);

            return redirect()->route('all.roles.permission')->with('success', 'Role permissions updated successfully.');
        }

        public function deleteRolePermission($id)
        {
            $role = Role::findOrFail($id);
            $role->delete();

            return redirect()->route('all.roles.permission')->with('success', 'Role deleted successfully.');
        }

        public function allAdmin()
        {
            $allAdmins = User::latest()->get();
            $roles = Role::all();
            return view('admin.pages.admin.all-admin', compact('allAdmins','roles'));
        }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id); // 1️⃣ unahin ito

        $admin = User::create([                        // 2️⃣ tapos gamitin dito
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $role->name,
        ]);

        $admin->assignRole($role);                      // 3️⃣ pati dito

        return redirect()->back()->with([
            'message' => 'Admin Successfully Added',
            'alert-type' => 'success'
        ]);
    }

    public function updateAdmin(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|min:6|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->role = $role->name;

        if ($request->filled('password')) {
            $admin->password = bcrypt($request->password);
        }

        $admin->save();

        $admin->syncRoles($role);
        return redirect()->back()->with([
            'message' => 'Admin Successfully Updated',
            'alert-type' => 'success'
        ]);
    }
}
