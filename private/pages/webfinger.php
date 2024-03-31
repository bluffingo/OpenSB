<?php

namespace OpenSB;

global $orange, $domain;

$db = $orange->getDatabase();

// https://docs.joinmastodon.org/spec/webfinger/

// from some manual testing with fediverse instances, it seems like that webfinger only works if the account
// belongs to its instance?

// example: https://mastodon.social/.well-known/webfinger?resource=acct:chazizgrkb@mastodon.social
// the content type is "application/jrd+json; charset=utf-8".

//{
//  "subject": "acct:chazizgrkb@mastodon.social",
//  "aliases": [
//    "https://mastodon.social/@chazizgrkb",
//    "https://mastodon.social/users/chazizgrkb"
//  ],
//  "links": [
//    {
//      "rel": "http://webfinger.net/rel/profile-page",
//      "type": "text/html",
//      "href": "https://mastodon.social/@chazizgrkb"
//    },
//    {
//      "rel": "self",
//      "type": "application/activity+json",
//      "href": "https://mastodon.social/users/chazizgrkb"
//    },
//    {
//      "rel": "http://ostatus.org/schema/1.0/subscribe",
//      "template": "https://mastodon.social/authorize_interaction?uri={uri}"
//    }
//  ]
//}

$resource = $_GET['resource'] ?? null;

$address = substr($resource, 5); // remove "acct:"
$extractedAddress = explode('@', $address);
// $extractedAddress[0] -> "bluffingo"
// $extractedAddress[1] -> "squarebracket.pw"

// check if the domain is our domain
if ($extractedAddress[1] != $domain) {
    // it isn't our domain, just 404.
    http_response_code(404);
    die();
} else {
    // it's our domain, so do a db query to see if the user exists.
    if (!$db->fetch("SELECT u.name FROM users u WHERE u.name = ?", [$extractedAddress[0]])) {
        // user doesn't exist, so 404.
        http_response_code(404);
        die();
    } else {
        // user does exist. now attempt outputting relevant information.
        header('Content-Type: application/jrd+json; charset=utf-8');

        $data = [
            "subject" => $resource,
            "aliases" => [
                "https://{$domain}/user/{$extractedAddress[0]}",
            ],
            "links" => [
                [
                    "rel" => "http://webfinger.net/rel/profile-page",
                    "type" => "text/html",
                    "href" => "https://{$domain}/user/{$extractedAddress[0]}",
                ],
                //requires activitypub to be implemented.
                [
                    "rel" => "self",
                    "type" => "application/activity+json",
                    "href" => "https://{$domain}/{$extractedAddress[0]}"
                ],
                //[
                //    "rel" => "http://ostatus.org/schema/1.0/subscribe",
                //    "template" => "https://mastodon.social/authorize_interaction?uri={uri}"
                //]
            ]
        ];

        echo(json_encode($data));
    }
}