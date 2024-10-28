<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Block\Adminhtml\Edit;

use RomainDndOffers\Offers\Api\OfferRepositoryInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Class GenericButton
 *
 * This class provides functionalities to retrieve offer ID and generate URLs
 * based on the given route and parameters.
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected Context $context;

    /**
     * @var OfferRepositoryInterface
     */
    protected OfferRepositoryInterface $offerRepository;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param Context                  $context
     * @param OfferRepositoryInterface $offerRepository
     * @param LoggerInterface          $logger
     */
    public function __construct(
        Context                  $context,
        OfferRepositoryInterface $offerRepository,
        LoggerInterface          $logger
    ) {
        $this->context = $context;
        $this->offerRepository = $offerRepository;
        $this->logger = $logger;
    }

    /**
     * Return CMS block ID
     *
     * @return int|null
     * @throws LocalizedException
     */
    public function getOfferId(): ?int
    {
        $offerId = $this->context->getRequest()->getParam('offer_id');
        if ($offerId === null) {
            return null;
        }

        if (!is_numeric($offerId)) {
            return null;
        }
        try {
            return $this->offerRepository->getById(
                (int)$offerId
            )->getOfferId();
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
        }
        return null;
    }
    // phpcs:disable Generic,Squiz,Magento2
    /**
     * Generate url by route and parameters
     * phpcs:ignore
     *
     * @param string                         $route
     * @param array<string, string|int|null> $params
     *
     * @return  string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
