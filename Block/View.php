<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Block;

use RomainDndOffers\Offers\Api\Data\OfferInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use RomainDndOffers\Offers\Api\OfferRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class View
 *
 * Handles the view layer for offers, including retrieving offers by ID
 * and managing messages.
 */
class View extends Template
{
    /**
     * @var int
     */
    protected int $offerId;
    /**
     * @var OfferRepositoryInterface
     */
    protected OfferRepositoryInterface $offerRepository;
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;
    /**
     * @var ManagerInterface
     */
    private ManagerInterface $messageManager;

    /**
     * @param Template\Context         $context
     * @param OfferRepositoryInterface $offerRepository
     * @param RequestInterface         $request
     * @param ManagerInterface         $messageManager
     * @param array<mixed>             $data
     */
    public function __construct(
        Template\Context         $context,
        OfferRepositoryInterface $offerRepository,
        RequestInterface         $request,
        ManagerInterface         $messageManager,
        array                    $data = []
    ) {
        $this->offerRepository = $offerRepository;
        $this->request = $request;
        $this->messageManager = $messageManager;
        parent::__construct($context, $data);
    }

    /**
     * Retrieves an offer by its ID.
     *
     * @return ?OfferInterface
     * @throws LocalizedException
     */
    public function getOfferById(): ?OfferInterface
    {
        $offerId = $this->request->getParam("id");
        $offer = null;
        if ($offerId) {
            try {
                $offer = $this->offerRepository->getById($offerId);
            } catch (NoSuchEntityException $e) {
                $this->_logger->error(
                    'l\'offerId ' . $offerId . ' n\'existe pas',
                    [
                        'exception' => $e
                    ]
                );
                $this->messageManager->addErrorMessage(__('The requested offer does not exist.'));
            }
            return $offer;
        }
        return null;
    }

    /**
     * Retrieves the message manager instance.
     *
     * @return ManagerInterface
     */
    public function getMessageManager(): ManagerInterface
    {
        return $this->messageManager;
    }
}
