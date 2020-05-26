<?php

use PHPUnit\Framework\TestCase;
use Orqlog\Yacampaign\Domain\Model\UserInterface;
use Orqlog\Yacampaign\Domain\Model\Impl\AbstractCampaign;
use Orqlog\Yacampaign\Domain\Model\QualificationPolicyInterface;
use Orqlog\Yacampaign\Domain\Exception\NoQualificationPolicyFoundException;
use Orqlog\Yacampaign\Domain\Model\ProductInterface;

final class AbstractCampaignTest extends TestCase
{

    /**
     * @test
     */
    public function isOpenReturnsTrueIfNowIsWithinCampainDates()
    {
        $campaign = new DummyCampaign();
        $campaign->setStartAt(new DateTime(date('Y-m-d', strtotime('-3 day'))));
        $campaign->setExpireAt(new DateTime(date('Y-m-d', strtotime('+3 day'))));

        $this->assertTrue($campaign->isOpen());
    }

    /**
     * @test
     */
    public function isOpenReturnsFalseIfCampainIsNotStartedYet()
    {
        $campaign = new DummyCampaign();
        $campaign->setStartAt(new DateTime(date('Y-m-d', strtotime('+3 day'))));
        $campaign->setExpireAt(new DateTime(date('Y-m-d', strtotime('+3 week'))));

        $this->assertFalse($campaign->isOpen());
    }

    /**
     * @test
     */
    public function isOpenReturnsFalseIfCampainIsExpired()
    {
        $campaign = new DummyCampaign();
        $campaign->setStartAt(new DateTime(date('Y-m-d', strtotime('-3 day'))));
        $campaign->setExpireAt(new DateTime(date('Y-m-d', strtotime('-3 minute'))));

        $this->assertFalse($campaign->isOpen());
    }

    /**
     * @test
     */
    public function hasQualificationPolicyReturnsTrueIfCampainDoesHaveThePolicy()
    {
        $policyIdentifier = 'ThePolicy_1';
        $campaign = new DummyCampaign();
        $mqPolicy = $this->createMock(QualificationPolicyInterface::class);
        $mqPolicy->expects($this->once())
            ->method('getIdentifier')
            ->willReturn($policyIdentifier);
        $campaign->addQualificationPolicy($mqPolicy);

        $mq2Policy = $this->createMock(QualificationPolicyInterface::class);
        $mq2Policy->expects($this->once())
            ->method('getIdentifier')
            ->willReturn($policyIdentifier);

        $this->assertTrue($campaign->hasQualificationPolicy($mq2Policy));
    }

    /**
     * @test
     */
    public function hasQualificationPolicyReturnsFalseIfCampainDoesNotHaveThePolicy()
    {
        $policyIdentifier = 'ThePolicy_1';
        $campaign = new DummyCampaign();
        $mqPolicy = $this->createMock(QualificationPolicyInterface::class);
        $mqPolicy->expects($this->once())
            ->method('getIdentifier')
            ->willReturn($policyIdentifier . 'a');
        $campaign->addQualificationPolicy($mqPolicy);

        $mq2Policy = $this->createMock(QualificationPolicyInterface::class);
        $mq2Policy->expects($this->once())
            ->method('getIdentifier')
            ->willReturn($policyIdentifier);

        $this->assertFalse($campaign->hasQualificationPolicy($mq2Policy));
    }

    
    /**
     * @test
     */
    public function setQualificationPoliciesScreenOutDuplicates()
    {
        $policies = [];
        for ($i = 0; $i < 3; $i++) {
            $policy = $this->createMock(QualificationPolicyInterface::class);
            $policy->expects($this->any())
                ->method('getIdentifier')
                ->willReturn('policy_' . $i);
            
            array_push($policies, $policy);
            array_push($policies, $policy);
        }
            
        $campaign = new DummyCampaign();
        $campaign->setQualificationPolicies($policies);
        $this->assertEquals(3, count($campaign->getQualificationPolicies()));
    }

    
    /**
     * @test
     */
    public function removeQualificationPolicy()
    {
        $policyIdentifier = 'ThePolicy_1';
        $campaign = new DummyCampaign();
        $mqPolicy = $this->createMock(QualificationPolicyInterface::class);
        $mqPolicy->expects($this->any())
            ->method('getIdentifier')
            ->willReturn($policyIdentifier);
        $campaign->addQualificationPolicy($mqPolicy);
        $this->assertTrue($campaign->hasQualificationPolicy($mqPolicy));
        
        $campaign->removeQualificationPolicy($mqPolicy);
        $this->assertFalse($campaign->hasQualificationPolicy($mqPolicy));

    }

