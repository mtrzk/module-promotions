<?php
/**
 * Copyright © Marcin Materzok
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\Promotions\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface PromotionGroupSearchResultInterface
 */
interface PromotionGroupSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Mtrzk\Promotions\Api\Data\PromotionGroupInterface[]
     */
    public function getItems(): array;

    /**
     * @param \Mtrzk\Promotions\Api\Data\PromotionGroupInterface[] $items
     *
     * @return \Mtrzk\Promotions\Api\Data\PromotionGroupSearchResultInterface
     */
    public function setItems(array $items): PromotionGroupSearchResultInterface;
}
