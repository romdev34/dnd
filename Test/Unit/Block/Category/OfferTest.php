<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Test\Unit\Block\Category;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RomainDndOffers\Offers\Block\Category\Offer;
use RomainDndOffers\Offers\Model\ResourceModel\Offer\CollectionFactory as OfferCollectionFactory;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class OfferTest extends TestCase
{
    /** @var Object Offer */
    private Object $offerBlock;

    /** @var OfferCollectionFactory|MockObject */
    private MockObject|OfferCollectionFactory $offerCollectionFactory;

    /** @var Registry|MockObject */
    private Registry|MockObject $registry;

    /** @var SerializerInterface|MockObject */
    private SerializerInterface|MockObject $serializer;

    /** @var StoreManagerInterface|MockObject */
    private StoreManagerInterface|MockObject $storeManager;

    /** @var UrlInterface|MockObject */
    private UrlInterface|MockObject $urlBuilder;

    /** @var ManagerInterface|MockObject */
    private MockObject|ManagerInterface $manager;

    public function testGetCategoryOffers()
    {
        $categoryId = 1;

        // Create a mock collection
        $mockCollection = $this->createMock(\RomainDndOffers\Offers\Model\ResourceModel\Offer\Collection::class);

        $items = [
            $this->createConfiguredMock(
                \RomainDndOffers\Offers\Model\Offer::class,
                ['getCategories' => json_encode([$categoryId])]
            ),
            $this->createConfiguredMock(
                \RomainDndOffers\Offers\Model\Offer::class,
                ['getCategories' => json_encode([2])]
            )
        ];

        $mockCollection->method('addFieldToFilter')
            ->willReturnSelf();
        $mockCollection->method('getItems')
            ->willReturn($items);

        $this->offerCollectionFactory->method('create')
            ->willReturn($mockCollection);

        $this->serializer->method('unserialize')
            ->will($this->returnCallback('json_decode'));

        $result = $this->offerBlock->getCategoryOffers($categoryId);
        $this->assertCount(1, $result);
        $this->assertEquals($items[0], $result[0]);
    }

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->offerCollectionFactory = $this->createMock(OfferCollectionFactory::class);
        $this->registry = $this->createMock(Registry::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->urlBuilder = $this->createMock(UrlInterface::class);
        $this->manager = $this->createMock(ManagerInterface::class);

        $context = $this->createMock(Context::class);

        $this->offerBlock = $objectManager->getObject(
            Offer::class,
            [
                'context' => $context,
                'offerCollectionFactory' => $this->offerCollectionFactory,
                'registry' => $this->registry,
                'serializer' => $this->serializer,
                'storeManager' => $this->storeManager,
                'urlBuilder' => $this->urlBuilder,
                'manager' => $this->manager
            ]
        );
    }
}
