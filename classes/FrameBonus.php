<?php

class FrameBonus {
    private Frame $frame;
    private int $bonusRollsRemaining;

    /**
     * @param Frame $frame Do której ramki gry doliczać punkty
     * @param int $moves Przez ile rzutów doliczać punkty jako bonus
     */
    function __construct(Frame $frame, int $moves) {
        $this->frame = $frame;
        $this->bonusRollsRemaining = $moves;
    }

    function useOneBobusRoll(): void {
        $this->bonusRollsRemaining--;
    }

    function remainingBonusRolls(): int {
        return $this->bonusRollsRemaining;
    }

    function getFrame() : Frame {
        return $this->frame;
    }
}