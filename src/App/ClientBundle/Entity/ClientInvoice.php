<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientInvoice
 *
 * @ORM\Table(name="client_invoice")
 * @ORM\Entity
 */
class ClientInvoice
{
    const STATUS_PAID = 1,
        STATUS_UNPAID = 2,
        STATUS_OVERDUE = 3,
        STATUS_PROFORMA = 4,
        STATUS_WRITTEN_OFF = 5
    ;

    public static function validStatuses()
    {
        return [
            self::STATUS_PAID => 'Paid',
            self::STATUS_UNPAID => 'Unpaid',
            self::STATUS_OVERDUE => 'Overdue',
            self::STATUS_PROFORMA => 'Proforma',
            self::STATUS_WRITTEN_OFF => 'Written Off'
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
    private $status = self::STATUS_UNPAID;

    /**
     * @var float $totalAmount
     *
     * @ORM\Column(name="total_amount", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $totalAmount;

    /**
     * @var float $totalPayment
     *
     * @ORM\Column(name="total_payment", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $totalPayment;

    /**
     * @var string $number
     *
     * @ORM\Column(name="number", type="string", length=50, nullable=true)
     */
    private $number;

    /**
     * @var string $invoiceAccessToken
     *
     * @ORM\Column(name="access_token", type="string", length=255, nullable=true)
     */
    private $invoiceAccessToken;

    /**
     * @ORM\Column(name="reminder_sent_at", type="datetime", nullable=true)
     */
    private $reminderSentAt;

    /**
     * @ORM\Column(name="viewed_by_client", type="boolean", nullable=true)
     */
    private $viewedByClient = 0;

    /**
     * @ORM\Column(name="reminders", type="boolean", nullable=true)
     */
    private $reminders = 1;

    /**
     * @ORM\Column(name="overdue_notices", type="boolean", nullable=true)
     */
    private $overdueNotices = 1;

    /**
     * @var integer $idClientProduct
     *
     * @ORM\Column(name="id_client_product", type="integer", nullable=true)
     */
    private $idClientProduct;

    /**
     * @param mixed $idRecurring
     */
    public function setIdRecurring($idRecurring)
    {
        $this->idRecurring = $idRecurring;
    }

    /**
     * @return mixed
     */
    public function getIdRecurring()
    {
        return $this->idRecurring;
    }

    /**
     * @ORM\Column(name="id_recurring", type="integer", nullable=true)
     */
    private $idRecurring;

    /**
     * @ORM\Column(name="id_product", type="integer", nullable=true)
     */
    private $idProduct;

    /**
     * @param mixed $idProduct
     */
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;
    }

    /**
     * @return mixed
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * @param mixed $overdueNotices
     */
    public function setOverdueNotices($overdueNotices)
    {
        $this->overdueNotices = $overdueNotices;
    }

    /**
     * @return mixed
     */
    public function getOverdueNotices()
    {
        return $this->overdueNotices;
    }

    /**
     * @param mixed $reminders
     */
    public function setReminders($reminders)
    {
        $this->reminders = $reminders;
    }

    /**
     * @return mixed
     */
    public function getReminders()
    {
        return $this->reminders;
    }

    /**
     * @param mixed $viewedByClient
     */
    public function setViewedByClient($viewedByClient)
    {
        $this->viewedByClient = $viewedByClient;
    }

    /**
     * @return mixed
     */
    public function getViewedByClient()
    {
        return $this->viewedByClient;
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
     * @param mixed $reminderSentAt
     * @return $this
     */
    public function setReminderSentAt($reminderSentAt)
    {
        $this->reminderSentAt = $reminderSentAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReminderSentAt()
    {
        return $this->reminderSentAt;
    }

    /**
     * Set idClient
     *
     * @param integer $idClient
     * @return ClientInvoice
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
     * @return ClientInvoice
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
     * @return ClientInvoice
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
     * @return ClientInvoice
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
     * @return ClientInvoice
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
     * @return ClientInvoice
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
     * @return ClientInvoice
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
     * @return ClientInvoice
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
     * @return ClientInvoice
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
     * Set totalAmount
     *
     * @param float $totalAmount
     * @return ClientInvoice
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
     * @return ClientInvoice
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
     * @return ClientInvoice
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
     * @param string $invoiceAccessToken
     * @return $this
     */
    public function setInvoiceAccessToken($invoiceAccessToken)
    {
        $this->invoiceAccessToken = $invoiceAccessToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getInvoiceAccessToken()
    {
        return $this->invoiceAccessToken;
    }

    /**
     * @return bool
     */
    public function isPaid()
    {
        return $this->status == self::STATUS_PAID;
    }

    /**
     * @return bool
     */
    public function isProforma()
    {
        return $this->status == self::STATUS_PROFORMA;
    }

    /**
     * @return bool
     */
    public function isOverdue()
    {
        return $this->status == self::STATUS_OVERDUE;
    }

    /**
     * @param int $idClientProduct
     */
    public function setIdClientProduct($idClientProduct)
    {
        $this->idClientProduct = $idClientProduct;
    }

    /**
     * @return int
     */
    public function getIdClientProduct()
    {
        return $this->idClientProduct;
    }

}
