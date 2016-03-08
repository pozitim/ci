<?php

namespace Pozitim\CI\Database\MySQL;

use Pozitim\CI\Database\BuildEntityFetcher;
use Pozitim\CI\Database\Entity\BuildEntity;

class BuildEntityFetcherImpl extends MySQLAbstract implements BuildEntityFetcher
{
    /**
     * @param $id
     * @return BuildEntity
     * @throws \Pozitim\MySQL\Exception\RecordNotFoundException
     */
    public function fetchOneObjectById($id)
    {
        $sql = 'SELECT * FROM build WHERE id =:id';
        $params = [':id' => $id];
        $className = 'Pozitim\CI\Database\Entity\BuildEntity';
        return $this->getPDOHelper()->fetchOneObject($sql, $params, $className, [$this->getDi()]);
    }
}
