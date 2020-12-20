<?php

class Validator {
    function validateEmail($email) {
        if (!$email || empty($email)) {
            return [
                "error" => "Email is required."
            ];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                "error" => "Email is invalid."
            ];
        }
        return [];
    }

    function validateUsername($username) {
        if (!$username || empty($username)) {
            return [
                "error" => "Username is required."
            ];
        }
        if(preg_match('/^\w{6,}$/', $username)) { // \w equals "[0-9A-Za-z_]"
            // valid username, alphanumeric & longer than or equals 6 chars
            return [
                "error" => "Username is invalid. Userbane only contain alphanumeric & longer than or equals 6 chars"
            ];
        }
        return [];
    }

    function validatePassword($password) {
        $passwordErr = null;

        if (empty($password)) {
            $passwordErr = "Password is required";
        } elseif (!is_string($password)) {
            $passwordErr = "Password must be a String";
        } else {
//            var_dump(strlen($password));die;
            if (strlen($password) < 8
                || !preg_match("#[0-9]+#",$password)
                || !preg_match("#[A-Z]+#",$password)
                || !preg_match("#[a-z]+#",$password)
            ) {
                $passwordErr = "Your password must contain at least 8 characters, at least 1 number, at least 1 capital letter, 1 lowercase letter";
            }
        }

        if ($passwordErr) {
            return [
                "error" => $passwordErr
            ];
        } else {
            return [];
        }
    }
}