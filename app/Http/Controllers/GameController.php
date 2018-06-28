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
}
