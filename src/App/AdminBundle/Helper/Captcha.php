<?php
namespace App\AdminBundle\Helper;

class Captcha
{
    public $container;

    public function __construct($container)
    {
        $this->container = $container;

    }

    public function isActive($ip)
    {
        $em    = $this->container->get('doctrine')->getEntityManager();

        // Get last successful login for IP
        $query = $em->createQueryBuilder();
        $query->select('p.timestamp')
            ->from('AppClientBundle:Log', 'p')
            ->andWhere('p.description LIKE :ip')
            ->andWhere('p.idType = 1')
            ->setParameters([
                'ip' => '%'.$ip.'%',
            ])
            ->orderBy('p.timestamp', 'DESC')
            ->setMaxResults(1);
        try{
            $lastSuccess = $query->getQuery()->getSingleScalarResult();
        } catch(\Doctrine\ORM\NoResultException $e){
            $lastSuccess = new \DateTime("1 January 1970");
        }

        // Get successful logins since lastSuccess
        $query = $em->createQueryBuilder();

        $query->select('COUNT(p)')
            ->from('AppClientBundle:Log', 'p')
            ->andWhere('p.idType = 2')
            ->andWhere('p.timestamp > :time')
            ->andWhere('p.description LIKE :ip')
            ->setParameters([
                'time' => $lastSuccess,
                'ip' => '%'.$ip.'%',
            ]);

        $result = $query->getQuery()->getSingleScalarResult();

        $config     = $this->container->get('app_admin.helper.common')->getConfig();
        $numAttempt = $this->container->getParameter('recaptcha_threshold');
        if ($numAttempt == 0) {
            return false;
        }
        return $result >= $numAttempt;
    }
    public function getCaptcha()
    {

    }
}