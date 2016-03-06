<?php

namespace Pozitim\CI\Database\MySQL;

use Pozitim\CI\Database\Entity\JobEntity;
use Pozitim\CI\Database\JobEntityFetcher;

class JobEntityFetcherImpl extends MySQLAbstract implements JobEntityFetcher
{
    /**
     * @param $id
     * @return JobEntity
     * @throws \Pozitim\MySQL\Exception\RecordNotFoundException
     */
    public function fetchOneObjectById($id)
    {
        $sql = 'SELECT * FROM job WHERE id =:id';
        $params = [':id' => $id];
        $className = 'Pozitim\CI\Database\Entity\JobEntity';
        return $this->getPDOHelper()->fetchOneObject($sql, $params, $className, [$this->getDi()]);
    }

    /**
     * @param $buildId
     * @return array
     */
    public function fetchAllObjectsByBuild($buildId)
    {
        $sql = 'SELECT * FROM job WHERE build_id =:build_id';
        $params = [':build_id' => $buildId];
        $className = 'Pozitim\CI\Database\Entity\JobEntity';
        return $this->getPDOHelper()->fetchAllObjects($sql, $params, $className, [$this->getDi()]);
    }
}
