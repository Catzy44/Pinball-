<?php

class GameBonus {
    public GameFrame $frame;
    public int $moves;

    function __construct(GameFrame $frame, int $moves) {
        $this->frame = $frame;
        $this->moves = $moves;
    }
}