<?php
/**
 * Copyright © Marcin Materzok
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\Promotions\Api;

use Mtrzk\Promotions\Api\Data\PromotionInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Mtrzk\Promotions\Api\Data\PromotionSearchResultInterface;

interface PromotionRepositoryInterface
{
    /**
     * @param \Mtrzk\Promotions\Api\Data\PromotionInterface $entity
     * @return \Mtrzk\Promotions\Api\Data\PromotionInterface
     * @throws CouldNotSaveException
     */
    public function save(PromotionInterface $entity): PromotionInterface;

    /**
     * @param int $id
     *
     * @return \Mtrzk\Promotions\Api\Data\PromotionInterface
     *
     * @throws NoSuchEntityException
     */
    public function getById(int $id): PromotionInterface;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(?SearchCriteriaInterface $searchCriteria = null): SearchResultsInterface;

    /**
     * @param int $id
     *
     * @return void
     *
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): void;
}
