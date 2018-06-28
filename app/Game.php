<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;

class Game extends Model
{
    const INITIAL_LIVES = 3;

    /**
     * Clears session data and sets up a new game
     *
     * @return void
     */
    public function setup()
    {
        // Clear session
        session()->flush();

        $cards = $this->getCards();
        shuffle($cards);

        // Initialise the session vars
        session([
            'cards' => $cards,
            'lives' => self::INITIAL_LIVES
        ]);
    }

    /**
     * Retrieves cards data from API and shuffles it
     *
     * @return array
     */
    private function getCards()
    {
        $requestUri = 'https://cards.davidneal.io/api/cards';

        // Create new Guzzle client and perform the request
        $client = new Client();
        $response = $client->request('get', $requestUri);
        $cards = json_decode($response->getBody(), true);

        return $cards;
    }
}
