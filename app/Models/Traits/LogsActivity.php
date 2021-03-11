<?php


namespace App\Models\Traits;

use App\Helpers\General\AuthUser;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity as BaseLogsActivity;

trait LogsActivity
{
    use BaseLogsActivity;

    public function tapActivity(Activity $activity, string $eventName)
    {
        $data = [
            'log_name' => $activity->log_name,
            'event_name' => $eventName,
            'username' => empty($activity->causer->username) ? NULL : $activity->causer->username
        ];

        $activity->email = empty($activity->causer->email) ? NULL : $activity->causer->email;
        $activity->employee_code = empty($activity->causer->employee_code) ? NULL : $activity->causer->employee_code;

        $activity->message = $this->describeLog($data);
        $activity->properties = $activity->properties->toArray();

        $deptID = $activity->subject->dept_id;
        $activity->client_id = !empty(AuthUser::$data['client_id']) ? AuthUser::$data['client_id'] : NULL;
        $activity->dept_id = !empty($deptID) ? $deptID : NULL;

        $activity->url = !empty(request()->url()) ? request()->url() : NULL;
        $activity->ip_address = !empty(request()->ip()) ? request()->ip() : NULL;
        $activity->user_agent = !empty(request()->userAgent()) ? request()->userAgent() : NULL;
    }

    public function describeLog($data)
    {
        $log = $data['log_name'] . " " .$data['event_name'] . " by username " . $data['username'];
        $log .= " on " . date('d-m-Y') . " at " . date('H:i:s');
        return $log;
    }

}
