<?php
namespace App\UserBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class UserRepository extends EntityRepository
{
    public function __construct($em)
    {
        $class = new ClassMetadata('App\UserBundle\Entity\User');
        parent::__construct($em, $class);
    }
}