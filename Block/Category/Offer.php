<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Block\Category;

use Magento\Framework\View\Element\Template;
use RomainDndOffers\Offers\Model\ResourceModel\Offer\CollectionFactory as OfferCollectionFactory;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Message\ManagerInterface;

/**
 *  Block offer
 */
class Offer extends Template
{
    /**
     * @var OfferCollectionFactory
     */
    protected OfferCollectionFactory $offerCollectionFactory;
    /**
     * @var mixed
     */
    protected mixed $category;
    /**
     * @var Registry
     */
    protected Registry $registry;
    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;
    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlBuilder;
    /**
     * @var ManagerInterface
     */
    protected ManagerInterface $manager;

    /**
     * @param Template\Context       $context
     * @param OfferCollectionFactory $offerCollectionFactory
     * @param Registry               $registry
     * @param SerializerInterface    $serializer
     * @param StoreManagerInterface  $storeManager
     * @param UrlInterface           $urlBuilder
     * @param ManagerInterface       $manager
     * @param array<mixed>           $data
     */
    public function __construct(
        Template\Context       $context,
        OfferCollectionFactory $offerCollectionFactory,
        Registry               $registry,
        SerializerInterface    $serializer,
        StoreManagerInterface  $storeManager,
        UrlInterface           $urlBuilder,
        ManagerInterface       $manager,
        array                  $data = []
    ) {
        $this->offerCollectionFactory = $offerCollectionFactory;
        $this->registry = $registry;
        $this->serializer = $serializer;
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->manager = $manager;
        parent::__construct($context, $data);
    }

    /**
     * Retrieves a list of offers for a specific category.
     *
     * @param int|string $categoryId
     *
     * @return array<mixed> The list of offers available for the specified category.
     */
    public function getCategoryOffers(int|string $categoryId): array
    {
        $currentDate = date('Y-m-d 00:00:00');
        $categoryId = (int)$categoryId;

        $collection = $this->offerCollectionFactory->create()
            ->addFieldToFilter('start_date', ['lteq' => $currentDate])
            ->addFieldToFilter('end_date', ['gteq' => $currentDate]);

        $items = $collection->getItems();
        return array_filter($items, function ($offer) use ($categoryId) {
            $categories = $this->serializer->unserialize($offer->getCategories());
            if (is_array($categories)) {
                return in_array($categoryId, $categories);
            } else {
                return false;
            }
        });
    }

    /**
     * Retrieve the current category from the registry.
     *
     * @return mixed
     */
    public function getCategory(): mixed
    {
        return $this->registry->registry('current_category');
    }

    /**
     * Retrieves and displays error messages from the manager.
     *
     * @return void
     */
    public function getErrorMessage(): void
    {
        $this->manager->getMessages();
    }
}
