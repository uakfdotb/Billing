<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientEstimate
 *
 * @ORM\Table(name="client_estimate")
 * @ORM\Entity
 */
class ClientEstimate
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
     * @var integer $idClient
     *
     * @ORM\Column(name="id_client", type="integer", nullable=false)
     */
    private $idClient;

    /**
     * @var string $subject
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=false)
     */
    private $subject;

    /**
     * @var \DateTime $issueDate
     *
     * @ORM\Column(name="issue_date", type="date", nullable=true)
     */
    private $issueDate;

    /**
     * @var \DateTime $dueDate
     *
     * @ORM\Column(name="due_date", type="date", nullable=true)
     */
    private $dueDate;

    /**
     * @var string $hash
     *
     * @ORM\Column(name="hash", type="string", length=12, nullable=true)
     */
    private $hash;

    /**
     * @var float $discount
     *
     * @ORM\Column(name="discount", type="decimal", nullable=true)
     */
    private $discount;

    /**
     * @var float $tax
     *
     * @ORM\Column(name="tax", type="integer", nullable=true)
     */
    private $tax;

    /**
     * @var string $notes
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var integer $invoiceStatus
     *
     * @ORM\Column(name="invoice_status", type="integer", nullable=true)
     */
    private $invoiceStatus;

    /**
     * @var float $totalAmount
     *
     * @ORM\Column(name="total_amount", type="decimal", nullable=true)
     */
    private $totalAmount;

    /**
     * @var float $totalPayment
     *
     * @ORM\Column(name="total_payment", type="decimal", nullable=true)
     */
    private $totalPayment;

    /**
     * @var string $number
     *
     * @ORM\Column(name="number", type="string", length=50, nullable=true)
     */
    private $number;

    /**
     * @var boolean $invoiced
     *
     * @ORM\Column(name="invoiced", type="boolean", nullable=true)
     */
    private $invoiced;


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
     * Set idClient
     *
     * @param integer $idClient
     * @return ClientEstimate
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
     * Set subject
     *
     * @param string $subject
     * @return ClientEstimate
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set issueDate
     *
     * @param \DateTime $issueDate
     * @return ClientEstimate
     */
    public function setIssueDate($issueDate)
    {
        $this->issueDate = $issueDate;

        return $this;
    }

    /**
     * Get issueDate
     *
     * @return \DateTime
     */
    public function getIssueDate()
    {
        return $this->issueDate;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     * @return ClientEstimate
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return ClientEstimate
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set discount
     *
     * @param float $discount
     * @return ClientEstimate
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set tax
     *
     * @param float $tax
     * @return ClientEstimate
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax
     *
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return ClientEstimate
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return ClientEstimate
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
     * Set invoiceStatus
     *
     * @param integer $invoiceStatus
     * @return ClientEstimate
     */
    public function setInvoiceStatus($invoiceStatus)
    {
        $this->invoiceStatus = $invoiceStatus;

        return $this;
    }

    /**
     * Get invoiceStatus
     *
     * @return integer
     */
    public function getInvoiceStatus()
    {
        return $this->invoiceStatus;
    }

    /**
     * Set totalAmount
     *
     * @param float $totalAmount
     * @return ClientEstimate
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return float
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set totalPayment
     *
     * @param float $totalPayment
     * @return ClientEstimate
     */
    public function setTotalPayment($totalPayment)
    {
        $this->totalPayment = $totalPayment;

        return $this;
    }

    /**
     * Get totalPayment
     *
     * @return float
     */
    public function getTotalPayment()
    {
        return $this->totalPayment;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return ClientEstimate
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set invoiced
     *
     * @param boolean $invoiced
     * @return ClientEstimate
     */
    public function setInvoiced($invoiced)
    {
        $this->invoiced = $invoiced;

        return $this;
    }

    /**
     * Get invoiced
     *
     * @return boolean
     */
    public function getInvoiced()
    {
        return $this->invoiced;
    }
}