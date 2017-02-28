<?php

namespace Authentication;

class AuthenticationConfig
{



    /**
     * array of <credential> => <profileName>
     *
     * - credential: <pseudo>:<password>
     *
     *
     */
    public static function getCredentials()
    {
        return [
            'root:root' => 'root',
            'admin:admin' => 'admin',
        ];
    }
}