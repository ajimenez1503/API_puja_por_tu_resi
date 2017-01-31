<?php
// src/AppBundle/Entity/Room.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class Room
{
    private $id;
    private $name;
    private $price;
    private $date_start_school;
    private $date_end_school;
    private $date_start_bid;
    private $date_end_bid;
    private $floor;
    private $size;
    private $picture1;
    private $picture2;
    private $picture3;
    private $tv;
    private $bath;
    private $desk;
    private $wardrove;
    private $college;
    private $bids;
    private $agreements;


    public function __construct()
    {
        $this->bids = new ArrayCollection();
        $this->agreements = new ArrayCollection();
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
    * @return JSON format of the room
    */
    public function getJSON()
    {
        $output=array(
            'id'=>$this->getId(),
            'name' => $this->getName(),
            'price'=>$this->getPrice(),
            'date_start_school'=>$this->getDateStartSchool(),
            'date_end_school'=>$this->getDateEndSchool(),
            'date_start_bid'=>$this->getDateStartBid(),
            'date_end_bid'=>$this->getDateEndBid(),
            'floor'=>$this->getFloor(),
            'size' => $this->getSize(),
            'picture1'=>$this->getPicture1(),
            'picture2'=>$this->getPicture2(),
            'picture3'=>$this->getPicture3(),
            'tv'=>$this->getTv(),
            'bath'=>$this->getBath(),
            'desk'=>$this->getDesk(),
            'wardrove'=>$this->getWardrove(),
            'college'=>$this->getCollege()->getUsername(),
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
     * Set name
     *
     * @param string $name
     *
     * @return Room
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
     * Set price
     *
     * @param float $price
     *
     * @return Room
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
     * @return Room
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
     * @return Room
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
     * Set dateStartBid
     *
     * @param \DateTime $dateStartBid
     *
     * @return Room
     */
    public function setDateStartBid($dateStartBid)
    {
        $this->date_start_bid = $dateStartBid;

        return $this;
    }

    /**
     * Get dateStartBid
     *
     * @return \DateTime
     */
    public function getDateStartBid()
    {
        return $this->date_start_bid;
    }

    /**
     * Set dateEndBid
     *
     * @param \DateTime $dateEndBid
     *
     * @return Room
     */
    public function setDateEndBid($dateEndBid)
    {
        $this->date_end_bid = $dateEndBid;

        return $this;
    }

    /**
     * Get dateEndBid
     *
     * @return \DateTime
     */
    public function getDateEndBid()
    {
        return $this->date_end_bid;
    }

    /**
     * Set floor
     *
     * @param integer $floor
     *
     * @return Room
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * Get floor
     *
     * @return integer
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return Room
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }


    /**
     * Set picture1
     *
     * @param string $picture1
     *
     * @return Room
     */
    public function setPicture1($picture1)
    {
        $this->picture1 = $picture1;

        return $this;
    }

    /**
     * Get picture1
     *
     * @return string
     */
    public function getPicture1()
    {
        return $this->picture1;
    }

    /**
     * Set picture2
     *
     * @param string $picture2
     *
     * @return Room
     */
    public function setPicture2($picture2)
    {
        $this->picture2 = $picture2;

        return $this;
    }

    /**
     * Get picture2
     *
     * @return string
     */
    public function getPicture2()
    {
        return $this->picture2;
    }

    /**
     * Set picture3
     *
     * @param string $picture3
     *
     * @return Room
     */
    public function setPicture3($picture3)
    {
        $this->picture3 = $picture3;

        return $this;
    }

    /**
     * Get picture3
     *
     * @return string
     */
    public function getPicture3()
    {
        return $this->picture3;
    }

    /**
     * Set tv
     *
     * @param boolean $tv
     *
     * @return Room
     */
    public function setTv($tv)
    {
        $this->tv = $tv;

        return $this;
    }

    /**
     * Get tv
     *
     * @return boolean
     */
    public function getTv()
    {
        return $this->tv;
    }

    /**
     * Set bath
     *
     * @param boolean $bath
     *
     * @return Room
     */
    public function setBath($bath)
    {
        $this->bath = $bath;

        return $this;
    }

    /**
     * Get bath
     *
     * @return boolean
     */
    public function getBath()
    {
        return $this->bath;
    }

    /**
     * Set desk
     *
     * @param boolean $desk
     *
     * @return Room
     */
    public function setDesk($desk)
    {
        $this->desk = $desk;

        return $this;
    }

    /**
     * Get desk
     *
     * @return boolean
     */
    public function getDesk()
    {
        return $this->desk;
    }

    /**
     * Set wardrove
     *
     * @param boolean $wardrove
     *
     * @return Room
     */
    public function setWardrove($wardrove)
    {
        $this->wardrove = $wardrove;

        return $this;
    }

    /**
     * Get wardrove
     *
     * @return boolean
     */
    public function getWardrove()
    {
        return $this->wardrove;
    }

    /**
     * Add bid
     *
     * @param \AppBundle\Entity\Bid $bid
     *
     * @return Room
     */
    public function addBid(\AppBundle\Entity\Bid $bid)
    {
        $this->bids[] = $bid;

        return $this;
    }

    /**
     * Remove bid
     *
     * @param \AppBundle\Entity\Bid $bid
     */
    public function removeBid(\AppBundle\Entity\Bid $bid)
    {
        $this->bids->removeElement($bid);
    }

    /**
     * Get bids
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBids()
    {
        return $this->bids;
    }


    /**
     * Add agreement
     *
     * @param \AppBundle\Entity\Agreement $agreement
     *
     * @return Room
     */
    public function addAgreement(\AppBundle\Entity\Agreement $agreement)
    {
        $this->agreements[] = $agreement;

        return $this;
    }

    /**
     * Remove agreement
     *
     * @param \AppBundle\Entity\Agreement $agreement
     */
    public function removeAgreement(\AppBundle\Entity\Agreement $agreement)
    {
        $this->agreements->removeElement($agreement);
    }

    /**
     * Get agreements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgreements()
    {
        return $this->agreements;
    }
}
