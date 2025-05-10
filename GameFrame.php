<?php
require_once __DIR__ . '/vendor/autoload.php';
use Illuminate\Support\Collection;

require_once "GameMove.php";
class GameFrame {
    public Collection $moves;
    private bool $isLastRound;
    private int $bonus = 0;

    /**
     * @param bool $isLastRound Ostatnia ramka gry musi zostać oznaczona przez metodę nadrzędną
     */
    public function __construct($isLastRound = false) {
        $this->moves = collect([]);
        $this->isLastRound = $isLastRound;
    }
    /**
     * Dodaje następny ruch do aktualnej ramki gry
     * Każda ramka z wyjątkiem ostatniej może mieć max 2 ruchy
     *
     * @param GameMove $move Nowy ruch do dodania
     */
    public function addNewMove(GameMove $move)  : void{
        $this->moves->push($move);
    }

    /**
     * @param int $bonus Dodaje do liczby punktów bonusowych
     */
    public function bumpBonus(int $bonus): void {
        $this->bonus = $this->bonus + $bonus;
    }

    /**
     * @return bool Wskazuje na to czy ramka gry jest już zakończona
     */
    function isComplete() : bool {
        if($this->isLastRound && ($this->isStreak() || $this->isSpare())) {
            return $this->moves->count() == 3;
        } else {
            return $this->moves->count() == 2 || $this->isStreak();
        }
    }

    /**
     * @return int Suma punktów zdobytych w tej ramce gry
     */
    function getPoints() : int {
        return $this->bonus
            + $this->moves
            ->map(fn ($move) => $move->knockedDownPins)
            ->sum();
    }

    function isStreak() : bool {
        return $this->moves->contains(fn(GameMove $move) => $move->knockedDownPins == 10);
    }
    function isSpare() : bool {
        return
            $this->moves->count() >= 2
            && $this->moves->map(fn($move) => $move->knockedDownPins)->sum() >= 10;
    }
}