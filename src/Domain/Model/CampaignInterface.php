<?php

namespace Orqlog\Yacampaign\Domain\Model;


interface CampaignInterface extends EntityInterface
{
    /**
     * Whether the campain is still open
     */
    public function isOpen() :bool;

    /**
     * Determeine whether the use is qualified to join
     */
    public function isQualified(UserInterface $user):bool;

    /**
     * Get the products associated with this campain
     */
    public function getProducts() :array;

    public function addProduct(ProductInterface $product);
    
    public function removeProduct(ProductInterface $product);

    /**
     * Add qualificationPolicies
     */
    public function addQualificationPolicy(QualificationPolicyInterface $qPolicy) :void;

    /**
     * remove qualificationPolicy
     */
    public function removeQualificationPolicy(QualificationPolicyInterface $qPolicy) :void;

}