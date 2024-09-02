<?php
/**
 * Copyright © Marcin Materzok
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\Promotions\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface PromotionSearchResultInterface
 */
interface PromotionSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Mtrzk\Promotions\Api\Data\PromotionInterface[]
     */
    public function getItems(): array;

    /**
     * @param \Mtrzk\Promotions\Api\Data\PromotionInterface[] $items
     *
     * @return \Mtrzk\Promotions\Api\Data\PromotionSearchResultInterface
     */
    public function setItems(array $items): PromotionSearchResultInterface;
}
