<?php

namespace OpenSB;

global $enableFederatedStuff, $domain;

if (!$enableFederatedStuff) { die(); }

/*
my testing instance of akkoma 3.13.2 complains about not finding a lrdd template
14:53:02.715 [warning] Can't find LRDD template in "https://helloworld.sb/.well-known/host-meta": {:error, :econnrefused}

this appears to be a new thing, since my previous testing which was done online in the wild on an akkoma 3.10.4
instance was able to fetch a opensb profile pretty easily.

UPDATE: no, it's a weird-ass WSL quirk mixed with ssl shittery. i pointed helloworld.sb to my local ip and i
disabled ssl checks on akkoma through via the help of https://docs.akkoma.dev/stable/development/setting_up_akkoma_dev/#testing-with-https

this is the output:

<?xml version="1.0" encoding="UTF-8"?>
<XRD
	xmlns="http://docs.oasis-open.org/ns/xri/xrd-1.0">
	<Link type="application/xrd+xml" template="http://helloworld.chaziz/.well-known/webfinger?resource={uri}" rel="lrdd" />
</XRD>

the content-type isn't "application/xml" but is instead "application/xrd+xml; charset=utf-8"

-chaziz 6/6/2024
*/

header('Content-Type: application/xrd+xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>' .
    '<XRD xmlns="http://docs.oasis-open.org/ns/xri/xrd-1.0">' .
    '<Link type="application/xrd+xml" template="https://' . $domain . '/.well-known/webfinger?resource={uri}" rel="lrdd" />' .
    '</XRD>';