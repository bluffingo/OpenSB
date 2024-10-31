<?php

namespace SquareBracket;

/**
 * Authentication stuff.
 */
class Authentication
{
    private Database $database;
    private bool $is_logged_in = false;
    private int $user_id;
    private array $user_data;
    private $user_ban_data;
    private $user_notice_count; // this shouldn't be here but whatever
    // TODO: make this default blacklist configurable per instance
    private $default_tags_blacklist = [];
    private $has_authenticated_as_an_admin = false;

    public function __construct(Database $database)
    {
        $accountfields = "id, ip, name, title, email, title, about, powerlevel, joined, lastview, birthdate, comfortable_rating, customcolor, blacklisted_tags, token";
        $this->database = $database;
        $token = $_SESSION["SBTOKEN"] ?? null;
        if (isset($token)) {
            if($this->user_id = $this->database->result("SELECT id FROM users WHERE token = ?", [$token])) {
                $this->is_logged_in = true;
                $this->user_data = $this->database->fetch("SELECT $accountfields FROM users WHERE id = ?", [$this->user_id]);
                $this->user_notice_count = $this->database->result("SELECT COUNT(*) FROM notifications WHERE recipient = ?", [$this->user_id]);
                $this->user_ban_data = $this->database->fetch("SELECT * FROM bans WHERE userid = ?", [$this->user_id]);

                if (!isset($this->user_data['blacklisted_tags'])) {
                    $this->user_data['blacklisted_tags'] = $this->default_tags_blacklist;
                } else {
                    $this->user_data['blacklisted_tags'] = json_decode($this->user_data['blacklisted_tags']); // decode this shit on the fly
                }

                if (!isset($this->user_data['birthdate'])) {
                    $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
                    $path = explode('/', $uri);
                    if ($path[1] != "verify_birthdate") {
                        header('Location: /verify_birthdate');
                        die();
                    }
                }

                // check if the current logged-in user is IP banned from another address, if so, then log them out.
                // this will prevent users from using IP banned accounts on other IPs.
                if ($this->database->fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [$this->user_data['ip']])) {
                    setcookie("SBTOKEN", "", time() - 3600);
                    Utilities::bannerNotification("You have been logged out, as this account is linked to a banned IP address.", true);
                }

                // update "last logged in" timestamp after 12 hours.
                if ($database->result("SELECT COUNT(*) FROM users WHERE lastview < ? AND id = ?", [time() - (12 * 60 * 60), $this->user_id])) {
                    $database->query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), Utilities::getIpAddress(), $this->user_id]);
                }

                // if "comfortable rating" is questionable, reset it back to general. this is because the site now uses
                // "general" and "sensitive" instead of the old "general", "questionable" and "mature" ratings, but the
                // old system is left there for compatibility. -chaziz 6/9/2024
                if ($this->user_data["comfortable_rating"] == "questionable") {
                    $this->database->query("UPDATE users SET comfortable_rating = 'general' WHERE id = ?", [$this->user_id]);
                    Utilities::bannerNotification("Your content filtering settings have been reset to General.", false, "primary");
                }

                $this->has_authenticated_as_an_admin = $_SESSION["SB_ADMIN_AUTHED"] ?? null;
            }
        }
    }

    public function isUserLoggedIn(): bool
    {
        return $this->is_logged_in;
    }

    public function getUserID(): ?int
    {
        if ($this->is_logged_in) {
            return $this->user_id;
        } else {
            return null;
        }
    }

    public function getUserData(): ?array
    {
        if ($this->is_logged_in) {
            return $this->user_data;
        } else {
            return [
                "comfortable_rating" => "general",
                "blacklisted_tags" => $this->default_tags_blacklist,
            ];
        }
    }

    public function getUserNoticesCount(): ?int
    {
        if ($this->is_logged_in) {
            return $this->user_notice_count;
        } else {
            return 0;
        }
    }

    public function getUserBanData()
    {
        return $this->user_ban_data;
    }

    public function isUserAdmin()
    {
        if ($this->is_logged_in) {
            return ($this->user_data['powerlevel'] >= 3);
        } else {
            return false;
        }
    }

    public function hasUserAuthenticatedAsAnAdmin() {
        if ($this->isUserAdmin()) {
            return $this->has_authenticated_as_an_admin;
        } else {
            return false;
        }
    }

    public function getUserBlacklistedTags()
    {
        if ($this->is_logged_in) {
            return $this->user_data['blacklisted_tags'];
        } else {
            return $this->default_tags_blacklist;
        }
    }

    public function getDefaultBlacklistedTags()
    {
        return $this->default_tags_blacklist;
    }
}