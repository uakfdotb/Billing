<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\AdminBundle\Business\Order\Constants;

/**
 * App\ClientBundle\Entity\Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity
 */
class Product
{
    static public $periodicities = [
        Constants::SCHEDULE_MONTHLY       => 'monthly',
        Constants::SCHEDULE_QUARTERLY     => 'quarterly',
        Constants::SCHEDULE_SEMI_ANNUALLY => 'semiannually',
        Constants::SCHEDULE_ANNUALLY      => 'annually',
        Constants::SCHEDULE_BIENNIALLY    => 'biennially',
        Constants::SCHEDULE_TRIENNIALLY   => 'triennially'
    ];

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string $serverPackage
     *
     * @ORM\Column(name="server_package", type="string", length=255, nullable=true)
     */
    private $serverPackage;

    /**
     * @var integer $idProductGroup
     *
     * @ORM\Column(name="id_product_group", type="integer", nullable=true)
     */
    private $idProductGroup;

    /**
     * @var integer $serverGroup
     *
     * @ORM\Column(name="server_group", type="integer", nullable=true)
     */
    private $serverGroup;

    /**
     * @var integer $idEmail
     *
     * @ORM\Column(name="id_email", type="integer", nullable=true)
     */
    private $idEmail;

    /**
     * @var integer $stock
     *
     * @ORM\Column(name="stock", type="integer", nullable=true)
     */
    private $stock;

    /**
     * @var integer $idPaymentType
     *
     * @ORM\Column(name="id_payment_type", type="integer", nullable=true)
     */
    private $idPaymentType;

    /**
     * @var float $setupFeeMonthly
     *
     * @ORM\Column(name="setup_fee_monthly", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $setupFeeMonthly;

    /**
     * @var float $setupFeeQuarterly
     *
     * @ORM\Column(name="setup_fee_quarterly", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $setupFeeQuarterly;

    /**
     * @var float $setupFeeSemiAnnually
     *
     * @ORM\Column(name="setup_fee_semi_annually", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $setupFeeSemiAnnually;

    /**
     * @var float $setupFeeAnnually
     *
     * @ORM\Column(name="setup_fee_annually", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $setupFeeAnnually;

    /**
     * @var float $setupFeeBiennially
     *
     * @ORM\Column(name="setup_fee_biennially", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $setupFeeBiennially;

    /**
     * @var float $setupFeeTriennially
     *
     * @ORM\Column(name="setup_fee_triennially", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $setupFeeTriennially;

    /**
     * @var float $priceMonthly
     *
     * @ORM\Column(name="price_monthly", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $priceMonthly;

    /**
     * @var float $priceQuarterly
     *
     * @ORM\Column(name="price_quarterly", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $priceQuarterly;

    /**
     * @var float $priceSemiAnnually
     *
     * @ORM\Column(name="price_semi_annually", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $priceSemiAnnually;

    /**
     * @var float $priceAnnually
     *
     * @ORM\Column(name="price_annually", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $priceAnnually;

    /**
     * @var float $priceBiennially
     *
     * @ORM\Column(name="price_biennially", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $priceBiennially;

    /**
     * @var float $priceTriennially
     *
     * @ORM\Column(name="price_triennially", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $priceTriennially;

    /**
     * @var boolean $isAvailable
     *
     * @ORM\Column(name="is_available", type="boolean", nullable=true)
     */
    private $isAvailable;

    /**
     * @var integer $idType
     *
     * @ORM\Column(name="id_type", type="integer", nullable=true)
     */
    private $idType;

    /**
     * @var boolean $isRedirectUnpaidInvoice
     *
     * @ORM\Column(name="is_redirect_unpaid_invoice", type="boolean", nullable=true)
     */
    private $isRedirectUnpaidInvoice;

    /**
     * @var integer $triggerCreate
     *
     * @ORM\Column(name="trigger_create", type="integer", nullable=true)
     */
    private $triggerCreate;

