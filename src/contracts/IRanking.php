<?php

namespace jimchen\ranking\contracts;

interface IRanking
{
    /**
     * @param $member
     * @param $score
     * @return mixed
     */
    public function addScore($member, $score);

    /**
     * @param $member
     * @param $score
     * @return mixed
     */
    public function updateScore($member, $score);
}
