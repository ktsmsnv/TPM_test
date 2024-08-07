<?php

use Illuminate\Support\Facades\Crypt;

return [

    'logging' => env('LDAP_LOGGING', true),

    'connections' => [

        'default' => [

            'auto_connect' => env('LDAP_AUTO_CONNECT', true),

            'connection' => Adldap\Connections\Ldap::class,

            'settings' => [

                'schema' => Adldap\Schemas\ActiveDirectory::class,

                'account_prefix' => env('LDAP_ACCOUNT_PREFIX', ''),

                'account_suffix' => env('LDAP_ACCOUNT_SUFFIX', '@kst-energo.ru'),

                'hosts' => explode(' ', env('LDAP_HOSTS', 'kstdc-03.kst-energo.ru kstdc-04.corp.kst-energo.ru')),

                'port' => env('LDAP_PORT', 389),

                'timeout' => env('LDAP_TIMEOUT', 5),

                'base_dn' => env('LDAP_BASE_DN', 'OU=ОП Уфа,OU=Users,OU=KST,dc=kst-energo,dc=ru'),

                // Расшифровка значений из .env
                'username' => Crypt::decryptString(env('LDAP_USERNAME_ENCRYPTED')),
                'password' => Crypt::decryptString(env('LDAP_PASSWORD_ENCRYPTED')),

//                'username' => env('LDAP_USERNAME', 'CN=tpm, OU=Системные УЗ, OU=USERS, OU=KST, DC=kst-energo, DC=ru'),
//                'password' => env('LDAP_PASSWORD', '0gWhhqLBy?xz'),

                'follow_referrals' => false,

                'use_ssl' => env('LDAP_USE_SSL', false),
                'use_tls' => env('LDAP_USE_TLS', false),

            ],

        ],

    ],

];
