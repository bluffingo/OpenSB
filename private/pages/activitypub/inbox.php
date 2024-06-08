<?php

namespace OpenSB;

global $enableFederatedStuff, $activityPubAdapter;

if (!$enableFederatedStuff) { die(); }

// this is all guesses from logs i got while having a private conversation
// with someone on a fedi instance all while pinging a dummy user from a test instance.
// please don't use this as reference. this is shitcode. -chaziz 6/7/2024

$body = json_decode(file_get_contents("php://input"), true);

if (isset($body["type"])) {
    switch ($body["type"]) {
        case "Create":
            $activityPubAdapter->create($body);
            break;
        case "Delete":
            $activityPubAdapter->delete($body);
            break;
        case "Update":
            $activityPubAdapter->update($body);
            break;
        case "Like":
            $activityPubAdapter->like($body);
            break;
    }
    // "EmojiReact" is a pleroma feature i believe, not adding that for now.
}