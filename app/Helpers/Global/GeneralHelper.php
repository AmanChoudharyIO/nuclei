<?php

use App\Helpers\General\AuthUser;
use App\Models\ErrorActivity;
use App\ActivityLog\ActivityLogger;
use Spatie\Activitylog\ActivityLogStatus;

if (!function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (!function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (!function_exists('home_route')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {
        if (auth()->check()) {
            if (auth()->user()->can('view backend')) {
                return 'admin.dashboard';
            }

            return 'frontend.user.dashboard';
        }

        return 'frontend.index';
    }
}

if (!function_exists('logError')) {
    function logError($data)
    {
        try {
            $data['url'] = !empty(request()->url()) ? request()->url() : NULL;
            $data['ip'] = !empty(request()->ip()) ? request()->ip() : NULL;
            $data['user_agent'] = !empty(request()->userAgent()) ? request()->userAgent() : NULL;

            $errorActivity = errorActivity($data['log_name']);
            if (isset($data['subject'])) {
                $errorActivity->performedOn($data['subject']);
            }
            return $errorActivity
                ->tap(function (ErrorActivity $activity) use ($data) {
                    $properties = $data['properties'];
                    $activity->properties = isset($properties) ? array('attributes' => $properties) : NULL;
                    $activity->client_id = isset(AuthUser::$data['client_id']) ? AuthUser::$data['client_id'] : NULL;
                    $activity->dept_id = isset($properties['dept_id']) ? $properties['dept_id'] : NULL;

                    $activity->url = isset($data['url']) ? $data['url'] : NULL;
                    $activity->ip_address = isset($data['ip']) ? $data['ip'] : NULL;
                    $activity->user_agent = isset($data['user_agent']) ? $data['user_agent'] : NULL;

                    $activity->message = isset($data['exception']) ? makePrettyException($data['exception']) : NULL;
                })
                ->log('error');
        } catch (Exception $exception) {
            $logException = $data['exception'] ?? $exception;
            \Log::info($logException);
        }
    }
}

if (!function_exists('makePrettyException')) {
    function makePrettyException(\Exception $exception)
    {
        $trace = $exception->getTrace();

        $result = 'Exception: "';
        $result .= $exception->getMessage();
        $result .= '" @ ';

        if (!empty($exception->info)) {

            $errorInfo = $exception->info;

            if (!empty($errorInfo['trait'])) {
                $result .= 'trait : ';
                $result .= $errorInfo['trait'];
                $result .= '->';
            }

            if (!empty($errorInfo['method'])) {
                $result .= $errorInfo['method'];
                $result .= '(); on ';
            } else {
                $result .= !empty($errorInfo['trait']) ? $errorInfo['trait'] : $errorInfo['class'];
                $result .= '::';
                $result .= $errorInfo['function'];
                $result .= '(); on ';
            }

            if (!empty($errorInfo['file'])) {
                $result .= $errorInfo['file'];
                $result .= '';
            }

        } else {
            if (!empty($trace[0]['class'])) {
                $result .= $trace[0]['class'];
                $result .= '->';
            }

            if (!empty($trace[0]['function'])) {
                $result .= $trace[0]['function'];
                $result .= '(); on ';
            }

            $result .= $trace[0]['file'];
            $result .= ':';

            $result .= $trace[0]['line'];
            $result .= '.';
        }

        return $result;
    }
}

if (!function_exists('createExceptionsString')) {
    function createExceptionsString($model, $errorType)
    {
        $classNameArray = explode('\\', $model); //convert to array
        $splicedClassNameArray = array_splice($classNameArray, 2); //remove 'App/Models'
        $splicedClassNameArray = array_map('strtosnake', $splicedClassNameArray); //convert all elements to snake case
        array_unshift($splicedClassNameArray, 'exceptions');//add prefix 'exceptions'
        array_push($splicedClassNameArray, $errorType);//add suffix error type
        $exceptionString = implode('.', $splicedClassNameArray);//array to string
        $exceptionString = str_replace('models', 'admin', $exceptionString); //replace models with admin ..in case of permission model
        return __($exceptionString);
    }
}

if (!function_exists('strtoSnake')) {
    //refer: https://stackoverflow.com/questions/1993721/how-to-convert-pascalcase-to-pascal-case/35719689#35719689
    function strtosnake($string)
    {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
    }
}

if (!function_exists('createWhereFilters')) {
    function createWhereFilters($filters)
    {
        $wheres = array();
        foreach ($filters as $filter) {
            if (count($filter) == 2 || count($filter) == 3) {
                $column = $filter[0];
                if (count($filter) == 2) {
                    $value = $filter[1];
                    $operator = '=';
                } else {
                    $operator = $filter[1];
                    $value = $filter[2];
                }
                array_push($wheres, compact('column', 'value', 'operator'));
            } else return false;
        }
        return $wheres;
    }
}

if (!function_exists('errorActivity')) {
    function errorActivity(string $logName = null): ActivityLogger
    {
        $defaultLogName = config('activitylog.default_log_name');

        $logStatus = app(ActivityLogStatus::class);

        return app(ActivityLogger::class)
            ->useLog($logName ?? $defaultLogName)
            ->setLogStatus($logStatus);
    }
}
if (!function_exists('generateUniqueID')) {
    function generateUniqueID($length)
    {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }
}

