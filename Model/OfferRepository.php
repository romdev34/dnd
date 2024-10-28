<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Model;

use RomainDndOffers\Offers\Api\OfferRepositoryInterface;
use RomainDndOffers\Offers\Api\Data\OfferInterface;
use RomainDndOffers\Offers\Model\ResourceModel\Offer as OfferResource;
use RomainDndOffers\Offers\Model\ResourceModel\Offer\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class OfferRepository implements OfferRepositoryInterface
{
    /**
     * @var OfferResource
     */
    protected OfferResource $resource;
    /**
     * @var OfferFactory
     */
    protected OfferFactory $offerFactory;
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;
    /**
     * @var SearchResultsInterfaceFactory
     */
    protected SearchResultsInterfaceFactory $searchResultsFactory;
    /**
     * @var CollectionProcessorInterface
     */
    protected CollectionProcessorInterface $collectionProcessor;
    /**
     * @var ExtensibleDataObjectConverter
     */
    protected ExtensibleDataObjectConverter $extensibleDataObjectConverter;

    /**
     * @param OfferResource                 $resource
     * @param OfferFactory                  $offerFactory
     * @param CollectionFactory             $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface  $collectionProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        OfferResource                 $resource,
        OfferFactory                  $offerFactory,
        CollectionFactory             $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface  $collectionProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->offerFactory = $offerFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * Save Offer data
     *
     * @param OfferInterface $offer
     *
     * @return OfferInterface
     * @throws CouldNotSaveException
     */
    public function save(OfferInterface $offer): OfferInterface
    {
        try {
            /** @phpstan-ignore-next-line */
            $this->resource->save($offer);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the offer: %1', $exception->getMessage()));
        }

        return $offer;
    }

    /**
     * Load Offer data collection by given search criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Delete Offer by given Offer Identity
     *
     * @param int $offerId
     *
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $offerId): bool
    {
        $offer = $this->getById($offerId);
        return $this->delete($offer);
    }

    /**
     * Load Offer data by given Offer Identity
     *
     * @param int $offerId
     *
     * @return OfferInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $offerId): OfferInterface
    {
        $offer = $this->offerFactory->create();
        $this->resource->load($offer, $offerId);
        if (!$offer->getId()) {
            throw new NoSuchEntityException(__('Offer with id "%1" does not exist.', $offerId));
        }
        return $offer;
    }

    /**
     * Delete Offer
     *
     * @param OfferInterface $offer
     *
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(OfferInterface $offer): bool
    {
        try {
            /** @phpstan-ignore-next-line */
            $this->resource->delete($offer);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the offer: %1', $exception->getMessage()));
        }

        return true;
    }
}
