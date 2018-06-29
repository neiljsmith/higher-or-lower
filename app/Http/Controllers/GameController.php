<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Game;

class GameController extends Controller
{
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * Sets up a new game
     *
     */
    public function new()
    {
        $this->game->setup();

        // Redirect to first guess
        return redirect('/game/guess');
    }

    /**
     * Handles guess and displays result
     *
     * @param string|null $action 'higher' or 'lower', or null if first play
     * @return void
     */
    public function guess($action = null)
    {
        if (isset($action)) {
            $gameStatus = $this->game->play($action);
            if ($gameStatus === false) {
                // Game over
                return redirect('/game/over');
            }
        }

        $gameData = $this->game->getCurrentGameData();

        //return $gameData;

        return view('game.guess', compact('gameData'));
    } 

    /**
     * Display the final result, win or lose
     *
     * @return void
     */
    public function over()
    {
        $gameData = $this->game->getCurrentGameData();
        return view('game.over', compact('gameData'));
    }
}
