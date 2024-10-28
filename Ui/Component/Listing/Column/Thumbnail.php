<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Ui\Component\Listing\Column;

use Magento\Catalog\Helper\Image;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Filesystem;

class Thumbnail extends Column
{
    /**
     *
     */
    public const ALT_FIELD = 'title';

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var Filesystem
     */
    protected Filesystem $_filesystem;

    /**
     * @var Image
     */
    protected Image $imageHelper;

    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlBuilder;

    /**
     * @param ContextInterface      $context
     * @param UiComponentFactory    $uiComponentFactory
     * @param Image                 $imageHelper
     * @param UrlInterface          $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param Filesystem            $filesystem
     * @param array<mixed>          $components
     * @param array<mixed>          $data
     */
    public function __construct(
        ContextInterface      $context,
        UiComponentFactory    $uiComponentFactory,
        Image                 $imageHelper,
        UrlInterface          $urlBuilder,
        StoreManagerInterface $storeManager,
        Filesystem            $filesystem,
        array                 $components = [],
        array                 $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
        $this->_filesystem = $filesystem;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array<mixed> $dataSource
     *
     * @return array<mixed>
     * @throws NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $url = '';
                if ($item[$fieldName] != '') {
                    /** @phpstan-ignore-next-line */
                    $url = $this->storeManager->getStore()->getBaseUrl(
                        UrlInterface::URL_TYPE_MEDIA
                    ) . 'offer/images/' . $item[$fieldName];
                }
                $item[$fieldName . '_src'] = $url;
                $item[$fieldName . '_alt'] = $this->getAlt($item) ?: '';
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'offers/offer/edit',
                    ['offer_id' => $item['offer_id']]
                );
                $item[$fieldName . '_orig_src'] = $url;
            }
        }

        return $dataSource;
    }

    /**
     * Get Alt attribut image
     *
     * @param array<mixed> $row
     *
     * @return null|string
     */
    protected function getAlt(array $row): ?string
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return $row[$altField] ?? null;
    }
}
