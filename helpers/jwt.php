<?php


class Jwt
{
    function encode($data)
    {
        $date = new DateTime();
        $curTimestamp = $date->getTimestamp(); // In second
        $data['exp'] = $curTimestamp + TOKEN_EXP;
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($data);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, SECRET, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        return $jwt;
    }

    function validate($token)
    {
        $tokenParts = explode('.', $token);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        $date = new DateTime();
        $curTimestamp = $date->getTimestamp(); // In second
        $expiration = json_decode($payload)->exp;
        $tokenExpired = $curTimestamp > $expiration;

        if ($tokenExpired) {
            return false;
        }
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, SECRET, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $signatureValid = ($base64UrlSignature === $signatureProvided);
        if ($signatureValid) {
            return json_decode($payload);
        } else {
            return false;
        }
    }
}