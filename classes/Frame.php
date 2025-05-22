<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Collection;

require_once "Roll.php";
class Frame {
    public Collection $moves;
    private bool $isLastRound;
    private int $bonus = 0;

    /**
     * @param bool $isLastRound Ostatnia ramka gry musi zostać oznaczona przez metodę nadrzędną
     */
    public function __construct(bool $isLastRound = false) {
        $this->moves = collect([]);
        $this->isLastRound = $isLastRound;
    }
    /**
     * Dodaje następny ruch do aktualnej ramki gry
     * Każda ramka z wyjątkiem ostatniej może mieć max 2 ruchy
     *
     * @param Roll $move Nowy ruch do dodania
     */
    public function addNewMove(Roll $move)  : void{
        $this->moves->push($move);
    }

    /**
     * @param int $bonus Dodaje do liczby punktów bonusowych
     */
    public function addBonusPins(int $bonus): void {
        $this->bonus += $bonus;
    }

    /**
     * @return bool Wskazuje na to czy ramka gry jest już zakończona
     */
    function isComplete() : bool {
        if($this->isLastRound && ($this->isStrike() || $this->isSpare())) {
            return $this->moves->count() == 3;
        } else {
            return $this->moves->count() == 2 || $this->isStrike();
        }
    }

    /**
     * @return int Suma punktów zdobytych w tej ramce gry
     */
    function getScore() : int {
        return $this->bonus
            + $this->moves
            ->map(fn ($move) => $move->knockedDownPins)
            ->sum();
    }

    function isStrike() : bool {
        return $this->moves->contains(fn(Roll $move) => $move->knockedDownPins == 10);
    }
    function isSpare() : bool {
        return
            $this->moves->count() >= 2
            && $this->moves->map(fn($move) => $move->knockedDownPins)->sum() >= 10;
    }
}