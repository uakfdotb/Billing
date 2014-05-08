<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientEstimatePurchaseFile
 *
 * @ORM\Table(name="client_estimate_purchase_file")
 * @ORM\Entity
 */
class ClientEstimatePurchaseFile
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $idEstimatePurchase
     *
     * @ORM\Column(name="id_estimate_purchase", type="integer", nullable=true)
     */
    private $idEstimatePurchase;

    /**
     * @var string $file
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @var number $fileSize
     *
     * @ORM\Column(name="size", type="decimal", precision=5, scale=3)
     */
    private $fileSize;

    /**
     * @param number $fileSize
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return number
     */
    public function getFileSize()
    {
        return $this->fileSize;
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
     * Set idEstimatePurchase
     *
     * @param integer $idEstimatePurchase
     * @return ClientEstimatePurchaseFile
     */
    public function setIdEstimatePurchase($idEstimatePurchase)
    {
        $this->idEstimatePurchase = $idEstimatePurchase;

        return $this;
    }

    /**
     * Get idEstimatePurchase
     *
     * @return integer
     */
    public function getIdEstimatePurchase()
    {
        return $this->idEstimatePurchase;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return ClientEstimatePurchaseFile
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
}