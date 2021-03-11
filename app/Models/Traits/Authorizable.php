<?php

namespace App\Models\Traits;

use App\Exceptions\ApiErrorResponse;
use App\Helpers\General\AuthUser;
use App\Helpers\General\Tables;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\Authorizable as BaseAuthorizable;

trait Authorizable
{

    use BaseAuthorizable, ApiErrorResponse;

    public function canDepartment($permission, $resource)
    {
        try {
            $permission = Permission::findByName($permission);
            if(!$permission->is_dept_dependent){
                throw new \Exception(__('exceptions.admin.misc.invalid_permission_type'));
            }
            if (empty($resource)) {
                return false;
            }
            $userID = $this->id;
            if (!empty($resource->client_id)) {//no client_id in case of store methods
                if (AuthUser::$data['client_id'] != $resource->client_id) {
                    return false;
                }
            }
            if ($this->can('manage-clients')) return true;

            $userRoles = DB::table(Tables::TABLE_NAMES['user_access'])
                ->where([
                    ['user_id', $userID],
                    ['dept_id', $resource->dept_id],
                    ['client_id', $this->client_id]
                ])->pluck('role_id')->toArray();

            foreach ($userRoles as $userRole) {
                $role = Role::findById($userRole);
                if ($role->hasPermissionTo($permission)) {
                    return true;
                }
            }
            return false;
        } catch (\Exception $exception) {
            $data = [
                'log_name' => 'Authorization',
                'subject' => $this,
                'properties' => $permission,
                'exception' => $exception
            ];
            logError($data);
            return false;
        }

    }

    public function cantDepartment($permission, $deptID)
    {
        return !$this->canDepartment($permission, $deptID);
    }

    public function cannotDepartment($permission, $deptID)
    {
        return $this->cantDepartment($permission, $deptID);
    }

    public function can($ability, $resource = [])
    {
        try {
            if (is_null($resource)) {
                return false;
            }
            $authData = AuthUser::$data;
//            if (!empty($resource->client_id) && !$resource instanceof User) {//ignore store case

            if (!empty($resource->client_id)) {//ignore store case
                $clientID = ($this->type_id == config('access.users.types.super_admin') && $authData['switched_client_id'] != 0) ? $authData['switched_client_id'] : $this->client_id;
                if(isset($resource->accessibleClientIds)){
                    if(!in_array($resource->client_id, $resource->accessibleClientIds)){
                        return false;
                    }
                }
                else {
                    if ($clientID != $resource->client_id) {
                        return false;
                    }
                }
            }
            if (parent::can('manage-clients')) return true;
            return parent::can($ability, []);
        } catch (\Exception $exception) {
            $data = [
                'log_name' => 'Authorization',
                'subject' => $this,
                'properties' => $ability,
                'exception' => $exception
            ];
            logError($data);
            return false;
        }
    }


}
