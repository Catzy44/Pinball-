<?php

class GameBonus {
    public GameFrame $frame;
    public int $moves;

    /**
     * @param GameFrame $frame Do której ramki gry doliczać punkty
     * @param int $moves Przez ile rzutów doliczać punkty jako bonus
     */
    function __construct(GameFrame $frame, int $moves) {
        $this->frame = $frame;
        $this->moves = $moves;
    }
}