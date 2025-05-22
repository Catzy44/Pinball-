<?php
class Roll {
    public int $knockedDownPins = 0;
    function __construct(int $knockedDownPins) {
        $this->knockedDownPins = $knockedDownPins;
    }
}