<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Ui\Component;

use RomainDndOffers\Offers\Model\ResourceModel\Offer\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\UrlInterface;

class FormDataProvider extends AbstractDataProvider
{
    /**
     * @var DataPersistorInterface
     */
    protected DataPersistorInterface $dataPersistor;
    /**
     * @var SerializerInterface
     */
    protected SerializerInterface $serializer;
    /**
     * @var UploaderFactory
     */
    protected UploaderFactory $uploaderFactory;
    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlBuilder;

    /**
     * @param String                 $name
     * @param String                 $primaryFieldName
     * @param String                 $requestFieldName
     * @param CollectionFactory      $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param SerializerInterface    $serializer
     * @param UploaderFactory        $uploaderFactory
     * @param UrlInterface           $urlBuilder
     * @param array<mixed>           $meta
     * @param array<mixed>           $data
     */
    public function __construct(
        string                 $name,
        string                 $primaryFieldName,
        string                 $requestFieldName,
        CollectionFactory      $collectionFactory,
        DataPersistorInterface $dataPersistor,
        SerializerInterface    $serializer,
        UploaderFactory        $uploaderFactory,
        UrlInterface           $urlBuilder,
        array                  $meta = [],
        array                  $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->serializer = $serializer;
        $this->uploaderFactory = $uploaderFactory;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Retrieves data.
     *
     * @return array<int, array<string, mixed>> The processed data of items in the collection, keyed by item ID.
     */
    public function getData(): array
    {
        $result = [];
        foreach ($this->collection->getItems() as $item) {
            $data = $item->getData();
            // gestion des catégories on déserialise afin de récupérer l'array des ids des catégories
            if (!empty($data['categories'])) {
                $data['categories'] = $this->serializer->unserialize($data['categories']);
            }

            // gestion des images on reconstruit le chemin de l'image
            if (!empty($data['image'])) {
                $imagePath = $this->getImageUrl($data['image']);
                $data['image'] = [
                    [
                        'url' => $imagePath,
                        'type' => 'image',
                        'file' => $data['image']
                    ]
                ];
            }
            $item->setData($data);
            /** @phpstan-ignore-next-line */
            $result[$item->getId()] = $item->getData();
        }
        return $result;
    }

    /**
     * Get image URL from the media path
     *
     * @param string $imagePath
     *
     * @return string
     */
    private function getImageUrl($imagePath)
    {
        return $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]) . 'offer/images/' . $imagePath;
    }
}
