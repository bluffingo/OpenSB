<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework\Sites;

class SquareBracketChaziz extends SiteBase
{
    public function getSpecificFeatureFlags()
    {
        return [
            'SBChat_Enable' => true,
            'Footer_Show_Chaziz_Logo' => true,
        ];
    }
}