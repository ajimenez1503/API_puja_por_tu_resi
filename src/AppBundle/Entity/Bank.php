<?php
// src/AppBundle/Entity/Bank.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class Bank
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $IBAN;

    /**
     * @var string
     */
    private $SWIFT;

    /**
     * @var string
     */
    private $account_holder;

    /**
     * @var boolean
     */
    private $activate;

    /**
     * @var \AppBundle\Entity\College
     */
    private $college;


    public function __construct()
    {
        $this->activate=false;
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
     * Set iBAN
     *
     * @param string $iBAN
     *
     * @return Bank
     */
    public function setIBAN($iBAN)
    {
        $this->IBAN = $iBAN;

        return $this;
    }

    /**
     * Get iBAN
     *
     * @return string
     */
    public function getIBAN()
    {
        return $this->IBAN;
    }

    /**
     * Set sWIFT
     *
     * @param string $sWIFT
     *
     * @return Bank
     */
    public function setSWIFT($sWIFT)
    {
        $this->SWIFT = $sWIFT;

        return $this;
    }

    /**
     * Get sWIFT
     *
     * @return string
     */
    public function getSWIFT()
    {
        return $this->SWIFT;
    }

    /**
     * Set countHolder
     *
     * @param string $countHolder
     *
     * @return Bank
     */
    public function setAccountHolder($accountHolder)
    {
        $this->account_holder = $accountHolder;

        return $this;
    }

    /**
     * Get countHolder
     *
     * @return string
     */
    public function getAccountHolder()
    {
        return $this->account_holder;
    }

    /**
     * Set activate
     *
     * @param boolean $activate
     *
     * @return Bank
     */
    public function setActivate($activate)
    {
        $this->activate = $activate;

        return $this;
    }

    /**
     * Get activate
     *
     * @return boolean
     */
    public function getActivate()
    {
        return $this->activate;
    }

    /**
     * Set college
     *
     * @param \AppBundle\Entity\College $college
     *
     * @return Bank
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
