<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Game;

class GameController extends Controller
{

    /**
     * Sets up a new game
     *
     */
    public function new()
    {
        $game = new Game();
        $game->setup();

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
            $game = new Game();
            $gameStatus = $game->play($action);
            if ($gameStatus === false) {
                // Game over
                return redirect('/game/over');
            }
        }

        $gameData = Game::getCurrentGameData();

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
        $gameData = Game::getCurrentGameData();
        return view('game.over', compact('gameData'));
    }
}
