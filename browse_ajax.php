<?php
// this is probably one of the worst pieces of code i've ever made.
// bhief, you're allowed to scream profanites at me. -gr 10/28/2021
header('Content-type: application/json');
$rawOutputRequired = true;
// tabs: from squarebracket, trending, popular and music
if (isset($_GET['action_continuation']))
{
?>
{
  "tracking_params": "CAAQhGciEwiTmcLY26LRAhUPG38KHegnAYM",
  "content_html": "Implement this shit later. -Bluey2000k 12/27/2021",
  "load_more_widget_html": "    \n\n\n\n\n    <button class=\"yt-uix-button yt-uix-button-size-default yt-uix-button-default load-more-button yt-uix-load-more browse-items-load-more-button\" type=\"button\" onclick=\";return false;\" aria-label=\"Load more\n\" data-uix-load-more-href=\"/browse_ajax?action_continuation=1&amp;continuation=4qmFsgJ7Eg9GRXdoYXRfdG9fd2F0Y2gaZENCQjZSME5wWjBGQlIxWjFRVUZHVmxWM1FVSldWazFCUVZGQ1IxSllaRzlaV0ZKbVpFYzVabVF5UmpCWk1tZEJRVkZCUVVGUlJVSkJRVUZDUlVGQldYbEtlbG95VG5WcE1GRkpCAGAA&amp;target_id=section-list-068266&amp;direct_render=1\" data-uix-load-more-target-id=\"section-list-068266\" data-scrolldetect-offset=\"600\"><span class=\"yt-uix-button-content\">  <span class=\"load-more-loading hid\">\n      <span class=\"yt-spinner\">\n      <span title=\"Loading icon\" class=\"yt-spinner-img  yt-sprite\"></span>\n\nLoading...\n  </span>\n\n  </span>\n  <span class=\"load-more-text\">\n    Load more\n\n  </span>\n</span></button>\n\n"
}<?php
}
?>