<?php

namespace App\Helpers\General;

class StoragePath
{
    private const  ROOT_RECORDING_STORAGE_PATH = 'recordings';

    public static function getClientRecordingPath($clientId)
    {
        if (!is_null($clientId)) {
            return self::ROOT_RECORDING_STORAGE_PATH . '/' . $clientId;
        }
        return self::ROOT_RECORDING_STORAGE_PATH;

    }

}
