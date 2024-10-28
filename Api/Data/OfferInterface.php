<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Api\Data;

use RomainDndOffers\Offers\Model\Offer;

interface OfferInterface
{
    /**
     *
     */
    public const OFFER_ID = 'offer_id';
    /**
     *
     */
    public const LABEL = 'label';
    /**
     *
     */
    public const IMAGE = 'image';
    /**
     *
     */
    public const LINK = 'link';
    /**
     *
     */
    public const CATEGORIES = 'categories';
    /**
     *
     */
    public const START_DATE = 'start_date';
    /**
     *
     */
    public const END_DATE = 'end_date';
    /**
     *
     */
    public const CREATION_DATE = 'creation_date';
    /**
     *
     */
    public const UPDATE_DATE = 'update_date';

    /**
     * Get the offer id
     *
     * @return int
     */
    public function getOfferId(): int;

    /**
     * Get the offer label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Get the image offer
     *
     * @return mixed
     */
    public function getImage(): mixed;

    /**
     * Get the link offer
     *
     * @return string|null
     */
    public function getLink(): ?string;

    /**
     * Get the categories offer
     *
     * @return mixed
     */
    public function getCategories(): mixed;

    /**
     * Get the start date offer
     *
     * @return string|null
     */
    public function getStartDate(): ?string;

    /**
     * Get the end date offer
     *
     * @return string|null
     */
    public function getEndDate(): ?string;

    /**
     * Get the creation date for the offer
     *
     * @return string|null
     */
    public function getCreationDate(): ?string;

    /**
     * Get the update date for the offer
     *
     * @return string
     */
    public function getUpdateDate(): string;

    /**
     * Set the offerid
     *
     * @param int $offerId
     *
     * @return Offer
     */
    public function setOfferId(int $offerId): self;

    /**
     * Set the label for the offer
     *
     * @param String $label
     *
     * @return Offer
     */
    public function setLabel(string $label): self;

    /**
     * Set the image for the offer
     *
     * @param string|null $image
     *
     * @return Offer
     */
    public function setImage(?string $image): self;

    /**
     * Set the link for the offer
     *
     * @param string|null $link
     *
     * @return Offer
     */
    public function setLink(?string $link): self;

    /**
     * Set the categories for the offer
     *
     * @param string|null $categories
     *
     * @return Offer
     */
    public function setCategories(?string $categories): self;

    /**
     * Set the start date for the offer
     *
     * @param string|null $startDate
     *
     * @return Offer
     */
    public function setStartDate(?string $startDate): self;

    /**
     * Set the end date for the offer
     *
     * @param string|null $endDate
     *
     * @return Offer
     */
    public function setEndDate(?string $endDate): self;

    /**
     * Set the creation date for the offer
     *
     * @param string $creationDate
     *
     * @return Offer
     */
    public function setCreationDate(string $creationDate): self;

    /**
     * Set the update date for the offer
     *
     * @param string $updateDate
     *
     * @return Offer
     */
    public function setUpdateDate(string $updateDate): self;
}
