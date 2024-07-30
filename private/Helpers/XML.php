<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Helpers;

class XML {
    // https://stackoverflow.com/a/5965940 (but modified)
    public static function arrayToXML(array $array, \SimpleXMLElement $node) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = 'item' . $key; //dealing with <0/>..<n/> issues
                }

                $subnode = $node->addChild($key);
                self::arrayToXML($value, $subnode);
            } else {
                $node->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }
}
