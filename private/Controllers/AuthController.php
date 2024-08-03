<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Controllers;

use OpenSB\App;
use OpenSB\Framework\Authentication;
use OpenSB\Framework\Controller;

class AuthController extends Controller {
    public function signout() {
        session_destroy();

        $this->redirect("/");
    }

    public function signin_post() {
        $error = "";
        $authSerivce = App::container()->get(Authentication::class);

        if (isset($_POST["field_command"])) {
            $username = (isset($_POST['field_login_username']) ? $_POST['field_login_username'] : null);
            $password = (isset($_POST['field_login_password']) ? $_POST['field_login_password'] : null);

            $result = $authSerivce->signin($username, $password);

            if (isset($result['error'])) {
                $error = $result['error'];
            }
        }

        if ($error !== "") {
            return $this->frontend->render('login', ['error' => $error ]);
        }

        $this->redirect("/");
    }

    public function signin() {
        $this->frontend->render("login");
    }

    public function signup() {
        $this->frontend->render("register");
    }

    public function signup_post() {
        if (isset($_POST["field_command"])) {
            $username = (isset($_POST['field_signup_username']) ? $_POST['field_signup_username'] : null);
            $pass1 = (isset($_POST['field_signup_password_1']) ? $_POST['field_signup_password_1'] : null);
            $pass2 = (isset($_POST['field_signup_password_2']) ? $_POST['field_signup_password_2'] : null);
            $mail = (isset($_POST['field_signup_email']) ? $_POST['field_signup_email'] : null);
            // $invite = (isset($_POST['field_signup_invite_key']) ? $_POST['field_signup_invite_key'] : null);


            // Check if username is alphanumeric and set
            if (!isset($username) || !ctype_alnum($username)) {
                die("Invalid username");
            }

            // Check if password fields are set and meet minimum length requirement
            if (!isset($pass1) || strlen($pass1) < 8 || !isset($pass2) || $pass1 != $pass2) {
                die("Invalid password");
            }

            // Check if email is set and valid
            if (!isset($mail) || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                die("Invalid email");
            }

            //// Check if invite key matches
            //if ($invite !== $invite_key) {
            //    die("Wrong or no invite key inputted.");
            //}

            $why_the_fuck_is_this_like_this = $this->db->execute("SELECT count(*) FROM users WHERE name = ?", [$username], true);

            if ($why_the_fuck_is_this_like_this["count(*)"] > 0) {
                die("This username has already been taken.");
            }

            $token = bin2hex(random_bytes(50)); // has to be half of the maximum length (100) or it fucks shit up.
            $this->db->execute("INSERT INTO users (name, email, passhash, joined, token) VALUES (?,?,?,?,?)",
                                [$username, $mail, password_hash($pass1, PASSWORD_DEFAULT), time(), $token]);

            $_SESSION['token'] = $token;
        }

        $this->redirect("/");
    }
}
