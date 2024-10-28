<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Test\Unit\Model;

use RomainDndOffers\Offers\Api\Data\OfferInterface;
use RomainDndOffers\Offers\Model\Offer;
use RomainDndOffers\Offers\Model\ResourceModel\Offer as OfferResource;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use PHPUnit\Framework\TestCase;

class OfferTest extends TestCase
{
    /**
     * @var Offer
     */
    private Offer $offer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $contextMock = $this->createMock(Context::class);
        $registryMock = $this->createMock(Registry::class);
        $resourceMock = $this->createMock(OfferResource::class);
        $resourceMock->method('getIdFieldName')->willReturn('offer_id'); // Mock getIdFieldName method

        $dataMock = $this->createMock(\Magento\Framework\Data\Collection\AbstractDb::class);

        $this->offer = new Offer($contextMock, $registryMock, $resourceMock, $dataMock);
    }

    /**
     * @return void
     */
    public function testSetOfferId()
    {
        $offerId = 123;
        $this->offer->setOfferId($offerId);
        $this->assertEquals($offerId, $this->offer->getData(OfferInterface::OFFER_ID));
    }

    /**
     * @return void
     */
    public function testGetOfferId()
    {
        $offerId = 123;
        $this->offer->setData(OfferInterface::OFFER_ID, $offerId);
        $this->assertEquals($offerId, $this->offer->getOfferId());
    }

    /**
     * @return void
     */
    public function testSetLabel()
    {
        $label = 'Special Offer';
        $this->offer->setLabel($label);
        $this->assertEquals($label, $this->offer->getData(OfferInterface::LABEL));
    }

    /**
     * @return void
     */
    public function testGetLabel()
    {
        $label = 'Special Offer';
        $this->offer->setData(OfferInterface::LABEL, $label);
        $this->assertEquals($label, $this->offer->getLabel());
    }

    /**
     * @return void
     */
    public function testSetImage()
    {
        $image = 'image.png';
        $this->offer->setImage($image);
        $this->assertEquals($image, $this->offer->getData(OfferInterface::IMAGE));
    }

    /**
     * @return void
     */
    public function testGetImage()
    {
        $image = 'image.png';
        $this->offer->setData(OfferInterface::IMAGE, $image);
        $this->assertEquals($image, $this->offer->getImage());
    }

    /**
     * @return void
     */
    public function testSetLink()
    {
        $link = 'http://example.com';
        $this->offer->setLink($link);
        $this->assertEquals($link, $this->offer->getData(OfferInterface::LINK));
    }

    /**
     * @return void
     */
    public function testGetLink()
    {
        $link = 'http://example.com';
        $this->offer->setData(OfferInterface::LINK, $link);
        $this->assertEquals($link, $this->offer->getLink());
    }

    /**
     * @return void
     */
    public function testSetCategories()
    {
        $categories = '["3","4"]';
        $this->offer->setCategories($categories);
        $this->assertEquals($categories, $this->offer->getData(OfferInterface::CATEGORIES));
    }

    /**
     * @return void
     */
    public function testGetCategories()
    {
        $categories = ['Category1', 'Category2'];
        $this->offer->setData(OfferInterface::CATEGORIES, $categories);
        $this->assertEquals($categories, $this->offer->getCategories());
    }

    /**
     * @return void
     */
    public function testSetStartDate()
    {
        $startDate = '2023-01-01 00:00:00';
        $this->offer->setStartDate($startDate);
        $this->assertEquals($startDate, $this->offer->getData(OfferInterface::START_DATE));
    }

    /**
     * @return void
     */
    public function testGetStartDate()
    {
        $startDate = '2023-01-01 00:00:00';
        $this->offer->setData(OfferInterface::START_DATE, $startDate);
        $this->assertEquals($startDate, $this->offer->getStartDate());
    }

    /**
     * @return void
     */
    public function testSetEndDate()
    {
        $endDate = '2023-12-31 23:59:59';
        $this->offer->setEndDate($endDate);
        $this->assertEquals($endDate, $this->offer->getData(OfferInterface::END_DATE));
    }

    /**
     * @return void
     */
    public function testGetEndDate()
    {
        $endDate = '2023-12-31 23:59:59';
        $this->offer->setData(OfferInterface::END_DATE, $endDate);
        $this->assertEquals($endDate, $this->offer->getEndDate());
    }

    /**
     * @return void
     */
    public function testSetCreationDate()
    {
        $creationDate = '2022-12-01 00:00:00';
        $this->offer->setCreationDate($creationDate);
        $this->assertEquals($creationDate, $this->offer->getData(OfferInterface::CREATION_DATE));
    }

    /**
     * @return void
     */
    public function testGetCreationDate()
    {
        $creationDate = '2022-12-01 00:00:00';
        $this->offer->setData(OfferInterface::CREATION_DATE, $creationDate);
        $this->assertEquals($creationDate, $this->offer->getCreationDate());
    }

    /**
     * @return void
     */
    public function testSetUpdateDate()
    {
        $updateDate = '2023-06-01 00:00:00';
        $this->offer->setUpdateDate($updateDate);
        $this->assertEquals($updateDate, $this->offer->getData(OfferInterface::UPDATE_DATE));
    }

    /**
     * @return void
     */
    public function testGetUpdateDate()
    {
        $updateDate = '2023-06-01 00:00:00';
        $this->offer->setData(OfferInterface::UPDATE_DATE, $updateDate);
        $this->assertEquals($updateDate, $this->offer->getUpdateDate());
    }
}
