<?php
// this code comes from gamerappa's poktube, -bluey december 10 2021
require('lib/common.php');

// Load Last 150 Git Logs
$git_history = [];
$git_logs = [];
exec('git log --pretty=format:"commit %H %n Author: %an %nDate: %aD %n %s %n" -n 150', $git_logs);

// Parse Logs
$last_hash = null;
foreach ($git_logs as $line)
{
    // Clean Line
    $line = trim($line);

    // Proceed If There Are Any Lines
    if (!empty($line))
    {
        // Commit
        if (strpos($line, 'commit') === 0)
        {
            $hash = explode(' ', $line);
            $hash = trim(end($hash));
            $git_history[$hash] = [
                'message' => ''
            ];
            $last_hash = $hash;
        }

        // Author
        else if (strpos($line, 'Author') !== false) {
            $author = explode(':', $line);
            $author = trim(end($author));
			// shitty hack to make it so that the github username is being used.
			if ($author == "Gamerappa" or $author == "Blue2k" or $author == "blue2000k" or $author == "bluey2000k") {
				$git_history[$last_hash]['author'] = "PF94";
			} elseif ($author == "John R") {
				$git_history[$last_hash]['author'] = "jkrosado"; // dumbass
			} elseif ($author == "dependabot[bot]") {
				$git_history[$last_hash]['author'] = "dependabot";
			}
			else {
            $git_history[$last_hash]['author'] = $author;
			}
        }

        // Date
        else if (strpos($line, 'Date') !== false) {
            $date = explode(':', $line, 2);
            $date = trim(end($date));
            $git_history[$last_hash]['date'] = date('F j, Y, g:i a', strtotime($date));
        }

        // Message
        else {
            $git_history[$last_hash]['message'] .= $line ." ";
        }
    }
}
// echo "<pre>";
// print_r($git_history);
// echo "</pre>";
$twig = twigloader();
echo $twig->render('githistory.twig', [
    'git' => $git_history,
]);
?>