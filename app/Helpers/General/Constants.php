<?php

namespace App\Helpers\General;

class Constants
{

    const ALL_TIMESTAMPS = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    const MODEL_BINDED_METHODS = [
        'GET',
        'PUT',
        'PATCH',
        'DELETE'
    ];

    const EMAIL_REGEX = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

    const DOMAIN_REGEX = '/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/';

    const PASSWORD_REGEX = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

    const PHONE_NO_REGEX = '/^\+?[0-9]{5,15}$/';

    const TIME = '/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/';// regex for checking time in H:i format.

    const CALLER_ID_REGEX = '/^[0-9]{5,15}$/';

    const DNC_NO_REGEX = '/^\+?[0-9]{1,15}$/';

    const DO_NOT_CALL_LIST = [
        'HEADER_DATA' => array('number', 'type')
    ];

    const BREAK_LIST = [
        'DOUBLE_DASH' => '--',
        'NO_RESTRICTIONS' => 'No Restrictions',
        'HEADER_DATA' => array('Pause Code', 'Description', 'Maximum Time for Break', 'Repetition in Same Shift'),
        'DOWNLOAD_FILENAME' => 'pause_codes'
    ];

    const PREFIX_REGEX = '/^\+?[A-Za-z0-9]{1,15}$/';

    const AREA_WISE_CALLER_ID_LIST = [
      'HEADER_DATA' => array('Area Code Prefix', 'Caller ID')
    ];
}