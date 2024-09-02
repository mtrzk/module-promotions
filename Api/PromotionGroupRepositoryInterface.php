<?php
/**
 * Copyright © Marcin Materzok
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\Promotions\Api;

use Mtrzk\Promotions\Api\Data\PromotionGroupInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface PromotionGroupRepositoryInterface
{
    /**
     * @param \Mtrzk\Promotions\Api\Data\PromotionGroupInterface $entity
     * @return \Mtrzk\Promotions\Api\Data\PromotionGroupInterface
     * @throws CouldNotSaveException
     */
    public function save(PromotionGroupInterface $entity): PromotionGroupInterface;

    /**
     * @param int $id
     *
     * @return \Mtrzk\Promotions\Api\Data\PromotionGroupInterface
     *
     * @throws NoSuchEntityException
     */
    public function getById(int $id): PromotionGroupInterface;

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
