<?php
require_once __DIR__ . '/vendor/autoload.php';
use Illuminate\Support\Collection;

require_once "GameFrame.php";
require_once "GameMove.php";
require_once "GameBonus.php";

class Game {
    public Collection $frames;
    public Collection $bonuses;
    function __construct() {
        $this->frames = collect([]);
        $this->bonuses = collect([]);
    }

    /**
     * Wykonaj ruch w grze
     *
     * @param  int  $knockedDownPins  Liczba strąconych kręgli
     * @return void
     */
    function roll(int $knockedDownPins = 0) : void {
        $newMove = new GameMove($knockedDownPins);

        if($this->getCurrentGameFrame() == null || $this->getCurrentGameFrame()->isComplete()) {
            $isLastRound = $this->frames->count() == 9;
            $newFrame = new GameFrame($isLastRound);
            $newFrame->addNewMove($newMove);
            $this->frames->push($newFrame);
        } else {
            $this->getCurrentGameFrame()->addNewMove($newMove);
        }

        $this->bonuses->each(function(GameBonus $bonus) use ($knockedDownPins) {
            if($bonus->moves > 0) {
                $bonus->frame->bumpBonus($knockedDownPins);
                $bonus->moves--;
            }
        });

        //no bonuses in the last round!
        if($this->frames->count() >= 10) {
            return;
        }
        $currentGameFrame = $this->getCurrentGameFrame();
        if($currentGameFrame->isStreak()) {
            $this->bonuses->push(new GameBonus($currentGameFrame,2));
        }
        if($currentGameFrame->isSpare()) {
            $this->bonuses->push(new GameBonus($currentGameFrame,1));
        }
    }

    function isComplete() : bool {
        return
             $this->frames->count() == 10
            && $this->frames->every(fn(GameFrame $frame) => $frame->isComplete());
    }

    /**
     * Sprawdź aktualną liczbę punktów
     *
     * @return int Aktualna liczba punktów
     */
    function getScore() : int {
        return $this->frames
            ->map(fn(GameFrame $frame) => $frame->getPoints())
            ->sum();
    }
    function getLastGameFrame() : ?GameFrame {
        return $this->frames->get($this->frames->count()-2);
    }
    function getCurrentGameFrame() : ?GameFrame {
        return $this->frames->last();
    }
    function getCurrentGameMove() : ?GameMove {
        return $this->getCurrentGameFrame()->moves->last();
    }
}