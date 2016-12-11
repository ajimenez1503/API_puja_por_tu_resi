<?php
// src/AppBundle/Entity/Message.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class Message
{
    private $id;
    private $open;
    private $message;
    private $file_attached;
    private $date;
    private $college;
    private $student;



    public function __construct()
    {
        $this->open = 0;
        $this->date=date_create('now');
        $this->college = null;
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




    public function getJSON()
    {
        $output=array(
            'id'=>$this->getId(),
            'open'=>$this->getOpen(),
            'message' => $this->getMessage(),
            'file_attached'=>$this->getFileAttached(),
            'date'=>$this->getDate(),
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
     * Set open
     *
     * @param boolean $open
     *
     * @return Message
     */
    public function setOpen($open)
    {
        $this->open = $open;

        return $this;
    }

    /**
     * Get open
     *
     * @return boolean
     */
    public function getOpen()
    {
        return $this->open;
    }
}
