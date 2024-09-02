<?php
/**
 * Copyright © Marcin Materzok
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\Promotions\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Mtrzk\Promotions\Api\Data\PromotionInterface;

class Promotion extends AbstractDb
{
    public const PROMOTION_ID = 'promotion_id';
    public const GROUP_ID = 'group_id';
    public const MAIN_TABLE = 'mtrzk_promotions';
    public const RELATED_TABLE = 'mtrzk_promotions_group_related';

    public const ID_FIELD_NAME = 'entity_id';

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }

    protected function _afterLoad(AbstractModel $object)
    {
        $groups = $this->lookupGroupIds((int)$object->getId());
        $object->setData(PromotionInterface::GROUP_IDS, $groups);

        return parent::_afterLoad($object);
    }

    /**
     * @param int $id
     * @return array
     */
    public function lookupGroupIds(int $id)
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(['mpgr' => $this->getTable(self::RELATED_TABLE )], self::GROUP_ID)
            ->where(sprintf(
                "mpgr.%s = :%s",
                self::PROMOTION_ID,
                self::PROMOTION_ID
            ));

        return $connection->fetchCol($select, [self::PROMOTION_ID => (int)$id]);
    }
}
