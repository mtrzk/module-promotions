<?php
/**
 * Copyright © Marcin Materzok
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\Promotions\Api\Data;

interface PromotionGroupInterface
{
    public const ENTITY_ID = 'entity_id';
    public const NAME = 'name';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /**
     * @return int|null Promotion item ID.
     */
    public function getId();

    /**
     * @param int $entityId
     * @return \Mtrzk\Promotions\Api\Data\PromotionGroupInterface
     */
    public function setId($entityId): PromotionGroupInterface;

    /**
     * @return string|null Name.
     */
    public function getName(): ?string;

    /**
     * @param string $name
     * @return \Mtrzk\Promotions\Api\Data\PromotionGroupInterface
     */
    public function setName(string $name): PromotionGroupInterface;

    /**
     * @return null|string
     */
    public function getCreatedAt(): ?string;

    /**
     * @return null|string
 */
    public function getUpdatedAt(): ?string;
}
