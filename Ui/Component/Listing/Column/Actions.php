<?php
/**
 * Copyright © Romain ITOFO. All rights reserved.
 * Author: Romain ITOFO
 * Contact: romdev34@gmail.com
 */

namespace RomainDndOffers\Offers\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\Escaper;

class Actions extends Column
{
    /**
     * Url path for edit action
     */
    public const URL_PATH_EDIT = 'offers/offer/edit';

    /**
     * Url path for delete action
     */
    public const URL_PATH_DELETE = 'offers/offer/delete';

    /**
     * Url path for view action
     */
    public const URL_PATH_VIEW = 'offers/offer/view';

    /**
     * @var UrlInterface
     */
    protected UrlInterface $urlBuilder;

    /**
     * @var Escaper
     */
    protected Escaper $escaper;

    /**
     * Constructor
     *
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface       $urlBuilder
     * @param Escaper            $escaper
     * @param array<mixed>       $components
     * @param array<mixed>       $data
     */
    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface       $urlBuilder,
        Escaper            $escaper,
        array              $components = [],
        array              $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->escaper = $escaper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array<mixed> $dataSource
     *
     * @return array<mixed>
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['offer_id'])) {
                    $name = $this->escaper->escapeHtml($item['label']);
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                ['offer_id' => $item['offer_id']]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                ['offer_id' => $item['offer_id']]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete "%1"', $name),
                                'message' => __('Are you sure you want to delete the offer "%1"?', $name)
                            ]
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }
}
