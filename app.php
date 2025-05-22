<?php
require_once "classes/BowlingGame.php";
$inputStream = fopen('php://stdin', 'r');
function readLineFromIS($handle): string {
    return trim(fgets($handle));
}

echo "Witaj w programie.\n";
echo "Zadanie testowe P. Witczak 2025\n\n";

$game = new BowlingGame();

while(!$game->isComplete()) {
    echo "podaj ilość strąconych kręgli: ";

    $knockedDownPins = intval(readLineFromIS($inputStream) ?? 0);
    $knockedDownPins = max($knockedDownPins,0);
    $knockedDownPins = min($knockedDownPins,10);
    $game->roll($knockedDownPins);

    echo "Aktualna liczba punktów: {$game->getScore()}\n";

    if($game->getCurrentFrame()->isSpare()) {
        echo "SPARE!\n";
    }
    if($game->getCurrentFrame()->isStrike()) {
        echo "STRIKE!\n";
    }

    echo "\n";
}

fclose($inputStream);

echo "----------------------------------------\n\n";
echo "Podsumowanie gry: \n";

$game->frames->each(function(Frame $f, int $i) {
    echo "Runda ".($i+1)."\n";

    $f->moves->each(function(Roll $b, int $idx) {
        echo "Rzut ".($idx+1).": $b->knockedDownPins strąceń\n";
    });

    if($f->isSpare()) {
        echo "SPARE!\n";
    }
    if($f->isStrike()) {
        echo "STRIKE!\n";
    }
    echo "Punkty: {$f->getScore()} \n\n";
});

echo "Suma zdobytych punktów: {$game->getScore()}\n\n";