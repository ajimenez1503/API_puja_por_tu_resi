<?php
// src/AppBundle/Entity/ResponsiblePerson.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class ResponsiblePerson
{

    /**
     * @var string
     */
    private $DNI;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $job_position;

    /**
     * @var \AppBundle\Entity\College
     */
    private $college;


    public function __construct()
    {
    }



    /**
    * @return JSON format of the message
    */
    public function getJSON()
    {
        $output=array(
            'DNI' => $this->getDNI(),
            'email'=>$this->getEmail(),
            'name'=> $this->getName(),
            'job_position'=> $this->getJobPosition(),
        );
        return $output;
    }

    /**
     * Set dNI
     *
     * @param string $dNI
     *
     * @return ResponsiblePerson
     */
    public function setDNI($dNI)
    {
        $this->DNI = $dNI;

        return $this;
    }

    /**
     * Get dNI
     *
     * @return string
     */
    public function getDNI()
    {
        return $this->DNI;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return ResponsiblePerson
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ResponsiblePerson
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set jobPosition
     *
     * @param string $jobPosition
     *
     * @return ResponsiblePerson
     */
    public function setJobPosition($jobPosition)
    {
        $this->job_position = $jobPosition;

        return $this;
    }

    /**
     * Get jobPosition
     *
     * @return string
     */
    public function getJobPosition()
    {
        return $this->job_position;
    }

    /**
     * Set college
     *
     * @param \AppBundle\Entity\College $college
     *
     * @return ResponsiblePerson
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
}
