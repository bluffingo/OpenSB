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
            return $this->getProfileFromURL($url);
        } else {
            return false;
        }
    }

    public function getProfileFromURL($url)
    {
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

    // this is going to be a pain in the ass later on. why? well we need to keep a copy of the
    // required urls in the database, but on a different table. i feel like this could
    // cause problems if an opensb instance gets knocked offline temporarily causing it
    // to "desync" from other instances since those can't send updates to the opensb instance
    // for the time being. this will be problematic with squarebracket.pw as it often has long
    // downtimes over hosting-related issues.
    private function makeDummySquareBracketAccount($profileData, $name)
    {
        // using preferredUsername would be better but ergh, whatever.
        $this->db->query("INSERT INTO users (name, email, password, title, about, token) VALUES (?,?,?,?,?,?)",
            [$name, "dummy@fakeemail.com", "UselessHash-" . time(), $profileData["name"], $profileData["summary"], bin2hex(random_bytes(32))]);

        $new_id = $this->db->result("SELECT id FROM users WHERE name = ?", [$name]);

        $iconUrl = $profileData["icon"]["url"] ?? null;
        $imageUrl = $profileData["image"]["url"] ?? null;

        $this->db->query("INSERT INTO activitypub_user_urls (user_id, id, featured, followers, following, profile_picture, banner_picture, inbox, outbox, last_updated) VALUES (?,?,?,?,?,?,?,?,?,?)",
            [$new_id, $profileData["id"], $profileData["featured"], $profileData["followers"],
                $profileData["following"], $iconUrl, $imageUrl,
                $profileData["inbox"], $profileData["outbox"], time()]);
    }

    public function getFediProfileFromWebFinger($handle)
    {
        $webfinger = new WebFinger($this->db, $handle);
        if ($webfinger->requestWebFinger()) {
            if ($data = $this->getProfileFromWebFinger($webfinger->getWebFingerData())) {
                if (!$this->db->fetch("SELECT u.name FROM users u WHERE u.name = ?", [$handle])) {
                    $this->makeDummySquareBracketAccount($data, $handle);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function getFediProfileFromURL($url)
    {
        if ($data = $this->getProfileFromURL($url)) {
            $handle = $this->urlToWebFinger($url);
            if (!$this->db->fetch("SELECT u.name FROM users u WHERE u.name = ?", [$handle])) {
                $this->makeDummySquareBracketAccount($data, $handle);
            }
            return true;
        } else {
            return false;
        }
    }

    private function urlToWebFinger($url) {
        $parsedUrl = parse_url($url);
        $domain = $parsedUrl['host'];
        $path = $parsedUrl['path'];
        $pathParts = explode('/', trim($path, '/'));
        $username = end($pathParts);
        return "$username@$domain";
    }

    public function create(mixed $body)
    {
        $actor = $body["actor"];
        $content = $body["object"]["content"];
        $published = strtotime($body["object"]["published"]);

        $id = $body["object"]["id"] ?? null;
        $context = $body["object"]["context"] ?? null;
        $conversation = $body["object"]["conversation"] ?? null;
        $inReplyTo = $body["object"]["inReplyTo"] ?? null;

        $this->getFediProfileFromURL($actor);
        $handle = $this->urlToWebFinger($actor); // stupid poor design that should be redone!!!

        $actorSbID = $this->db->fetch("SELECT u.name, u.id FROM users u WHERE u.name = ?", [$handle])["id"];
        //$this->getWebFinger($actor); it's not a webfinger

        //INSERT INTO `posts` (`id`, `author`, `contents`, `attachments`, `context`, `conversation`, `activitypubId`, `inReplyTo`, `posted`) VALUES ('1', '1', '1', NULL, '1', '1', '1', '1', '1');

        $this->db->query("INSERT INTO posts (activitypubId, author, contents, attachments, context, conversation, inReplyTo, posted) VALUES (?,?,?,?,?,?,?,?)",
            [$id, $actorSbID, $content, null,
                $context, $conversation, $inReplyTo,
                $published]);
    }

    // todo: figure out why i get random requests from instances that i haven't interacted with -chaziz 6/7/2024
    public function delete(mixed $body)
    {
    }

    public function update(mixed $body)
    {
    }

    public function like(mixed $body)
    {
    }
}