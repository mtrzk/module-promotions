<?php
/**
 * Copyright © Marcin Materzok
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\Promotions\Api\Data;

use DateTime;
use Magento\Sales\Api\Data\OrderInterface;

interface PromotionInterface
{
    public const ENTITY_ID = 'entity_id';
    public const NAME = 'name';
    public const GROUP_IDS = 'group_ids';
    public const GROUPS = 'groups';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /**
     * @return int|null Promotion ID.
     */
    public function getId();

    /**
     * @param int $entityId
     * @return PromotionInterface
     */
    public function setId($entityId);

    /**
     * @return \Mtrzk\Promotions\Api\Data\PromotionGroupInterface[]
     */
    public function getGroups(): array;

    /**
     * @return int[]
     */
    public function getGroupIds(): ?array;

    /**
     * @param int[] $promotionGroupIds
     *
     * @return \Mtrzk\Promotions\Api\Data\PromotionInterface
     */
    public function setGroupIds(array $promotionGroupIds): PromotionInterface;

    /**
     * @param string $name
     *
     * @return \Mtrzk\Promotions\Api\Data\PromotionInterface
     */
    public function setName(string $name): PromotionInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return null|string
 */
    public function getCreatedAt(): ?string;

    /**
     * @return null|string
 */
    public function getUpdatedAt(): ?string;
}
