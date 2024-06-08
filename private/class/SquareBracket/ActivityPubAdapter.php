<?php

namespace SquareBracket;

use Symfony\Component\HttpClient\HttpClient;

class ActivityPubAdapter
{
    private $httpClient;
    private $sb;
    private $db;
    public function __construct(SquareBracket $sb) {
        $this->sb = $sb;
        $this->db = $sb->getDatabase();

        $this->httpClient = HttpClient::create([
            "headers" => [
                "User-Agent" => (new VersionNumber)->printVersionForUserAgent(),
            ],
        ]);
    }

    public function getProfileFromWebFinger($data)
    {
        $url = false;
        foreach ($data["links"] as $link) {
            if (isset($link['rel'], $link['type'], $link['href']) &&
                $link['rel'] === 'self' &&
                $link['type'] === 'application/activity+json') {
                $url =  $link['href'];
            }
        }

        if ($url) {
            $response = $this->httpClient->request(
                "GET",
                $url,
                [
                    'headers' => [
                        'Accept' => 'application/ld+json; profile="https://www.w3.org/ns/activitystreams"',
                    ],
                ]
            );

            if ($response->getStatusCode() != 200) {
                return false;
            } else {
                return $response->toArray();
            }
        }
    }

    // this is going to be a pain in the ass later on. why? well we need to keep a copy of the
    // required urls in the database, but on a different table. i feel like this could
    // cause problems if an opensb instance gets knocked offline temporarily causing it
    // to "desync" from other instances since those can't send updates to the opensb instance
    // for the time being. this will be problematic with squarebracket.pw as it often has long
    // downtimes over hosting-related issues.
    public function makeDummySquareBracketAccount($profileData, $name)
    {
        // using preferredUsername would be better but ergh, whatever.
        $this->db->query("INSERT INTO users (name, email, password, title, about, token) VALUES (?,?,?,?,?,?)",
            [$name, "dummy@fakeemail.com", "UselessHash-" . time(), $profileData["name"], $profileData["summary"], bin2hex(random_bytes(32))]);

        $new_id = $this->db->result("SELECT id FROM users WHERE name = ?", [$name]);

        $this->db->query("INSERT INTO activitypub_user_urls (user_id, id, featured, followers, following, profile_picture, banner_picture, inbox, outbox, last_updated) VALUES (?,?,?,?,?,?,?,?,?,?)",
            [$new_id, $profileData["id"], $profileData["featured"], $profileData["followers"],
                $profileData["following"], $profileData["icon"]["url"], $profileData["image"]["url"],
                $profileData["inbox"], $profileData["outbox"], time()]);
    }
}