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
    const RESULT_HIGHER = 'higher';
    const RESULT_LOWER = 'lower';
    const RESULT_EQUAL = 'equal';

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
            'score' => 0,
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

    /**
     * Handles a new guess, adjusts the score and determines the outcome
     * 
     * @param $action string Either 'higher' or 'lower'
     *
     * @return bool 'true' if game can continue, 'false' if game over
     */
    public function play($action)
    {
        // This will be the return value
        // true = continue game
        // false = game over
        $result = null;

        // Compare current card value with next card value
        $cards = session('cards');

        if (count($cards) === 1) {
            // No more cards to deal so we display the final result
            return false;
        }

        $higherOrLower = $this->compareNextCardWithCurrent($cards);

        if ($higherOrLower === self::RESULT_EQUAL) {

            $result = true;

        } elseif ($higherOrLower === $action) {

            // Correct guess
            // Increment the score in the session
            session(['score' => session('score') + 1]);

            $result = true;

        } else {

            // Incorrect guess
            // Decrement the score in the session
            session(['score' => session('score') - 1]);
            if (session('score') < 0) {
                // Remove one life
                session(['lives' => session('lives') - 1]);
                session(['score' => 0]);
                if (session('lives') < 0) {
                    // All lives used up - game over
                    $result = false;
                }
            }
        }

        $this->removeCurrentCard($cards);

        return $result;
    }

    /**
     * Removes the first card in the deck array and updates the session
     *
     * @param [type] $cards
     * @return void
     */
    private function removeCurrentCard($cards)
    {
        array_shift($cards);
        session(['cards' => $cards]);   
    }

    /**
     * Compares the second (next) card in the pack with the first (current) one
     * and returns 'higher', 'lower' or 'equal'
     *
     * @param array $cards
     * @return string
     */
    private function compareNextCardWithCurrent(array $cards)
    {
        $comparisonResult = $this->getCardNumericValue($cards[1]) <=> $this->getCardNumericValue($cards[0]);

        switch ($comparisonResult) {
            case 1:
                return self::RESULT_HIGHER;
                break;

            case -1:
                return self::RESULT_LOWER;
                break;
            
            default:
                return self::RESULT_EQUAL;
                break;
        }
    }

    /**
     * Returns a cards numeric value based on its 'value' string property
     *
     * @param object $card
     * @return int The card's numeric value
     */
    private function getCardNumericValue($card) 
    {
        $cardValueMap = [
            'A' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
            '8' => 8,
            '9' => 9,
            '10' => 10,
            'J' => 11,
            'Q' => 12,
            'K' => 13
        ];

        return $cardValueMap[$card['value']];
    }

    /**
     * Returns information about current score, lives, card dealt
     *
     * @return array
     */
    public function getCurrentGameData()
    {
        return [
            'cards' => session('cards'),
            'currentCard' => session('cards')[0],
            'score' => session('score'),
            'lives' => session('lives'),
            'cardsRemaining' => count(session('cards'))
        ];
    }
}
