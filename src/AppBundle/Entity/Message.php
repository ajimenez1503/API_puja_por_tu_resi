<?php
// src/AppBundle/Entity/Message.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class Message
{
    private $id;
    private $read_by_college;
    private $read_by_student;
    private $message;
    private $file_attached;
    private $date;
    private $college;
    private $student;
    private $sender_type;//STUDENT or COLLEGE



    public function __construct()
    {
        $this->read_by_college = 0;
        $this->read_by_student = 0;
        $this->date=date_create('now');
        $this->college = null;#TODO choose the college of the user
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Incidence
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
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
            'message' => $this->getMessage(),
            'read_by_student'=>$this->getReadByStudent(),
            'read_by_college'=>$this->getReadByCollege(),
            'file_attached'=>$this->getFileAttached(),
            'date'=>$this->getDate(),
            'senderType'=>$this->getSenderType(),
            'college_name'=>$this->getCollege()->getCompanyName(),
            'student_name'=>$this->getStudent()->getName(),
        );
        return $output;
    }

    /**
     * Set college
     *
     * @param \AppBundle\Entity\College $college
     *
     * @return Incidence
     */
    public function setCollege(\AppBundle\Entity\College $college = null)
    {
        $this->college = $college;

        return $this;
    }

    /**
     * Get college
     *
     * @return \AppBundle\Entity\College
     */
    public function getCollege()
    {
        return $this->college;
    }



    /**
     * Set message
     *
     * @param string $message
     *
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set fileAttached
     *
     * @param string $fileAttached
     *
     * @return Message
     */
    public function setFileAttached($fileAttached)
    {
        $this->file_attached = $fileAttached;

        return $this;
    }

    /**
     * Get fileAttached
     *
     * @return string
     */
    public function getFileAttached()
    {
        return $this->file_attached;
    }


    /**
     * Set senderType
     *
     * @param string $senderType
     *
     * @return Message
     */
    public function setSenderType($senderType)
    {
        $this->sender_type = $senderType;

        return $this;
    }

    /**
     * Get senderType
     *
     * @return string
     */
    public function getSenderType()
    {
        return $this->sender_type;
    }

    /**
     * Set readByStudent
     *
     * @param boolean $readByStudent
     *
     * @return Message
     */
    public function setReadByStudent($readByStudent)
    {
        $this->read_by_student = $readByStudent;

        return $this;
    }

    /**
     * Get readByStudent
     *
     * @return boolean
     */
    public function getReadByStudent()
    {
        return $this->read_by_student;
    }

    /**
     * Set readByCollege
     *
     * @param boolean $readByCollege
     *
     * @return Message
     */
    public function setReadByCollege($readByCollege)
    {
        $this->read_by_college = $readByCollege;

        return $this;
    }

    /**
     * Get readByCollege
     *
     * @return boolean
     */
    public function getReadByCollege()
    {
        return $this->read_by_college;
    }
}
