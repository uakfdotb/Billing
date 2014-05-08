<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ProductOrder
 *
 * @ORM\Table(name="product_order")
 * @ORM\Entity
 */
class ProductOrder
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
     * @var integer $idProduct
     *
     * @ORM\Column(name="id_product", type="integer", nullable=true)
     */
    private $idProduct;

    /**
     * @var integer $idClient
     *
     * @ORM\Column(name="id_client", type="integer", nullable=true)
     */
    private $idClient;

    /**
     * @var \DateTime $timestamp
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=true)
     */
    private $timestamp;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var string $orderNumber
     *
     * @ORM\Column(name="order_number", type="string", length=255, nullable=true)
     */
    private $orderNumber;

    /**
     * @var integer $clientProduct
     *
     * @ORM\Column(name="client_product", type="integer", nullable=true)
     */
    private $clientProduct;

    /**
     * @var string $maxmindData
     *
     * @ORM\Column(name="maxmind_data", type="array", nullable=true)
     */
    private $maxmindData;

    /**
     * @var string $ipAddress
     *
     * @ORM\Column(name="ip_address", type="string", length=255, nullable=true)
     */
    private $ipAddress;

    /**
     * @var integer $idInvoice
     *
     * @ORM\Column(name="id_invoice", type="integer", nullable=true)
     */
    private $idInvoice;

    /**
     * @param int $idInvoice
     */
    public function setIdInvoice($idInvoice)
    {
        $this->idInvoice = $idInvoice;
    }

    /**
     * @return int
     */
    public function getIdInvoice()
    {
        return $this->idInvoice;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
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
     * Set idProduct
     *
     * @param integer $idProduct
     * @return ProductOrder
     */
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    /**
     * Get idProduct
     *
     * @return integer
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * Set idClient
     *
     * @param integer $idClient
     * @return ProductOrder
     */
    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;

        return $this;
    }

    /**
     * Get idClient
     *
     * @return integer
     */
    public function getIdClient()
    {
        return $this->idClient;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return ProductOrder
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return ProductOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set orderNumber
     *
     * @param string $orderNumber
     * @return ProductOrder
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Get orderNumber
     *
     * @return string
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @param int $clientProduct
     */
    public function setClientProduct($clientProduct)
    {
        $this->clientProduct = $clientProduct;
    }

    /**
     * @return int
     */
    public function getClientProduct()
    {
        return $this->clientProduct;
    }

    /**
     * Set maxmindData
     *
     * @param array $maxmindData
     * @return ProductOrder
     */
    public function setMaxmindData($maxmindData)
    {
        $this->maxmindData = $maxmindData;

        return $this;
    }

    /**
     * Get maxmindData
     *
     * @return array
     */
    public function getMaxmindData()
    {
        return $this->maxmindData;
    }
}