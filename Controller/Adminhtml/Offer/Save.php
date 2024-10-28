<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Controller\Adminhtml\Offer;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use RomainDndOffers\Offers\Model\OfferFactory;
use RomainDndOffers\Offers\Api\OfferRepositoryInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use RomainDndOffers\Offers\Model\ImageUploader;
use Magento\Framework\Serialize\SerializerInterface;

class Save extends Action
{
    /**
     * Controller Image uploader
     *
     * @var ImageUploader
     */
    protected ImageUploader $imageUploader;

    /**
     * @var DataPersistorInterface
     */
    protected DataPersistorInterface $dataPersistor;
    /**
     * @var OfferFactory
     */
    protected OfferFactory $offerFactory;
    /**
     * @var OfferRepositoryInterface
     */
    protected OfferRepositoryInterface $offerRepository;

    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;

    /**
     * @param Action\Context           $context
     * @param DataPersistorInterface   $dataPersistor
     * @param OfferFactory             $offerFactory
     * @param OfferRepositoryInterface $offerRepository
     * @param ImageUploader            $imageUploader
     * @param SerializerInterface      $serializer
     */
    public function __construct(
        Action\Context           $context,
        DataPersistorInterface   $dataPersistor,
        OfferFactory             $offerFactory,
        OfferRepositoryInterface $offerRepository,
        ImageUploader            $imageUploader,
        SerializerInterface      $serializer
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->offerFactory = $offerFactory;
        $this->offerRepository = $offerRepository;
        $this->imageUploader = $imageUploader;
        $this->serializer = $serializer;
        parent::__construct($context);
    }

    /**
     * Execute admin Save controller for the offer
     *
     * @throws LocalizedException
     */
    public function execute(): ResultInterface|ResponseInterface|Redirect
    {
        /** @phpstan-ignore-next-line */
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $data = array_filter($data, function ($value) {
                return $value !== '';
            });
            $offerId = $this->getRequest()->getParam('offer_id');

            $offer = $this->offerFactory->create();

            if ($offerId) {
                $offer = $this->offerRepository->getById($offerId);
            }
            $offer->setData($data);
            try {
                if (is_array($offer->getImage()) && $offer->getImage()[0]['file']) {
                    $imageName = $offer->getImage()[0]['file'];
                    $offer->setImage($imageName);
                    if ($this->imageUploader->movable($imageName)) {
                        $this->imageUploader->moveFileFromTmp($imageName);
                    }
                } else {
                    $offer->setImage(null);
                }
                if (is_array($offer->getCategories())) {
                    $categories = $this->serializer->serialize($data["categories"]);
                    $offer->setCategories($categories);
                } else {
                    $offer->setCategories(null);
                }
                if ($offer->getStartDate()) {
                    $date = \DateTime::createFromFormat('d/m/Y', $offer->getStartDate());
                    if ($date) {
                        $offer->setStartDate($date->format('Y-m-d 00:00:00'));
                    }
                }
                if ($offer->getEndDate()) {
                    $date = \DateTime::createFromFormat('d/m/Y', $offer->getEndDate());
                    if ($date) {
                        $offer->setEndDate($date->format('Y-m-d 00:00:00'));
                    }
                }

                $this->offerRepository->save($offer);
                $this->messageManager->addSuccessMessage(__('You saved the offer.'));
                $this->dataPersistor->clear('offer');
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }

            $this->dataPersistor->set('offer', $data);
            return $this->resultRedirectFactory->create()->setPath('*/*/edit', ['offer_id' => $offerId]);
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
