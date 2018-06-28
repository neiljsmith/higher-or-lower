@extends('layouts.app')
@section('content')
<h2>Game over!</h2>
<h3>Final result:</h3>
<p>Score: {{ $gameData['score'] }}</p>
@endsection