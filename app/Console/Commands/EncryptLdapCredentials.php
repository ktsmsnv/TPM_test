<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

class EncryptLdapCredentials extends Command
{
    protected $signature = 'ldap:encrypt';
    protected $description = 'Encrypt LDAP credentials';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $key = config('app.key'); // Используйте ключ из конфигурации Laravel
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));

        $ldap_username = 'CN=tpm, OU=Системные УЗ, OU=USERS, OU=KST, DC=kst-energo, DC=ru';
        $ldap_password = '0gWhhqLBy?xz';

        // Шифрование
        $encrypted_username = Crypt::encryptString($ldap_username);
        $encrypted_password = Crypt::encryptString($ldap_password);

        $this->info("Encrypted Username: " . $encrypted_username);
        $this->info("Encrypted Password: " . $encrypted_password);
    }
}
