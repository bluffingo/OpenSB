<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework\Sites;
abstract class SiteBase
{
    protected $defaultFeatureFlags = [
        'SBChat_Enable' => false,
        'Footer_Show_Chaziz_Logo' => false,
    ];

    abstract public function getSpecificFeatureFlags();

    public function getFeatureFlags()
    {
        return array_merge($this->defaultFeatureFlags, $this->getSpecificFeatureFlags());
    }
}