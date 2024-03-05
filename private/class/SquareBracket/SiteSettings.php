<?php

namespace SquareBracket;

/**
 * Non-hardcoded website settings.
 *
 * @since SquareBracket 1.1
 */
class SiteSettings
{
    private \SquareBracket\Database $database;
    private $data;

    public function __construct(\SquareBracket\Database $database)
    {
        global $branding, $isMaintenance;

        $this->database = $database;
        $this->data = $this->database->fetch("SELECT * FROM site_settings");

        // temporary code for migrating from orange 1.0
        if (empty($this->data)) {
            $this->database->query("INSERT INTO `site_settings` (`development`, `maintenance`, `branding_name`, `branding_assets`) VALUES (?,?,?,?)",
            [0, (int)$isMaintenance, $branding["name"], $branding["assets_location"]]);
        }
    }

    public function getDevelopmentMode()
    {
        return $this->data["development"];
    }

    public function getMaintenanceMode()
    {
        return $this->data["maintenance"];
    }

    public function getBrandingSettings(): array
    {
        return [
            "name" => $this->data["branding_name"],
            "assets_location" => $this->data["branding_assets"],
        ];
    }
}