<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Log;

class LdapHelper
{
    public static function callLdapMicroservice($username, $password)
    {
        $url = 'http://10.233.208.3:8000/api/v1/autenticaldap';
        $data = [
            "username" => $username,
            "password" => $password
        ];
        $payload = json_encode($data);

        Log::info('Chamada LDAP', ['payload' => $payload]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        Log::info('Resposta LDAP', ['response' => $response, 'httpcode' => $httpcode]);

        return ['response' => $response, 'status_code' => $httpcode];
    }
}
