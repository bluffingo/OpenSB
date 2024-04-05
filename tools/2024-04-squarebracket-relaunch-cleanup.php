<?php
namespace OpenSB;

global $orange;

define("SB_DYNAMIC_PATH", dirname(__DIR__) . '/dynamic');
define("SB_PRIVATE_PATH", dirname(__DIR__) . '/private');
define("SB_VENDOR_PATH", dirname(__DIR__) . '/vendor');
define("SB_GIT_PATH", dirname(__DIR__) . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

require_once dirname(__DIR__) . '/private/class/common.php';

$sql = $orange->getDatabase();

// STEP 1. Remove "Migrated to Qobo via cattleDog." from user profile descriptions
$cattleDogStringFull = "- Migrated to Qobo via cattleDog.";
$cattleDogStringOnly = "Migrated to Qobo via cattleDog.";

$users = $sql->fetchArray($sql->query("SELECT * FROM users"));

foreach ($users as $user) {
    if (isset($user["about"])) {
        if (str_contains($user["about"], $cattleDogStringFull)) {
            $newAbout = str_replace($cattleDogStringFull, "", $user["about"]);
            $sql->query("UPDATE users SET about = ? WHERE id = ?", [$newAbout, $user["id"]]);
        } elseif ($user["about"] == $cattleDogStringOnly) {
            $sql->query("UPDATE users SET about = NULL WHERE id = ?", [$user["id"]]);
        }
    }
}

// STEP 2. Remove "Originally uploaded to squareBracket/PokTube" labels since this is the same site.
$submissions = $sql->fetchArray($sql->query("SELECT * FROM videos"));

foreach ($submissions as $submission) {
    if (isset($submission["original_site"])) {
        if ($submission["original_site"] == "squareBracket" || $submission["original_site"] == "PokTube") {
            $sql->query("UPDATE videos SET original_site = NULL WHERE id = ?", [$submission["id"]]);
        }
    }
}

// STEP 3. Remove "Bluey diaper" submissions. Even with content filtering, I don't think this should have been on
// the site to begin with. The way this will be done is that PaddedBluffingo will be completely wiped, and submissions
// gU5H3r4oR7W and UypIbleB-ga by Anonymos will be deleted. This isn't about "OYC drama" or whatever.
$paddedBluffingoUserID = $sql->result("SELECT id FROM users where name = 'PaddedBluffingo'");

//DELETE FROM videos WHERE id = 521

foreach ($submissions as $submission) {
    if ($submission["author"] == $paddedBluffingoUserID) {
        $sql->query("DELETE FROM videos WHERE id = ?", [$submission["id"]]);
        $sql->query("INSERT INTO takedowns (submission, time, reason, sender) VALUES (?,?,?,?)",
            [$submission["video_id"], time(), "Bluey diaper is no longer allowed on squareBracket.", 1]);
    } elseif ($submission["video_id"] == "gU5H3r4oR7W" || $submission["video_id"] == "UypIbleB-ga") {
        $sql->query("DELETE FROM videos WHERE id = ?", [$submission["id"]]);
        $sql->query("INSERT INTO takedowns (submission, time, reason, sender) VALUES (?,?,?,?)",
            [$submission["video_id"], time(), "Bluey diaper is no longer allowed on squareBracket.", 1]);
    }
}