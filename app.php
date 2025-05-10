<?php
require_once "Game.php";
$inputStream = fopen('php://stdin', 'r');
function readLineFromIS($handle): string {
    return trim(fgets($handle));
}

echo "Witaj w programie.\n";
echo "Zadanie testowe P. Witczak 2025\n\n";

$game = new Game();

while(!$game->isComplete()) {
    echo "podaj ilość strąconych kręgli: ";

    $knockedDownPins = intval(readLineFromIS($inputStream) ?? 0);
    $knockedDownPins = max($knockedDownPins,0);
    $knockedDownPins = min($knockedDownPins,10);
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

$game->frames->each(function(GameFrame $f, int $i) {
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
});

echo "Suma zdobytych punktów: {$game->getScore()}\n\n";