<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

use OpenSB\Framework\Sites\SquareBracketGeneric;
use OpenSB\Framework\Sites\SOOSGeneric;
use OpenSB\Framework\Sites\SquareBracketChaziz;
use OpenSB\Framework\Sites\SOOSChaziz;

/**
 * Set branding and feature flags all from the "site" parameter in the config.
 *
 * Feature flags is an idea I have in mind due to squareBracket and SOOS being different sites in concept. I believe
 * that this would be cleaner than having to do "if Site is squareBracket" or "if Site is SOOS" checks, plus it would
 * make it easier to deal with potential new semi-official(?) websites in the future.
 */

class SiteConfig
{
    private $config;
    private $site;

    function __construct($config)
    {
        $this->config = $config;
        $this->initializeSettings();
    }

    private function initializeSettings()
    {
        if (!isset($this->config)) {
            throw new \Exception('Missing site parameter in configuration.');
        }

        $site = $this->config;
        switch ($site) {
            case 'squarebracket':
                $this->site = new SquareBracketGeneric();
                break;
            case 'soos':
                $this->site = new SOOSGeneric();
                break;
            case 'squarebracket_chaziz':
                $this->site = new SquareBracketChaziz();
                break;
            case 'soos_chaziz':
                $this->site = new SOOSChaziz();
                break;
            default:
                throw new \Exception('Unknown or invalid site type.');
        }
    }

    public function getFeatureFlags()
    {
        return $this->site->getFeatureFlags();
    }
}