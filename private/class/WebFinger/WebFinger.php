<?php

namespace WebFinger;

use Core\VersionNumber;
use Symfony\Component\HttpClient\HttpClient;

// class for dealing with other server's webfingers.

class WebFinger
{
    private $database;
    private $address;
    private $client;
    private $data;

    public function __construct($database, $address)
    {
        $this->address = $address;
        $this->database = $database;

        $this->client = HttpClient::create([
            "headers" => [
                "User-Agent" => (new \Core\VersionNumber)->printVersionForUserAgent(),
            ],
        ]);
    }

    public function getWebFinger()
    {
        $extractedAddress = explode('@', $this->address);
        // $extractedAddress[0] -> "bluffingo"
        // $extractedAddress[1] -> "squarebracket.pw"

        // http://squarebracket.pw/.well-known/webfinger?resource=acct:bluffingo@squarebracket.pw
        $response = $this->client->request(
            "GET",
            "https://{$extractedAddress[1]}/.well-known/webfinger?resource=acct:{$this->address}"
        );

        // If the webfinger request failed, then don't bother.
        if ($response->getStatusCode() != 200) {
            return false;
        } else {
            $this->data = json_decode($response->getContent(), true);
            return true;
        }
    }
}