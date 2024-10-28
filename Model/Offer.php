<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Model;

use Magento\Framework\Model\AbstractModel;
use RomainDndOffers\Offers\Api\Data\OfferInterface;

/**
 * Offer Model
 */
class Offer extends AbstractModel implements OfferInterface
{
    /**
     * Get the offerId
     *
     * @return int
     */
    public function getOfferId(): int
    {
        return $this->getData(self::OFFER_ID);
    }

    /**
     * Get the label of the offer
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->getData(self::LABEL);
    }

    /**
     * Get the image of the offer
     *
     * @return mixed
     */
    public function getImage(): mixed
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * Get the link of the offer
     *
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->getData(self::LINK);
    }

    /**
     * Get the categories of the label
     *
     * @return mixed
     */
    public function getCategories(): mixed
    {
        return $this->getData(self::CATEGORIES);
    }

    /**
     * Retrieves the start date of an entity.
     *
     * @return string|null The start date value.
     */
    public function getStartDate(): ?string
    {
        return $this->getData(self::START_DATE);
    }

    /**
     * Retrieves the end date.
     *
     * @return string|null
     */
    public function getEndDate(): ?string
    {
        return $this->getData(self::END_DATE);
    }

    /**
     * Get the offer creation date
     *
     * @return string|null
     */
    public function getCreationDate(): ?string
    {
        return $this->getData(self::CREATION_DATE);
    }

    /**
     * Get the update date of the offer
     *
     * @return string
     */
    public function getUpdateDate(): string
    {
        return $this->getData(self::UPDATE_DATE);
    }

    /**
     * Set the offerId
     *
     * @param int $offerId
     *
     * @return Offer
     */
    public function setOfferId(int $offerId): Offer
    {
        return $this->setData(self::OFFER_ID, $offerId);
    }

    /**
     * Set the label of the offer
     *
     * @param string $label
     *
     * @return Offer
     */
    public function setLabel(string $label): Offer
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * Set the image of the offer
     *
     * @param string|null $image
     *
     * @return Offer
     */
    public function setImage(?string $image): Offer
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * Set link of the offer
     *
     * @param string|null $link
     *
     * @return Offer
     */
    public function setLink(?string $link): Offer
    {
        return $this->setData(self::LINK, $link);
    }

    /**
     * Set categories of the offer
     *
     * @param string|null $categories
     *
     * @return Offer
     */
    public function setCategories(?string $categories): Offer
    {
        return $this->setData(self::CATEGORIES, $categories);
    }

    /**
     * Set StartDate of the offer
     *
     * @param string|null $startDate
     *
     * @return Offer
     */
    public function setStartDate(?string $startDate): Offer
    {
        return $this->setData(self::START_DATE, $startDate);
    }

    /**
     * Set EndDate of the offer
     *
     * @param string|null $endDate
     *
     * @return Offer
     */
    public function setEndDate(?string $endDate): Offer
    {
        return $this->setData(self::END_DATE, $endDate);
    }

    /**
     * Set creationDate of the offer
     *
     * @param string $creationDate
     *
     * @return Offer
     */
    public function setCreationDate(string $creationDate): Offer
    {
        return $this->setData(self::CREATION_DATE, $creationDate);
    }

    /**
     * Set update date of the offer
     *
     * @param string $updateDate
     *
     * @return Offer
     */
    public function setUpdateDate(string $updateDate): Offer
    {
        return $this->setData(self::UPDATE_DATE, $updateDate);
    }

    /**
     * Define resource model
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Offer::class);
    }
}
