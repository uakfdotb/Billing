<?php
namespace App\ApiBundle;

use JMS\Serializer\Annotation as Serializer;

use Doctrine\Common\Collections\ArrayCollection,
    Doctrine\Common\Collections\Collection;

class ApiResponse
{
    /**
     * @var Collection
     */
    protected $articles;

    /**
     * @var int
     */
    protected $page;

    public function __construct($controller, $articles, $page)
    {
        try{
            $paginator = $controller->get('knp_paginator');
            $this->articles = $paginator->paginate($articles, $page, 10)->getItems();
        } catch(\Exception $e) {
            $articles = Array();
        }

        $this->page = $page;
    }

    public function getArticles()
    {
        return $this->articles;
    }

    public function getPage()
    {
        return $this->page;
    }
}