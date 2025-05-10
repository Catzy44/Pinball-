<?php
require_once __DIR__ . '/vendor/autoload.php';
use Illuminate\Support\Collection;

require_once "GameMove.php";
class GameFrame {
    public Collection $moves;
    private bool $isLastRound;
    private int $bonus = 0;
    public function __construct($isLastRound = false) {
        $this->moves = collect([]);
        $this->isLastRound = $isLastRound;
    }
    public function addNewMove(GameMove $move)  : void{
        $this->moves->push($move);
    }
    public function bumpBonus(int $bonus): void {
        $this->bonus = $this->bonus + $bonus;
    }
    function isStreak() : bool {
        return $this->moves->contains(fn(GameMove $move) => $move->knockedDownPins == 10);
    }
    function isSpare() : bool {
        return
            $this->moves->count() >= 2
            && $this->moves->map(fn($move) => $move->knockedDownPins)->sum() >= 10;
    }
    function isComplete() : bool {
        if($this->isLastRound && ($this->isStreak() || $this->isSpare())) {
            return $this->moves->count() == 3;
        } else {
            return $this->moves->count() == 2 || $this->isStreak();
        }
    }
    function getPoints() : int {
        return $this->bonus
            + $this->moves
            ->map(fn ($move) => $move->knockedDownPins)
            ->sum();
    }
}