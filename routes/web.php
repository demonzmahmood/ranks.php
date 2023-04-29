<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    $scores = collect([
    ['score' => 76, 'team' => 'A'],
    ['score' => 62, 'team' => 'B'],
    ['score' => 82, 'team' => 'C'],
    ['score' => 86, 'team' => 'D'],
    ['score' => 91, 'team' => 'E'],
    ['score' => 67, 'team' => 'F'],
    ['score' => 67, 'team' => 'G'],
    ['score' => 82, 'team' => 'H'],
]);


$filteredScores = [
        function (Collection $scores) {
            return $scores->sortByDesc('score');
        },
        function (Collection $scores) {
            return $scores->groupBy('score');
        },
        function (Collection $groupedScores) {
            $rank = 1;
            return $groupedScores->map(function ($teams, $score) use (&$rank) {
                $result = $teams->map(function ($team) use ($rank) {
                    return array_merge($team, ['rank' => $rank]);
                });

                $rank += $teams->count();
                return $result;
            });
        },
        function (Collection $rankedGroups) {
            return $rankedGroups->collapse();
        },
];

$rankedScores = $scores->pipeThrough($filteredScores);

    return view('welcome',['rankedScores' => $rankedScores]);
});
