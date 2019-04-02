<?php

namespace coolcoins\storage\model;

/**
 * @property int $id
 * @property string $owner A unique mix of the owner id and type.
 * @property int $ownerId
 * @property int $ownerType
 * @property int $balance
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Account extends DatabaseModel
{
}