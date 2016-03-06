<?php

namespace Pozitim\CI\Database;

use Pozitim\CI\Database\Entity\BuildEntity;

interface BuildEntityFetcher
{
    /**
     * @param $id
     * @return BuildEntity
     */
    public function fetchOneObjectById($id);
}
