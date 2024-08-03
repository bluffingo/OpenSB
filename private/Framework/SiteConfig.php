<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

/**
 * Planned: Set branding and feature flags all from the "site" parameter in the config.
 *
 * Feature flags is an idea I have in mind due to squareBracket and SOOS being different sites in concept. I believe
 * that this would be cleaner than having to do "if Site is squareBracket" or "if Site is SOOS" checks, plus it would
 * make it easier to deal with potential new semi-official(?) websites in the future.
 */
class SiteConfig
{
    function __construct($config)
    {

    }
}