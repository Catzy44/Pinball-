<?php
require_once "Game.php";
$inputStream = fopen('php://stdin', 'r');
function readLineFromIS($handle): string {
    return trim(fgets($handle));
}

echo "Witaj w programie.\n";
echo "Zadanie testowe P. Witczak 2025\n\n";

$game = new Game();

$game->roll(10);
$game->roll(7);
$game->roll(3);
$game->roll(9);
$game->roll(0);
$game->roll(10);
$game->roll(10);
$game->roll(10);
$game->roll(5);
$game->roll(3);
$game->roll(6);
$game->roll(4);
$game->roll(7);
$game->roll(2);

//$game->roll(10);

while(!$game->isComplete()) {
    echo "podaj ilość strąconych kręgli: ";
    $knockedDownPins = intval(readLineFromIS($inputStream));
    $game->roll($knockedDownPins);
    echo "Aktualna liczba punktów: {$game->getScore()}\n";
    if($game->getCurrentGameFrame()->isSpare()) {
        echo "SPARE!\n";
    }
    if($game->getCurrentGameFrame()->isStreak()) {
        echo "STRIKE!\n";
    }

    echo "\n";
}

fclose($inputStream);

echo "----------------------------------------\n\n";
echo "Podsumowanie gry: \n";

for($i = 0; $i < $game->frames->count(); $i++) {
    $f = $game->frames[$i];
    echo "Runda ".($i+1)."\n";

    $f->moves->each(function(GameMove $b, int $idx) {
        echo "Rzut ".($idx+1).": $b->knockedDownPins strąceń\n";
    });

    if($f->isSpare()) {
        echo "SPARE!\n";
    }
    if($f->isStreak()) {
        echo "STRIKE!\n";
    }
    echo "Punkty: {$f->getPoints()} \n\n";
}

echo "Suma zdobytych punktów: {$game->getScore()}\n\n";