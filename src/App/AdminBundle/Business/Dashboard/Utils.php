<?php

namespace App\AdminBundle\Business\Dashboard;

use App\AdminBundle\Business;
use App\ClientBundle\Entity\ClientInvoice;

class Utils
{

    public static function computeRevenue($controller)
    {

        $monthlyTotal = array();

        for ($i = 1; $i <= 12; $i++) {

            $monthlyTotal[$i] = array(

                'month' => date("M", mktime(0, 0, 0, $i, 1, 2011)),

                'value' => 0

            );

        }


        $emConfig = $controller->getDoctrine()->getEntityManager()->getConfiguration();

        $emConfig->addCustomDatetimeFunction('MONTH', 'App\AdminBundle\Business\Dashboard\DoctrineExtMonth');


        // PAY IN

        $query = $controller->getDoctrine()->getEntityManager()->createQueryBuilder();

        $query->select('MONTH(p.payDate) as month, SUM(p.amount) as amount')

            ->from('AppClientBundle:ClientPayment', 'p')

            ->andWhere('p.payDate >= :year')

            ->andWhere('p.amount >= 0')

            ->setParameter('year', date('Y'))

            ->groupBy('month');

        $result = $query->getQuery()->getResult();

        foreach ($result as $r) {

            $monthlyTotal[$r['month']]['payIn'] = $r['amount'];

        }


        // PAY OUT

        $query = $controller->getDoctrine()->getEntityManager()->createQueryBuilder();

        $query->select('MONTH(p.payDate) as month, SUM(p.amount) as amount')

            ->from('AppClientBundle:ClientPayment', 'p')

            ->andWhere('p.payDate >= :year')

            ->andWhere('p.amount < 0')

            ->setParameter('year', date('Y'))

            ->groupBy('month');

        $result = $query->getQuery()->getResult();

        foreach ($result as $r) {

            $monthlyTotal[$r['month']]['payOut'] = -$r['amount'];

        }


        foreach ($monthlyTotal as $m => $v) {

            if (!isset($v['payIn'])) {
                $monthlyTotal[$m]['payIn'] = 0;
            }

            if (!isset($v['payOut'])) {
                $monthlyTotal[$m]['payOut'] = 0;
            }

        }


        return array_values($monthlyTotal);

    }


    public static function getLatestTicketResponses($controller, $numResult)
    {

        $formatter = $controller->get('app_admin.helper.formatter');

        $query = $controller->getDoctrine()->getEntityManager()->createQueryBuilder();

        $query->select('t.id, t.subject, t.status, p.idUser')

            ->from('AppClientBundle:TicketResponse', 'p')

            ->innerJoin('AppClientBundle:Ticket', 't', 'WITH', 'p.idTicket = t.id')

            ->orderBy('p.timestamp', 'DESC')

            ->setFirstResult(0)

            ->setMaxResults($numResult);

        $result = $query->getQuery()->getResult();

        $data = array();

        foreach ($result as $row) {

            $r = array(

                'id'      => $row['id'],

                'url'     => $controller->generateUrl('app_admin_ticket_item_list', array('id' => $row['id'])),

                'subject' => $row['subject'],

                'status'  => $formatter->format($row['status'], 'mapping', 'ticket_status'),

                'user'    => $formatter->format($row['idUser'], 'mapping', 'staff_list')

            );


            if ($r['user'] == '') {

                $r['user'] = 'Unknown';

            }

            $data[] = $r;

        }

        return $data;

    }

    public static function getOverdueInvoices($controller, $numResult)
    {
        $formatter = $controller->get('app_admin.helper.formatter');

        $query = $controller->getDoctrine()->getEntityManager()->createQueryBuilder();
        $query->select('p')
            ->from('AppClientBundle:ClientEstimate', 'p')
            ->andWhere('p.invoiceStatus = ' . ClientInvoice::STATUS_OVERDUE)
            ->orderBy('p.id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults($numResult);

        $result = $query->getQuery()->getResult();

        $data = array();

        foreach ($result as $row) {
            $data[] = array(
                'url'     => $controller->generateUrl('app_admin_estimate_item_list', array('id' => $row->getId())),
                'client'  => $formatter->format($row->getIdClient(), 'mapping', 'client_list'),
                'subject' => $row->getSubject(),
                'amount'  => $formatter->format($row->getTotalAmount(), 'money'),
                'payment' => $formatter->format($row->getTotalPayment(), 'money')
            );
        }

        return $data;
    }

    public static function getSchedules($controller)
    {
        $doctrine  = $controller->get('doctrine');
        $config    = $controller->get('app_admin.helper.common')->getConfig();
        $schedules = array();

        $formatter = $controller->get('app_admin.helper.formatter');

        $estimates = $doctrine->getRepository('AppClientBundle:ClientEstimate')->findAll();
        foreach ($estimates as $estimate) {
            if ($estimate->getDueDate() != null) {
                $event         = array('title' => $estimate->getNumber() . ' (' . $formatter->format($estimate->getStatus(), 'mapping', 'estimate_status') . ')');
                $event['date'] = $estimate->getDueDate();
                $schedules[]   = $event;
            }
        }

        $invoices = $doctrine->getRepository('AppClientBundle:ClientInvoice')->findAll();
        foreach ($invoices as $invoice) {
            if ($invoice->getDueDate() != null) {
                $event         = array('title' => $invoice->getNumber() . ' (' . $formatter->format($invoice->getStatus(), 'mapping', 'invoice_status') . ')');
                $event['date'] = $invoice->getDueDate();
                $schedules[]   = $event;
            }
        }

        $projects = $doctrine->getRepository('AppClientBundle:ClientProject')->findAll();
        foreach ($projects as $p) {
            if ($p->getDueDate() != null) {
                $schedules[] = array(
                    'title' => 'PRJ-' . $p->getId() . ' (' . $formatter->format($p->getStatus(), 'mapping', 'project_status') . ')',
                    'date'  => $p->getDueDate()
                );
            }
        }

        return $schedules;
    }
}