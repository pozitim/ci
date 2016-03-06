<?php

namespace Pozitim\CI\Database\MySQL;

use Pozitim\CI\Database\Entity\JobEntity;
use Pozitim\CI\Database\JobEntitySaver;

class JobEntitySaverImpl extends MySQLAbstract implements JobEntitySaver
{
    /**
     * @param JobEntity $entity
     */
    public function insert(JobEntity $entity)
    {
        $sql = 'INSERT INTO job (build_id, name, started_date) VALUES (:build_id, :name, :started_date)';
        $params = [
            ':build_id' => $entity->build_id,
            ':name' => $entity->name,
            ':started_date' => $entity->started_date
        ];
        $entity->id = $this->getPDOHelper()->insert($sql, $params);
    }

    /**
     * @param JobEntity $entity
     * @param $appendText
     */
    public function appendOutput(JobEntity $entity, $appendText)
    {
        $sql = 'UPDATE job SET output = CONCAT(IFNULL(output, ""), :append_text) WHERE id =:id';
        $params = [
            ':id' => $entity->id,
            ':append_text' => $appendText
        ];
        $this->getPDOHelper()->update($sql, $params);
    }

    /**
     * @param JobEntity $entity
     * @param string $errorMessage
     */
    public function updateExitCode(JobEntity $entity, $errorMessage = '')
    {
        $sql = 'UPDATE job SET exit_code =:exit_code, completed_date =:completed_date WHERE id =:id';
        $params = [
            ':id' => $entity->id,
            ':exit_code' => $entity->exit_code,
            ':completed_date' => $entity->completed_date
        ];
        $this->getPDOHelper()->update($sql, $params);
    }
}
