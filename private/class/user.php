<?php

namespace openSB;

use DateTime;

class Users
{
    /**
     * Return HTML code for an userlink.
     *
     * @param array $user User array containing user fields. Retrieve this from the database using userfields().
     * @param string $pre $user key prefix.
     * @return string Userlink HTML code.
     */
    public static function userlink($user, $pre = ''): string
    {
        if (self::getIsUserBannedFromName($user[$pre . 'name'])) {
            $user[$pre . 'colorname'] = sprintf('<span style="color:gray">%s</span>', $user[$pre . 'name']);
            return <<<HTML
			<s class="user"><span class="t_user">{$user[$pre . 'colorname']}</span></s>
	HTML;
        } else {
            if ($user[$pre . 'customcolor']) {
                $user[$pre . 'colorname'] = sprintf('<span style="color:%s">%s</span>', $user[$pre . 'customcolor'], $user[$pre . 'name']);
            }
            return <<<HTML
			<a class="user" href="/user.php?name={$user[$pre . 'name']}"><span class="t_user">{$user[$pre . 'colorname']}</span></a>
	HTML;
        }
    }

    /**
     * Verifies if another user is banned via their name
     *
     * @return bool
     */
    public static function getIsUserBannedFromName($userName): bool
    {
        global $sql;
        $id = $sql->result("SELECT id FROM users WHERE name=?", [$userName]);
        return self::getIsUserBannedFromID($id);
    }

    /**
     * Verifies if another user is banned via their ID
     *
     * @return bool
     */
    public static function getIsUserBannedFromID($userID): bool
    {
        global $sql;
        if ($sql->result("SELECT userid FROM bans WHERE userid=?", [$userID])) { // get uid from ban data
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get list of SQL SELECT fields for userlinks.
     *
     * @return string String to put inside a SQL statement.
     */
    public static function userfields(): string
    {
        $fields = ['id', 'name', 'customcolor', 'powerlevel'];

        $out = '';
        foreach ($fields as $field) {
            $out .= sprintf('u.%s u_%s,', $field, $field);
        }

        return $out;
    }

    /**
     * Get the amount of videos a user has uploaded. Probably index this shit in the future.
     *
     * @return int
     */
    public static function getUserVideoCount($userID): int
    {
        global $sql;
        $count = $sql->result("SELECT COUNT(id) FROM videos WHERE author=?", [$userID]);
        return $count;
    }

    /**
     * Get the amount of videos a user has favorited. Probably index this shit in the future.
     *
     * @return int
     */
    public static function getUserFavoriteCount($userID): int
    {
        global $sql;
        $count = $sql->result("SELECT COUNT(user_id) FROM favorites WHERE user_id=?", [$userID]);
        return $count;
    }

    /**
     *
     * Registers an user.
     *
     * @param $name
     * @param $pass
     * @param $mail
     * @return string
     * @throws \Exception
     */
    public static function register($name, $pass, $mail): string
    {
        global $sql;
        $token = bin2hex(random_bytes(20));
        $sql->query("INSERT INTO users (name, password, email, token, joined, lastview, ip) VALUES (?,?,?,?,?,?,?)",
            [$name, password_hash($pass, PASSWORD_DEFAULT), $mail, $token, time(), time(), getUserIpAddr()]);

        return $token;
    }
}