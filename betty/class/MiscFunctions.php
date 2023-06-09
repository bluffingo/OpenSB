<?php

namespace Betty;

/**
 * Miscellaneous functions that need to be reused.
 *
 * @since 0.1.0
 */
class MiscFunctions
{
    /**
     * Get the submission's file, works for both Qobo mode and local mode.
     *
     * @param array|bool $submission The submission data
     * @return array|string|null
     * @since openSB Beta 3.0
     */
    public static function getSubmissionFile(array|bool $submission): array|string|null
    {
        global $isQoboTV, $bunnySettings;
        if ($submission == null)
        {
            return null;
        }

        if ($isQoboTV) {
            if ($submission['post_type'] == 0) {
                // videofile on videos using bunnycdn are the guid, don't ask me why. -grkb 4/8/2023
                return "https://" . $bunnySettings["streamHostname"] . "/" . $submission["videofile"] . "/playlist.m3u8";
            } elseif ($submission['post_type'] == 2) {
                // https://qobo-grkb.b-cdn.net/dynamic/art/f_eKEJNj4bm.png
                return "https://" . $bunnySettings["pullZone"] . $submission["videofile"];
            }
        }
        return $submission['videofile'];
    }
}