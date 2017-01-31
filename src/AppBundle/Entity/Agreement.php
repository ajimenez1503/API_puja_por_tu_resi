<?php
// src/AppBundle/Entity/Agreement.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class Agreement
{
    private $id;
    private $point;
    private $room;
    private $student;
    private $price;
    private $date_start_school;
    private $date_end_school;
    private $file_agreement;
    private $file_agreement_signed;


    public function __construct()
    {
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Agreement
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set dateStartSchool
     *
     * @param \DateTime $dateStartSchool
     *
     * @return Agreement
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
     * @return Agreement
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

    /**
     * Set fileAgreement
     *
     * @param string $fileAgreement
     *
     * @return Agreement
     */
    public function setFileAgreement($fileAgreement)
    {
        $this->file_agreement = $fileAgreement;

        return $this;
    }

    /**
     * Get fileAgreement
     *
     * @return string
     */
    public function getFileAgreement()
    {
        return $this->file_agreement;
    }

    /**
     * Set fileAgreementSigned
     *
     * @param string $fileAgreementSigned
     *
     * @return Agreement
     */
    public function setFileAgreementSigned($fileAgreementSigned)
    {
        $this->file_agreement_signed = $fileAgreementSigned;

        return $this;
    }

    /**
     * Get fileAgreementSigned
     *
     * @return string
     */
    public function getFileAgreementSigned()
    {
        return $this->file_agreement_signed;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Agreement
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
     * Set room
     *
     * @param \AppBundle\Entity\Room $room
     *
     * @return Agreement
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
