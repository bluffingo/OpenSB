<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

// NOTE: this is not the same thing as the previous implementation of uploaddata

class UploadData implements Data
{
    public function __construct()
    {
    }

    public function getData(): array
    {
        // TODO: Implement getData() method.
        return [];
    }

    public function modifyData($data): bool
    {
        // TODO: Implement updateData() method.
        return false;
    }
}