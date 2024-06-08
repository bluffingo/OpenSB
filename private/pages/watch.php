<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\CoreException;
use SquareBracket\Pages\SubmissionView;
use SquareBracket\UnorganizedFunctions;

$id = $path[2] ?? null;

if (isset($_GET['v'])) UnorganizedFunctions::redirect('/submission/' . $_GET['v']);

try {
    $page = new SubmissionView($orange, $id);
    $data = $page->getSubmission();
} catch (CoreException $e) {
    $e->page();
}

echo $twig->render('watch.twig', [
    'submission' => $data,
]);