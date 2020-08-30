<?php

namespace jimchen\ranking;

class Item
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $member;

    /**
     * @var string
     */
    private $score;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * Item constructor.
     * @param string $id
     * @param string $member
     * @param string $score
     * @param string $createdAt
     */
    public function __construct($id, $member, $score, $createdAt)
    {
        $this->id = $id;
        $this->member = $member;
        $this->score = $score;
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @param string $member
     */
    public function setMember($member)
    {
        $this->member = $member;
    }

    /**
     * @return string
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param string $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
