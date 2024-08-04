<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework\Sites;

class SOOSChaziz extends SiteBase
{
    public function getSpecificFeatureFlags()
    {
        return [
            'Footer_Show_Chaziz_Logo' => true,
        ];
    }
}