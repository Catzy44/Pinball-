<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Collection;

require_once "Frame.php";
require_once "Roll.php";
require_once "FrameBonus.php";

class BowlingGame {
    public Collection $frames;
    public Collection $bonuses;
    function __construct() {
        $this->frames = collect([]);
        $this->bonuses = collect([]);
    }

    /**
     * Wykonuje ruch w grze
     *
     * @param  int  $knockedDownPins  Liczba strąconych w ruchu kręgli
     * @return void
     */
    function roll(int $knockedDownPins = 0) : void {
        $newMove = new Roll($knockedDownPins);

        if($this->getCurrentFrame() == null || $this->getCurrentFrame()->isComplete()) {
            $isLastRound = $this->frames->count() == 9;
            $newFrame = new Frame($isLastRound);
            $newFrame->addNewMove($newMove);
            $this->frames->push($newFrame);
        } else {
            $this->getCurrentFrame()->addNewMove($newMove);
        }

        $this->bonuses->each(function(FrameBonus $bonus) use ($knockedDownPins) {
            if($bonus->remainingBonusRolls() > 0) {
                $bonus->getFrame()->addBonusPins($knockedDownPins);
                $bonus->useOneBobusRoll();
            }
        });
        $this->bonuses = $this->bonuses->reject(fn(FrameBonus $bonus) => $bonus->remainingBonusRolls() <= 0);

        //no bonuses in the last round!
        if($this->frames->count() >= 10) {
           return;
        }
        $currentGameFrame = $this->getCurrentFrame();
        if($currentGameFrame->isStrike()) {
            $this->bonuses->push(new FrameBonus($currentGameFrame,2));
        }
        if($currentGameFrame->isSpare()) {
            $this->bonuses->push(new FrameBonus($currentGameFrame,1));
        }
    }

    /**
     * @return bool Wskazuje na to czy gra się już skończyła
     */
    function isComplete() : bool {
        return
            $this->frames->count() == 10
            && $this->frames->every(fn(Frame $frame) => $frame->isComplete());
    }
    /**
     * @return int Aktualna suma punktów
     */
    function getScore() : int {
        return $this->frames
            ->map(fn(Frame $frame) => $frame->getScore())
            ->sum();
    }
    /**
     * @return ?Frame Najnowsza ramka gry
     */
    function getCurrentFrame() : ?Frame {
        return $this->frames->last();
    }
    /**
     * @return ?Roll Najnowszy wykonany w grze ruch
     */
    function getCurrentRoll() : ?Roll {
        return $this->getCurrentFrame()->moves->last();
    }
}