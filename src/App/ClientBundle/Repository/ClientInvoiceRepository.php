<?php
namespace App\ClientBundle\Repository;

use App\ClientBundle\Entity\ClientInvoice;
use Doctrine\ORM\EntityRepository;

class ClientInvoiceRepository extends EntityRepository
{
    public function findDueInvoicesFor($days = 7, $reminderNotSent = true)
    {
        $qb = $this->createQueryBuilder('i');

        $qb
            ->where('i.status = :unpaid or i.status = :overdue')
            ->andWhere('i.issueDate < :nDaysBefore')
            ->setParameters([
                'unpaid' => ClientInvoice::STATUS_UNPAID,
                'overdue' => ClientInvoice::STATUS_OVERDUE,
                'nDaysBefore' => new \DateTime('-'.$days.' days')
            ])
        ;

        if ($reminderNotSent) {
            $qb->andWhere('i.reminderSentAt IS NULL');
        }

        return $qb->getQuery()->getResult();
    }
}