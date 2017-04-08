<?php
// src/AppBundle/Entity/Bid.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class Bid
{
    private $id;
    private $room;
    private $student;
    /**
     * @var \DateTime
     */
    private $date_start_school;

    /**
     * @var \DateTime
     */
    private $date_end_school;

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
            'point' => $this->getStudent()->get_point(),
            'student_name' => $this->getStudent()->getName(),
            'room_id'=>$this->getRoom()->getId(),
            'student_username'=> $this->getStudent()->getUsername(),
            'date_start_school'=>$this->getDateStartSchool(),
            'date_end_school'=>$this->getDateEndSchool(),
        );
        return $output;
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


    /**
     * Set dateStartSchool
     *
     * @param \DateTime $dateStartSchool
     *
     * @return Bid
     */
    public function setDateStartSchool($dateStartSchool)
    {
        $this->date_start_school = $dateStartSchool;

        return $this;
    }

    /**
     * Get dateStartSchool
     *
     * @return \DateTime
     */
    public function getDateStartSchool()
    {
        return $this->date_start_school;
    }

    /**
     * Set dateEndSchool
     *
     * @param \DateTime $dateEndSchool
     *
     * @return Bid
     */
    public function setDateEndSchool($dateEndSchool)
    {
        $this->date_end_school = $dateEndSchool;

        return $this;
    }

    /**
     * Get dateEndSchool
     *
     * @return \DateTime
     */
    public function getDateEndSchool()
    {
        return $this->date_end_school;
    }
}
