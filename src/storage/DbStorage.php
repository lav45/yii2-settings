<?php
/**
 * @link https://github.com/LAV45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\storage;

use Yii;
use yii\db\Query;
use yii\db\Connection;
use yii\di\Instance;
use yii\base\BaseObject;

/**
 * Class DbStorage
 * @package lav45\settings\storage
 */
class DbStorage extends BaseObject implements StorageInterface
{
    /**
     * @var Connection|array|string
     */
    public $db = 'db';
    /**
     * @var string
     */
    public $tableName = '{{%settings}}';

    /**
     * Initializes the application component.
     */
    public function init()
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::class);
    }

    /**
     * @param string $key
     * @return false|null|string
     */
    public function getValue($key)
    {
        return (new Query())
            ->select(['data'])
            ->from($this->tableName)
            ->where(['id' => $key])
            ->limit(1)
            ->createCommand($this->db)
            ->queryScalar();
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function setValue($key, $value)
    {
        $exists = (new Query())
            ->from($this->tableName)
            ->where(['id' => $key])
            ->exists($this->db);

        $query = (new Query())->createCommand($this->db);

        try {
            if ($exists) {
                $query->update($this->tableName, ['data' => $value], ['id' => $key])->execute();
            } else {
                $query->insert($this->tableName, ['id' => $key, 'data' => $value])->execute();
            }
        } catch (\Exception $e) {
            Yii::error(get_class($e) . '[' . $e->getCode() . '] ' . $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function deleteValue($key)
    {
        $result = (new Query())->createCommand($this->db)
            ->delete($this->tableName, ['id' => $key])
            ->execute();

        return $result > 0;
    }
}
