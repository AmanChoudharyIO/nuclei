<?php


namespace App\Helpers\General;


class Columns
{
    const BREAK_LIST = [
      'PAUSE_CODE' => 'pause_code',
      'DESCRIPTION' => 'description',
      'BREAK_TIME' => 'break_time',
      'REPETITION' => 'repetition'
    ];

    const PAUSE_CODE = [
      'LIST_ID' => 'list_id'
    ];

    const AREA_WISE_CALLER_ID_LIST = [
        'AREA_CODE_PREFIX' => 'area_code_prefix',
        'CALLER_ID' => 'caller_id',

    ];

    const AREA_WISE_CALLER_ID = [
        'LIST_ID' => 'list_id'
    ];

    const DO_NOT_CALL_LIST = [
        'NUMBER' => 'number',
        'TYPE' => 'type',
    ];

    const DO_NOT_CALL_NUMBER = [
        'LIST_ID' => 'list_id'
    ];
}