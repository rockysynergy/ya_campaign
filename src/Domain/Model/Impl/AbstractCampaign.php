<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Orqlog\Yacampaign\Domain\Exception\IllegalArgumentException;
use Orqlog\Yacampaign\Domain\Exception\NoQualificationPolicyFoundException;
use Orqlog\Yacampaign\Domain\Model\UserInterface;
use Orqlog\Yacampaign\Domain\Model\ProductInterface;
use Orqlog\Yacampaign\Domain\Model\CampaignInterface;
use Orqlog\Yacampaign\Domain\Model\QualificationPolicyInterface;

abstract class AbstractCampaign extends AbstractEntity implements CampaignInterface
{
    /**
     * @var \DateTime
     */
    private $startAt;

    /**
     * @var \DateTime
     */
    private $expireAt;

    /**
     * @var array
     */
    private $qualificationPolicies = [];

    /**
     * @var
     */
    private $products = [];

    /**
     * @param \DateTime $startAt
     * @return void
     */
    public function setStartAt(\DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt(): \DateTime
    {
        return $this->startAt;
    }

    /**
     * @param \DateTime $expireAt
     * @return void
     */
    public function setExpireAt(\DateTime $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

    /**
     * @return \DateTime
     */
    public function getExpireAt(): \DateTime
    {
        return $this->expireAt;
    }

    /**
     * Whether the campain is still open
     * 
     * @return boolean
     */
    public function isOpen(): bool
    {
        $now = new \DateTime();
        return $now > $this->startAt && $now < $this->expireAt;
    }

    /**
     * Get the products associated with this campain
     * 
     * @return array 
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * set products
     * 
     * @param array $products
     * @return void
     */
    public function setProducts(array $products):void 
    {
        foreach ($products as $product) {
            if (! $product instanceof ProductInterface) {
                throw new IllegalArgumentException('请提供实现了ProuctInterface接口的对象！');
            }

            $identifier = $product->getIdentifier();
            $this->products[$identifier] = $product;
        }
    }

    /**
     * add product
     * 
     * @param ProductInterface $product
     * @return void
     */
    public function addProduct(ProductInterface $product):void
    {
        $identifier = $product->getIdentifier();
        $this->products[$identifier] = $product;
    }

    /**
     * Remove the product
     * 
     * @param ProductInterface $product
     * @return void
     */
    public function removeProduct(ProductInterface $product):void
    {
        $newProds= [];
        foreach ($this->products as $aProd) {
            if ($aProd->getIdentifier() !== $product->getIdentifier()) {
                array_push($newProds, $aProd);
            }
        }

        $this->products = $newProds;
    }

    /**
     * Add qualificationPolicies, if it will overwrite the eixsting one based on Policy's identifier
     * 
     * @param QualificationPolicyInterface $qPolicy
     * @return void
     */
    public function addQualificationPolicy(QualificationPolicyInterface $qPolicy): void
    {
        $identifier = $qPolicy->getIdentifier();
        $this->qualificationPolicies[$identifier] = $qPolicy;
    }

    
    /**
     * set policies
     * 
     * @param array $policies
     * @return void
     */
    public function setQualificationPolicies(array $policies):void 
    {
        foreach ($policies as $policy) {
            if (! $policy instanceof QualificationPolicyInterface) {
                throw new IllegalArgumentException('请提供实现了QualificationPolicyInterface接口的对象！');
            }

            $identifier = $policy->getIdentifier();
            $this->qualificationPolicies[$identifier] = $policy;
        }
    }

    /**
     * Get qualification policies
     * 
     * @return array
     */
    public function getQualificationPolicies():array 
    {
        return $this->qualificationPolicies;
    }

    /**
     * remove qualificationPolicy
     */
    public function removeQualificationPolicy(QualificationPolicyInterface $qPolicy): void
    {
        $identifier = $qPolicy->getIdentifier();
        if (isset($this->qualificationPolicies[$identifier])) {
            unset($this->qualificationPolicies[$identifier]);
        }
    }

    /**
     * @param (QualificationPolicyInterface $qPolicy
     * @return boolean
     */
    public function hasQualificationPolicy(QualificationPolicyInterface $qPolicy): bool
    {
        $identifier = $qPolicy->getIdentifier();
        return isset($this->qualificationPolicies[$identifier]);
    }

    /**
     * Determeine whether the use is qualified to join
     */
    public function isQualified(UserInterface $user): bool
    {
        if (count($this->qualificationPolicies) < 1) {
            throw new NoQualificationPolicyFoundException('No policy!', 1590399166);
        }

        $result = true;
        foreach ($this->qualificationPolicies as $qPolicy) {
            if ($qPolicy->isQualified($user) === false) {
                $result = false;
                break;
            }
        }

        return $result;
    }
}
