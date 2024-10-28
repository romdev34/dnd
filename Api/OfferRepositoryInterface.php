<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Api;

use RomainDndOffers\Offers\Api\Data\OfferInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface OfferRepositoryInterface
{
    /**
     * Save Offer
     *
     * @param OfferInterface $offer
     * @return OfferInterface
     * @throws LocalizedException
     */
    public function save(OfferInterface $offer): OfferInterface;

    /**
     * Retrieve Offer
     *
     * @param int $offerId
     * @return OfferInterface
     * @throws LocalizedException
     */
    public function getById(int $offerId): OfferInterface;

    /**
     * Retrieve offers matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * Delete Offer
     *
     * @param OfferInterface $offer
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(OfferInterface $offer): bool;

    /**
     * Delete Offer by ID
     *
     * @param int $offerId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(int $offerId): bool;
}
