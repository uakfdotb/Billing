<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientRecurring
 *
 * @ORM\Table(name="client_recurring")
 * @ORM\Entity
 */
class ClientRecurring
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
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var integer $idSchedule
     *
     * @ORM\Column(name="id_schedule", type="integer", nullable=false)
     */
    private $idSchedule;

    /**
     * @var float $discount
     *
     * @ORM\Column(name="discount", type="decimal", nullable=false)
     */
    private $discount;

    /**
     * @var float $tax
     *
     * @ORM\Column(name="tax", type="integer", nullable=false)
     */
    private $tax;

    /**
     * @var \DateTime $nextDue
     *
     * @ORM\Column(name="next_due", type="date", nullable=false)
     */
    private $nextDue;

    /**
     * @var string $notes
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var boolean $isInvoiced
     *
     * @ORM\Column(name="is_invoiced", type="boolean", nullable=true)
     */
    private $isInvoiced = false;

    /**
     * @ORM\Column(name="reminders", type="boolean", nullable=true)
     */
    private $reminders = 1;

    /**
     * @ORM\Column(name="overdue_notices", type="boolean", nullable=true)
     */
    private $overdueNotices = 1;

    /**
     * @var integer $idProduct
     *
     * @ORM\Column(name="id_product", type="integer", nullable=true)
     */
    private $idProduct;

    /**
     * @param int $idProduct
     */
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;
    }

    /**
     * @return int
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
     * @return ClientRecurring
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
     * @return ClientRecurring
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
     * Set idSchedule
     *
     * @param integer $idSchedule
     * @return ClientRecurring
     */
    public function setIdSchedule($idSchedule)
    {
        $this->idSchedule = $idSchedule;

        return $this;
    }

    /**
     * Get idSchedule
     *
     * @return integer
     */
    public function getIdSchedule()
    {
        return $this->idSchedule;
    }

    /**
     * Set discount
     *
     * @param float $discount
     * @return ClientRecurring
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
     * @return ClientRecurring
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
     * Set nextDue
     *
     * @param \DateTime $nextDue
     * @return ClientRecurring
     */
    public function setNextDue($nextDue)
    {
        $this->nextDue = $nextDue;

        return $this;
    }

    /**
     * Get nextDue
     *
     * @return \DateTime
     */
    public function getNextDue()
    {
        return $this->nextDue;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return ClientRecurring
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
     * Set isInvoiced
     *
     * @param boolean $isInvoiced
     * @return ClientRecurring
     */
    public function setIsInvoiced($isInvoiced)
    {
        $this->isInvoiced = $isInvoiced;

        return $this;
    }

    /**
     * Get isInvoiced
     *
     * @return boolean
     */
    public function getIsInvoiced()
    {
        return $this->isInvoiced;
    }
}