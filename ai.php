<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

$content = $request->getContent();
$query = json_decode($content, true);

if (!$content || !$query || !isset($query['boardState']) || !isset($query['team'])) {
    $response->setStatusCode(400);
    $response->send();
    exit;
}


$ai = new \TickTackToe\TickTackToeAI();

try {
    $move = $ai->makeMove($query['boardState'], $query['team']);

    $response->setContent(json_encode($move));
} catch (Exception $e /* Pokemon! Gotta' catch 'em all! */) {
    $response->setStatusCode(400);
}

$response->send();
exit;