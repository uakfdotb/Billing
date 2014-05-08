<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientProductItem
 *
 * @ORM\Table("client_product_item")
 * @ORM\Entity
 */
class ClientProductItem
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_client_product", type="integer")
     */
    private $idClientProduct;

    /**
     * @var array
     *
     * @ORM\Column(name="details", type="array")
     */
    private $details;


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
     * Set idClientProduct
     *
     * @param integer $idClientProduct
     * @return ClientProductItem
     */
    public function setIdClientProduct($idClientProduct)
    {
        $this->idClientProduct = $idClientProduct;

        return $this;
    }

    /**
     * Get idClientProduct
     *
     * @return integer 
     */
    public function getIdClientProduct()
    {
        return $this->idClientProduct;
    }

    /**
     * Set details
     *
     * @param array $details
     * @return ClientProductItem
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return array 
     */
    public function getDetails()
    {
        return $this->details;
    }
}
