<?php
// src/AppBundle/Entity/Incidence.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class Incidence
{
    private $id;
    private $status;
    private $description;
    private $file_name;
    private $date;
    //private $college;
    private $student;



    public function __construct()
    {
        $this->status = "OPEN";
        $this->file_name = NULL;
        $this->date=date_create('now');
        //TODO time now
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
     * Set status
     *
     * @param string $status
     *
     * @return Incidence
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Incidence
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return Incidence
     */
    public function setFileName($fileName)
    {
        $this->file_name = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
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
     * Set description,file_name,student
     *
      * @param string $status
     * @param \AppBundle\Entity\Student $student
     *
     * @return Incidence
     */
    public function set($description,$file_name,\AppBundle\Entity\Student $student = null)
    {
        $this->description = $description;
        $this->file_name =  $file_name;
        $this->student = $student;

        return $this;
    }

    public function getJSON()
    {
        $output=array(
            'id'=>$this->getId(),
            'status'=>$this->getStatus(),
            'description' => $this->getDescription(),
            'file_name'=>$this->getFileName(),
            'date'=>$this->getDate(),
        );
        return $output;
    }
}
