<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientPayment
 *
 * @ORM\Table(name="client_payment")
 * @ORM\Entity
 */
class ClientPayment
{

    const STATUS_COMPLETED = 1,
        STATUS_PROCESSING = 2
    ;

    public static function validStatuses()
    {
        return [
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_PROCESSING => 'Processing'
        ];
    }

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $idGateway
     *
     * @ORM\Column(name="id_gateway", type="integer", nullable=false)
     */
    private $idGateway;

    /**
     * @var string $transaction
     *
     * @ORM\Column(name="transaction", type="string", length=255, nullable=true)
     */
    private $transaction;

    /**
     * @var \DateTime $payDate
     *
     * @ORM\Column(name="pay_date", type="datetime", nullable=false)
     */
    private $payDate;

    /**
     * @var float $amount
     *
     * @ORM\Column(name="amount", type="decimal", nullable=false, precision=11, scale=2)
     */
    private $amount;

    /**
     * @var float $fee
     *
     * @ORM\Column(name="fee", type="decimal", nullable=false, precision=11, scale=2)
     */
    private $fee;

    /**
     * @param float $fee
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
    }

    /**
     * @return float
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @var integer $idEstimate
     *
     * @ORM\Column(name="id_estimate", type="integer", nullable=true)
     */
    private $idEstimate;

    /**
     * @var integer $idType
     *
     * @ORM\Column(name="id_type", type="integer", nullable=true)
     */
    private $idType;

    /**
     * @var integer $idAccountTransaction
     *
     * @ORM\Column(name="id_account_transaction", type="integer", nullable=true)
     */
    private $idAccountTransaction;

    /**
     * @var integer $idInvoice
     *
     * @ORM\Column(name="id_invoice", type="integer", nullable=true)
     */
    private $idInvoice;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = self::STATUS_COMPLETED;

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
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
     * Set idGateway
     *
     * @param integer $idGateway
     * @return ClientPayment
     */
    public function setIdGateway($idGateway)
    {
        $this->idGateway = $idGateway;

        return $this;
    }

    /**
     * Get idGateway
     *
     * @return integer
     */
    public function getIdGateway()
    {
        return $this->idGateway;
    }

    /**
     * Set transaction
     *
     * @param string $transaction
     * @return ClientPayment
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get transaction
     *
     * @return string
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Set payDate
     *
     * @param \DateTime $payDate
     * @return ClientPayment
     */
    public function setPayDate($payDate)
    {
        $this->payDate = $payDate;

        return $this;
    }

    /**
     * Get payDate
     *
     * @return \DateTime
     */
    public function getPayDate()
    {
        return $this->payDate;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return ClientPayment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set idEstimate
     *
     * @param integer $idEstimate
     * @return ClientPayment
     */
    public function setIdEstimate($idEstimate)
    {
        $this->idEstimate = $idEstimate;

        return $this;
    }

    /**
     * Get idEstimate
     *
     * @return integer
     */
    public function getIdEstimate()
    {
        return $this->idEstimate;
    }

    /**
     * Set idType
     *
     * @param integer $idType
     * @return ClientPayment
     */
    public function setIdType($idType)
    {
        $this->idType = $idType;

        return $this;
    }

    /**
     * Get idType
     *
     * @return integer
     */
    public function getIdType()
    {
        return $this->idType;
    }

    /**
     * Set idAccountTransaction
     *
     * @param integer $idAccountTransaction
     * @return ClientPayment
     */
    public function setIdAccountTransaction($idAccountTransaction)
    {
        $this->idAccountTransaction = $idAccountTransaction;

        return $this;
    }

    /**
     * Get idAccountTransaction
     *
     * @return integer
     */
    public function getIdAccountTransaction()
    {
        return $this->idAccountTransaction;
    }

    /**
     * Set idInvoice
     *
     * @param integer $idInvoice
     * @return ClientPayment
     */
    public function setIdInvoice($idInvoice)
    {
        $this->idInvoice = $idInvoice;

        return $this;
    }

    /**
     * Get idInvoice
     *
     * @return integer
     */
    public function getIdInvoice()
    {
        return $this->idInvoice;
    }
}