    /**
     * @var integer $taxGroup
     *
     * @ORM\Column(name="tax_group", type="integer", nullable=true)
     */
    private $taxGroup;

    /**
     * @var string $color
     *
     * @ORM\Column(name="color", type="string", length=7, nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="module_settings", type="text")
     */
    private $moduleSettings;

    /**
     * @var array
     *
     * @ORM\Column(name="features", type="array")
     */
    private $features;

    /**
     * @param int $taxGroup
     */
    public function setTaxGroup($taxGroup)
    {
        $this->taxGroup = $taxGroup;
    }

    /**
     * @return int
     */
    public function getTaxGroup()
    {
        return $this->taxGroup;
    }

    /**
     * @param string $serverPackage
     */
    public function setServerPackage($serverPackage)
    {
        $this->serverPackage = $serverPackage;
    }

    /**
     * @return string
     */
    public function getServerPackage()
    {
        return $this->serverPackage;
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
     * Set name
     *
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set idProductGroup
     *
     * @param integer $idProductGroup
     * @return Product
     */
    public function setIdProductGroup($idProductGroup)
    {
        $this->idProductGroup = $idProductGroup;

        return $this;
    }

    /**
     * Get idProductGroup
     *
     * @return integer
     */
    public function getIdProductGroup()
    {
        return $this->idProductGroup;
    }

    /**
     * Set idEmail
     *
     * @param integer $idEmail
     * @return Product
     */
    public function setIdEmail($idEmail)
    {
        $this->idEmail = $idEmail;

        return $this;
    }

    /**
     * Get idEmail
     *
     * @return integer
     */
    public function getIdEmail()
    {
        return $this->idEmail;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set idPaymentType
     *
     * @param integer $idPaymentType
     * @return Product
     */
    public function setIdPaymentType($idPaymentType)
    {
        $this->idPaymentType = $idPaymentType;

        return $this;
    }

    /**
     * Get idPaymentType
     *
     * @return integer
     */
    public function getIdPaymentType()
    {
        return $this->idPaymentType;
    }

    /**
     * Set setupFeeMonthly
     *
     * @param float $setupFeeMonthly
     * @return Product
     */
    public function setSetupFeeMonthly($setupFeeMonthly)
    {
        $this->setupFeeMonthly = $setupFeeMonthly;

        return $this;
    }

    /**
     * Get setupFeeMonthly
     *
     * @return float
     */
    public function getSetupFeeMonthly()
    {
        return $this->setupFeeMonthly;
    }

    /**
     * Set setupFeeQuarterly
     *
     * @param float $setupFeeQuarterly
     * @return Product
     */
    public function setSetupFeeQuarterly($setupFeeQuarterly)
    {
        $this->setupFeeQuarterly = $setupFeeQuarterly;

        return $this;
    }

    /**
     * Get setupFeeQuarterly
     *
     * @return float
     */
    public function getSetupFeeQuarterly()
    {
        return $this->setupFeeQuarterly;
    }

    /**
     * Set setupFeeSemiAnnually
     *
     * @param float $setupFeeSemiAnnually
     * @return Product
     */
    public function setSetupFeeSemiAnnually($setupFeeSemiAnnually)
    {
        $this->setupFeeSemiAnnually = $setupFeeSemiAnnually;

        return $this;
    }

    /**
     * Get setupFeeSemiAnnually
     *
     * @return float
     */
    public function getSetupFeeSemiAnnually()
    {
        return $this->setupFeeSemiAnnually;
    }

    /**
     * Set setupFeeAnnually
     *
     * @param float $setupFeeAnnually
     * @return Product
     */
    public function setSetupFeeAnnually($setupFeeAnnually)
    {
        $this->setupFeeAnnually = $setupFeeAnnually;

        return $this;
    }

    /**
     * Get setupFeeAnnually
     *
     * @return float
     */
    public function getSetupFeeAnnually()
    {
        return $this->setupFeeAnnually;
    }

    /**
     * Set setupFeeBiennially
     *
     * @param float $setupFeeBiennially
     * @return Product
     */
    public function setSetupFeeBiennially($setupFeeBiennially)
    {
        $this->setupFeeBiennially = $setupFeeBiennially;

        return $this;
    }

    /**
     * Get setupFeeBiennially
     *
     * @return float
     */
    public function getSetupFeeBiennially()
    {
        return $this->setupFeeBiennially;
    }

    /**
     * Set setupFeeTriennially
     *
     * @param float $setupFeeTriennially
     * @return Product
     */
    public function setSetupFeeTriennially($setupFeeTriennially)
    {
        $this->setupFeeTriennially = $setupFeeTriennially;

        return $this;
    }

    /**
     * Get setupFeeTriennially
     *
     * @return float
     */
    public function getSetupFeeTriennially()
    {
        return $this->setupFeeTriennially;
    }

    /**
     * Set priceMonthly
     *
     * @param float $priceMonthly
     * @return Product
     */
    public function setPriceMonthly($priceMonthly)
    {
        $this->priceMonthly = $priceMonthly;

        return $this;
    }

    /**
     * Get priceMonthly
     *
     * @return float
     */
    public function getPriceMonthly()
    {
        return $this->priceMonthly;
    }

    /**
     * Set priceQuarterly
     *
     * @param float $priceQuarterly
     * @return Product
     */
    public function setPriceQuarterly($priceQuarterly)
    {
        $this->priceQuarterly = $priceQuarterly;

        return $this;
    }

    /**
     * Get priceQuarterly
     *
     * @return float
     */
    public function getPriceQuarterly()
    {
        return $this->priceQuarterly;
    }

    /**
     * Set priceSemiAnnually
     *
     * @param float $priceSemiAnnually
     * @return Product
     */
    public function setPriceSemiAnnually($priceSemiAnnually)
    {
        $this->priceSemiAnnually = $priceSemiAnnually;

        return $this;
    }

    /**
     * Get priceSemiAnnually
     *
     * @return float
     */
    public function getPriceSemiAnnually()
    {
        return $this->priceSemiAnnually;
    }

    /**
     * Set priceAnnually
     *
     * @param float $priceAnnually
     * @return Product
     */
    public function setPriceAnnually($priceAnnually)
    {
        $this->priceAnnually = $priceAnnually;

        return $this;
    }

    /**
     * Get priceAnnually
     *
     * @return float
     */
    public function getPriceAnnually()
    {
        return $this->priceAnnually;
    }

    /**
     * Set priceBiennially
     *
     * @param float $priceBiennially
     * @return Product
     */
    public function setPriceBiennially($priceBiennially)
    {
        $this->priceBiennially = $priceBiennially;

        return $this;
    }

    /**
     * Get priceBiennially
     *
     * @return float
     */
    public function getPriceBiennially()
    {
        return $this->priceBiennially;
    }

    /**
     * Set priceTriennially
     *
     * @param float $priceTriennially
     * @return Product
     */
    public function setPriceTriennially($priceTriennially)
    {
        $this->priceTriennially = $priceTriennially;

        return $this;
    }

    /**
     * Get priceTriennially
     *
     * @return float
     */
    public function getPriceTriennially()
    {
        return $this->priceTriennially;
    }

    /**
     * Set isAvailable
     *
     * @param boolean $isAvailable
     * @return Product
     */
    public function setIsAvailable($isAvailable)
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    /**
     * Get isAvailable
     *
     * @return boolean
     */
    public function getIsAvailable()
    {
        return $this->isAvailable;
    }

    /**
     * @param int $serverGroup
     */
    public function setServerGroup($serverGroup)
    {
        $this->serverGroup = $serverGroup;
    }

    /**
     * @return int
     */
    public function getServerGroup()
    {
        return $this->serverGroup;
    }

    /**
     * Set idType
     *
     * @param integer $idType
     * @return Product
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
     * Set isRedirectUnpaidInvoice
     *
     * @param boolean $isRedirectUnpaidInvoice
     * @return Product
     */
    public function setIsRedirectUnpaidInvoice($isRedirectUnpaidInvoice)
    {
        $this->isRedirectUnpaidInvoice = $isRedirectUnpaidInvoice;

        return $this;
    }

    /**
     * Get isRedirectUnpaidInvoice
     *
     * @return boolean
     */
    public function getIsRedirectUnpaidInvoice()
    {
        return $this->isRedirectUnpaidInvoice;
    }

    /**
     * @param int $triggerCreate
     */
    public function setTriggerCreate($triggerCreate)
    {
        $this->triggerCreate = $triggerCreate;
    }

    /**
     * @return int
     */
    public function getTriggerCreate()
    {
        return $this->triggerCreate;
    }

    /**
     * Get price
     *
     * @param periodicity
     * @return float
     */
    public function getPrice($periodicity = 'monthly')
    {
        $method = 'getPrice' . ucfirst(strtolower($periodicity));

        return $this->$method();
    }

    /**
     * Get prices
     *
     * @return array
     */
    public function getPrices()
    {
        $prices  = [];

        foreach (self::$periodicities as $periodicity)
        {
            $method = 'getPrice' . ucfirst($periodicity);

            $prices[$periodicity] = $this->$method();
        }

        return $prices;
    }

    /**
     * Get shortest periodicity price set
     *
     * @return array
     */
    public function getShortestPeriodicityPrice(&$periodicity = '')
    {
        foreach ($this->getPrices() as $periodicity => $price)
        {
            if ($price > 0)
            {
                return $price;
            }
        }

        $periodicity = 'monthly';

        return $this->getPriceMonthly();
    }

    /**
     * Get shortest periodicity which has a price set
     *
     * @return array
     */
    public function getShortestPeriodicity(&$price = '')
    {
        $price = $this->getShortestPeriodicityPrice($periodicity);

        return $periodicity;
    }

    /**
     * Get setup fee
     *
     * @param periodicity
     * @return float
     */
    public function getSetupFee($periodicity = 'monthly')
    {
        $method = 'getSetupFee' . ucfirst(strtolower($periodicity));

        return $this->$method();
    }

    /**
     * Get setup fees
     *
     * @return array
     */
    public function getSetupFees()
    {
        $fees  = [];

        foreach (self::$periodicities as $periodicity)
        {
            $method = 'getSetupFee' . ucfirst($periodicity);

            $fees[$periodicity] = $this->$method();
        }

        return $fees;
    }

    /**
     * Get setup fee of the shortest periodicity with a price set
     *
     * @return array
     */
    public function getShortestPeriodicitySetupFee(&$periodicity = '')
    {
        $periodicity = $this->getShortestPeriodicity();

        return $this->getSetupFee($periodicity);
    }

    /**
     * Get color
     *
     * @param string $default
     * @return string
     */
    public function getColor($default = '')
    {
        return empty($this->color) ? $default : $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * Get hover color
     * Requires an hexadecimal RGB color (eg, #102A3F)
     * Returns a slightly brighter color in the same format
     *
     * @param string $original
     * @return string
     */
    public function getHoverColor($original = '')
    {
        $color = $this->getColor($original);

        if (preg_match('/^#([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})$/', $color, $matches))
        {
            array_shift($matches);
            array_walk($matches, function(&$value) { $value = dechex(min(255, hexdec($value) + 16)); });
            $color = '#' . implode('', $matches);
        }

        return $color;
    }

    /**
     * @param string $moduleSettings
     */
    public function setModuleSettings($moduleSettings)
    {
        $this->moduleSettings = $moduleSettings;
    }

    /**
     * @return string
     */
    public function getModuleSettings()
    {
        return $this->moduleSettings;
    }

    /**
     * @param array $features
     */
    public function setFeatures($features)
    {
        $this->features = $features;
    }

    /**
     * @return array
     */
    public function getFeatures()
    {
        return $this->features;
    }
}