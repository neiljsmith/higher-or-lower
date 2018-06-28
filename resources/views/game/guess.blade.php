@extends('layouts.app')
@section('content')
<h2>Guess 'higher' or 'lower'!</h2>
<p>Card dealt: {{ $gameData['currentCard']['value'] }} of {{ $gameData['currentCard']['suit'] }}</p>
<p>Score: {{ $gameData['score'] }}</p>
<p>Lives: {{ $gameData['lives'] }}</p>
<p>Cards remaining: {{ $gameData['cardsRemaining'] }}</p>
<h3>Next guess:</h3>
<a href="/game/guess/higher">Higher</a> or <a href="/game/guess/lower">Lower</a>
@endsection