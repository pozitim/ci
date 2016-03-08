<?php

namespace Pozitim\CI\Database;

use Pozitim\CI\Database\Entity\BuildEntity;

interface BuildEntitySaver
{
    /**
     * @param BuildEntity $entity
     */
    public function insert(BuildEntity $entity);

    /**
     * @param BuildEntity $entity
     */
    public function updateCompletedDate(BuildEntity $entity);
}
