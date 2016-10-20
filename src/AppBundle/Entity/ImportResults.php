<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImportResults
 */
class ImportResults
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $totalCount;

    /**
     * @var integer
     */
    private $newCount;

    /**
     * @var string
     */
    private $updatedCount;

    /**
     * @var \DateTime
     */
    private $datetime;

    public function __construct()
    {
        $this->datetime = new \DateTime();
        $this->newCount = 0;
        $this->updatedCount = 0;
        $this->totalCount = 0;
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
     * Set totalCount
     *
     * @param integer $totalCount
     * @return ImportResults
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;

        return $this;
    }

    /**
     * Get totalCount
     *
     * @return integer 
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * Set newCount
     *
     * @param integer $newCount
     * @return ImportResults
     */
    public function setNewCount($newCount)
    {
        $this->newCount = $newCount;

        return $this;
    }

    /**
     * Get newCount
     *
     * @return integer 
     */
    public function getNewCount()
    {
        return $this->newCount;
    }

    /**
     * Set updatedCount
     *
     * @param string $updatedCount
     * @return ImportResults
     */
    public function setUpdatedCount($updatedCount)
    {
        $this->updatedCount = $updatedCount;

        return $this;
    }

    /**
     * Get updatedCount
     *
     * @return string 
     */
    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return ImportResults
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime 
     */
    public function getDatetime()
    {
        return $this->datetime;
    }
}
