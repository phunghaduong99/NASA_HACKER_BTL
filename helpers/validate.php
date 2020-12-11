<?php

class Validate {
    function validateEmail($email) {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                "success" => false,
                "error" => "Email is invalid."
            ];
        }
        return [
            "success" => true
        ];
    }

    function validatePassword($password) {
        $passwordErr = null;

        if (empty($password)) {
            $passwordErr = "Password is required";
        } elseif (is_string($password)) {
            $passwordErr = "Password must be a String";
        } else {
            if (strlen($password) <= '8'
                || !preg_match("#[0-9]+#",$password)
                || !preg_match("#[A-Z]+#",$password)
                || !preg_match("#[a-z]+#",$password)
            ) {
                $passwordErr = "Your password must contain at least 8 characters, at least 1 number, at least 1 capital letter, 1 lowercase letter";
            }
        }

        if ($passwordErr) {
            return [
                "success" => false,
                "error" => $passwordErr
            ];
        } else {
            return [
                "success" => true
            ];
        }
    }
}