<?php
$client = new \GuzzleHttp\Client();
$url = 'https://app.matchplay.events/api/tournaments/129878';
$response = $client->get(
    $url,
    [
        'headers' => [
            'Authorization' => '199|5dzKj1QpQgrF10MP0aKRq82fFU6FqvmFjqMBQ8wm9f5e5581',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'query' => [
            'includePlayers' => '1',
            'includeArenas' => '0',
            'includeBanks' => '1',
            'includeScorekeepers' => '0',
            'includeSeries' => '0',
            'includeLocation' => '1',
            'includeRsvpConfiguration' => '1',
            'includeParent' => '1',
            'includePlayoffs' => '1',
            'includeShortcut' => '1',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));

?>