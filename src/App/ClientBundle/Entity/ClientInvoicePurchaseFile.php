<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientInvoicePurchaseFile
 *
 * @ORM\Table(name="client_invoice_purchase_file")
 * @ORM\Entity
 */
class ClientInvoicePurchaseFile
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
     * @var integer $idInvoicePurchase
     *
     * @ORM\Column(name="id_invoice_purchase", type="integer", nullable=true)
     */
    private $idInvoicePurchase;

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
     * Set idInvoicePurchase
     *
     * @param integer $idInvoicePurchase
     * @return ClientInvoicePurchaseFile
     */
    public function setIdInvoicePurchase($idInvoicePurchase)
    {
        $this->idInvoicePurchase = $idInvoicePurchase;

        return $this;
    }

    /**
     * Get idInvoicePurchase
     *
     * @return integer
     */
    public function getIdInvoicePurchase()
    {
        return $this->idInvoicePurchase;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return ClientInvoicePurchaseFile
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