<?php

namespace Pozitim\CI\Database\MySQL;

use Pozitim\CI\Database\BuildEntitySaver;
use Pozitim\CI\Database\Entity\BuildEntity;

class BuildEntitySaverImpl extends MySQLAbstract implements BuildEntitySaver
{
    /**
     * @param BuildEntity $entity
     */
    public function updateCompletedDate(BuildEntity $entity)
    {
        $sql = 'UPDATE build SET completed_date =:completed_date WHERE id =:id';
        $params = [
            ':id' => $entity->id,
            ':completed_date' => $entity->completed_date
        ];
        $this->getPDOHelper()->update($sql, $params);
    }

    /**
     * @param BuildEntity $entity
     */
    public function insert(BuildEntity $entity)
    {
        $sql = 'INSERT INTO build (started_date)  VALUES (:started_date)';
        $params = [':started_date' => $entity->started_date];
        $entity->id = $this->getPDOHelper()->insert($sql, $params);
    }
}
