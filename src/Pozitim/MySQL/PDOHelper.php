<?php

namespace Pozitim\MySQL;

use OU\DI;
use Pozitim\MySQL\Exception\RecordNotFoundException;

class PDOHelper
{
    /**
     * @var DI
     */
    protected $di;

    /**
     * @var SQLProfiler
     */
    protected $profiler;

    /**
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    public function beginTransaction()
    {
        $this->getPDO()->beginTransaction();
    }

    public function rollBack()
    {
        $this->getPDO()->rollBack();
    }

    public function commit()
    {
        $this->getPDO()->commit();
    }

    public function truncate($table)
    {
        $sql = 'TRUNCATE TABLE ' . $table;
        $stmt = $this->execute($sql);
        $stmt->closeCursor();
    }

    /**
     * @param $sql
     * @param array $params
     * @return int
     */
    public function delete($sql, array $params = array())
    {
        return $this->update($sql, $params);
    }

    /**
     * @param $sql
     * @param array $params
     * @return int
     */
    public function update($sql, array $params = array())
    {
        $stmt = $this->execute($sql, $params);
        $affectedRows = $stmt->rowCount();
        $stmt->closeCursor();
        return $affectedRows;
    }

    /**
     * @param $sql
     * @param array $params
     * @return int
     */
    public function insert($sql, array $params = array())
    {
        $stmt = $this->execute($sql, $params);
        $lastInsertId = $this->getPDO()->lastInsertId();
        $stmt->closeCursor();
        return $lastInsertId;
    }

    /**
     * @param $sql
     * @param array $params
     * @param int $index
     * @return mixed
     */
    public function fetchColumn($sql, array $params = array(), $index = 0)
    {
        $stmt = $this->execute($sql, $params);
        $result = $stmt->fetchColumn($index);
        $stmt->closeCursor();
        return $result;
    }

    /**
     * @param $sql
     * @param array $params
     * @param int $index
     * @return mixed
     */
    public function fetchAllColumn($sql, array $params = array(), $index = 0)
    {
        $stmt = $this->execute($sql, $params);
        $result = $stmt->fetchAll(\PDO::FETCH_COLUMN, $index);
        $stmt->closeCursor();
        return $result;
    }

    /**
     * @param $sql
     * @param array $params
     * @return mixed
     * @throws RecordNotFoundException
     * @throws \Exception
     */
    public function fetchOne($sql, array $params = [])
    {
        $stmt = $this->execute($sql, $params);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if (!$result) {
            throw new RecordNotFoundException();
        }
        return $result;
    }

    /**
     * @param $sql
     * @param array $params
     * @param string $class
     * @param array $classParams
     * @return mixed
     * @throws RecordNotFoundException
     * @throws \Exception
     */
    public function fetchOneObject($sql, array $params = [], $class = '\stdClass', array $classParams = [])
    {
        $stmt = $this->execute($sql, $params);
        $result = $stmt->fetchObject($class, $classParams);
        $stmt->closeCursor();
        if (!$result) {
            throw new RecordNotFoundException();
        }
        return $result;
    }

    /**
     * @param $sql
     * @param array $params
     * @return array
     */
    public function fetchAll($sql, array $params = [])
    {
        $stmt = $this->execute($sql, $params);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $results;
    }

    /**
     * @param $sql
     * @param array $params
     * @param string $class
     * @param array $classParams
     * @return array
     */
    public function fetchAllObjects($sql, array $params = [], $class = '\stdClass', array $classParams = [])
    {
        $stmt = $this->execute($sql, $params);
        $results = $stmt->fetchAll(\PDO::FETCH_CLASS, $class, $classParams);
        $stmt->closeCursor();
        return $results;
    }

    /**
     * @param $sql
     * @param array $params
     * @return \PDOStatement
     * @throws \Exception
     */
    public function execute($sql, array $params = array())
    {
        if ($this->hasProfiler()) {
            $this->getProfiler()->startProfile($sql, $params);
        }
        try {
            $stmt = $this->tryExecute($sql, $params);
        } catch (\Exception $exception) {
            if ($this->hasProfiler()) {
                $this->getProfiler()->endProfile($sql, $params);
            }
            throw $exception;
        }
        if ($this->hasProfiler()) {
            $this->getProfiler()->endProfile($sql, $params);
        }
        return $stmt;
    }

    /**
     * @param $sql
     * @param array $params
     * @return \PDOStatement
     */
    protected function tryExecute($sql, array $params = [])
    {
        $stmt = $this->getPDO()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * @return bool
     */
    protected function hasProfiler()
    {
        return $this->profiler != null;
    }

    /**
     * @return SQLProfiler
     * @throws \Exception
     */
    protected function getProfiler()
    {
        return $this->profiler;
    }

    /**
     * @param SQLProfiler $profiler
     */
    public function setProfiler($profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * @return \PDO
     * @throws \Exception
     */
    protected function getPDO()
    {
        return $this->di->get('pdo');
    }
}
