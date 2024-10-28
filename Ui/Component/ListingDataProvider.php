<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Ui\Component;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\Serialize\SerializerInterface;

class ListingDataProvider extends DataProvider
{
    /**
     * @var CategoryCollectionFactory
     */
    protected CategoryCollectionFactory $categoryCollectionFactory;
    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param SerializerInterface       $serializer
     * @param string                    $name
     * @param string                    $primaryFieldName
     * @param string                    $requestFieldName
     * @param ReportingInterface        $reporting
     * @param SearchCriteriaBuilder     $searchCriteriaBuilder
     * @param RequestInterface          $request
     * @param FilterBuilder             $filterBuilder
     * @param array<mixed>              $meta
     * @param array<mixed>              $data
     */
    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory,
        SerializerInterface       $serializer,
        string                    $name,
        string                    $primaryFieldName,
        string                    $requestFieldName,
        ReportingInterface        $reporting,
        SearchCriteriaBuilder     $searchCriteriaBuilder,
        RequestInterface          $request,
        FilterBuilder             $filterBuilder,
        array                     $meta = [],
        array                     $data = []
    ) {
        $this->serializer = $serializer;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    /**
     * Retrieve and process data items with category information.
     *
     * The method fetches search results, unserializes category data,
     * retrieves category names, and injects them back into the items array.
     *
     * @return array{
     *     items: array<int, array<string, mixed>>,
     *     totalRecords: int
     * }
     * @throws LocalizedException
     */
    public function getData(): array
    {
        $searchResults = $this->searchResultToOutput($this->getSearchResult());
        $items = $searchResults['items'];
        foreach ($items as $key => $item) {
            if (!empty($item["categories"])) {
                $categoryIds = $this->serializer->unserialize($item["categories"]);
                if (is_array($categoryIds)) {
                    $categoryNames = $this->getCategoryNames($categoryIds);
                    $itemCategories = implode(',', $categoryNames);
                    $items[$key]["categories"] = ($itemCategories);
                }
            }
        }

        return [
            "items" => $items,
            "totalRecords" => $this->getSearchResult()->getTotalCount()
        ];
    }

    /**
     * Get categories names for the grid datasource
     *
     * @param array<mixed> $categoryIds
     *
     * @return array<mixed>
     * @throws LocalizedException
     */
    protected function getCategoryNames(array $categoryIds): array
    {
        $categories = $this->categoryCollectionFactory->create()
            ->addAttributeToSelect('name')
            ->addAttributeToFilter('entity_id', ['in' => $categoryIds]);

        $categoryNames = [];
        foreach ($categories as $category) {
            $categoryNames[$category->getId()] = $category->getName();
        }

        return $categoryNames;
    }
}
