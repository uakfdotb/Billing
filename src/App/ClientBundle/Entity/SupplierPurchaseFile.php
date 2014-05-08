<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\SupplierPurchaseFile
 *
 * @ORM\Table(name="supplier_purchase_file")
 * @ORM\Entity
 */
class SupplierPurchaseFile
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
     * @var integer $idPurchase
     *
     * @ORM\Column(name="id_purchase", type="integer", nullable=true)
     */
    private $idPurchase;

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
     * Set idPurchase
     *
     * @param integer $idPurchase
     * @return SupplierPurchaseFile
     */
    public function setIdPurchase($idPurchase)
    {
        $this->idPurchase = $idPurchase;

        return $this;
    }

    /**
     * Get idPurchase
     *
     * @return integer
     */
    public function getIdPurchase()
    {
        return $this->idPurchase;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return SupplierPurchaseFile
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