    /**
     * @test
     */
    public function isQualifiedThrowsExceptionIfNoQualificationPolicyIsAdded()
    {
        $this->expectException(NoQualificationPolicyFoundException::class);
        $this->expectExceptionCode(1590399166);
        $campaign = new DummyCampaign();
        $mUser = $this->createMock(UserInterface::class);

        $campaign->isQualified($mUser);
    }

    /**
     * @test
     */
    public function isQualifiedReturnsTrueIfQualificationPolicyResolveToTrueForUser()
    {
        $campaign = new DummyCampaign();
        $mUser = $this->createMock(UserInterface::class);
        $mqPolicy = $this->createMock(QualificationPolicyInterface::class);
        $mqPolicy->expects($this->once())
            ->method('isQualified')
            ->with($this->equalTo($mUser))
            ->willReturn(true);

        $campaign->addQualificationPolicy($mqPolicy);

        $this->assertTrue($campaign->isQualified($mUser));
    }

    /**
     * @test
     */
    public function isQualifiedReturnsFalseIfOneQualificationPolicyResolveToFalseForUser()
    {
        $campaign = new DummyCampaign();
        $mUser = $this->createMock(UserInterface::class);

        $mq2Policy = $this->createMock(QualificationPolicyInterface::class);
        $mq2Policy->expects($this->once())
            ->method('isQualified')
            ->with($this->equalTo($mUser))
            ->willReturn(true);
        $mq2Policy->expects($this->once())
            ->method('getIdentifier')
            ->willReturn('policy_1');
        $campaign->addQualificationPolicy($mq2Policy);

        $mqPolicy = $this->createMock(QualificationPolicyInterface::class);
        $mqPolicy->expects($this->once())
            ->method('isQualified')
            ->with($this->equalTo($mUser))
            ->willReturn(false);
        $mqPolicy->expects($this->once())
            ->method('getIdentifier')
            ->willReturn('policy_2');
        $campaign->addQualificationPolicy($mqPolicy);

        $this->assertFalse($campaign->isQualified($mUser));
    }

    /**
     * @test
     */
    public function addProduct()
    {
        $campaign = new DummyCampaign();
        $prodIdentifier = 'TheProd_id';

        $product = $this->createMock(ProductInterface::class);
        $product->expects($this->any())
            ->method('getIdentifier')
            ->willReturn($prodIdentifier);
        $campaign->addProduct($product);
        
        $products = $campaign->getProducts();
        $this->assertEquals($prodIdentifier, $products[$prodIdentifier]->getIdentifier());
    }

    /**
     * @test
     */
    public function addProductScreenOutDuplication()
    {
        $campaign = new DummyCampaign();
        $prodIdentifier = 'TheProd_id';

        $product = $this->createMock(ProductInterface::class);
        $product->expects($this->any())
            ->method('getIdentifier')
            ->willReturn($prodIdentifier);
        $campaign->addProduct($product);
        $campaign->addProduct($product);
        
        $products = $campaign->getProducts();
        $this->assertEquals(1, count($products));
        $this->assertEquals($prodIdentifier, $products[$prodIdentifier]->getIdentifier());
    }

    /**
     * @test
     */
    public function removeProduct()
    {
        $campaign = new DummyCampaign();
        $prodIdentifier = 'TheProd_id';

        $product = $this->createMock(ProductInterface::class);
        $product->expects($this->any())
            ->method('getIdentifier')
            ->willReturn($prodIdentifier);
        $campaign->addProduct($product);
        $products = $campaign->getProducts();
        $this->assertEquals($prodIdentifier, $products[$prodIdentifier]->getIdentifier());

        $campaign->removeProduct($product);
        $this->assertEquals(0, count($campaign->getProducts()));
    }

    /**
     * @test
     */
    public function setProducts()
    {
        $products = [];
        for ($i = 0; $i < 3; $i++) {
            $product = $this->createMock(ProductInterface::class);
            $product->expects($this->any())
                ->method('getIdentifier')
                ->willReturn('product_' . $i);
            
            array_push($products, $product);
            array_push($products, $product);
        }
            
        $campaign = new DummyCampaign();
        $campaign->setProducts($products);
        $this->assertEquals(3, count($campaign->getProducts()));
    }

}

class DummyCampaign extends AbstractCampaign 
{

}