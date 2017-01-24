<?php
// src/AppBundle/Entity/Bid.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class Bid
{
    private $id;
    private $point;
    private $room;
    private $student;

    public function __construct()
    {
    }
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Incidence
     */
    public function setStudent(\AppBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \AppBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }


    /**
    * @return JSON format of the message
    */
    public function getJSON()
    {
        $output=array(
            'id'=>$this->getId(),
            'point' => $this->getPoint(),
            'room_id'=>$this->getRoom()->getId(),
            'student_username'=> $this->getStudent()->getUsername(),
        );
        return $output;
    }


    /**
     * Set point
     *
     * @param integer $point
     *
     * @return Bid
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return integer
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set room
     *
     * @param \AppBundle\Entity\Room $room
     *
     * @return Bid
     */
    public function setRoom(\AppBundle\Entity\Room $room = null)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return \AppBundle\Entity\Room
     */
    public function getRoom()
    {
        return $this->room;
    }
}
