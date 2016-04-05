<?php

namespace Margo\Entity;

/**
 * Created by PhpStorm.
 * User: Dinam
 * Date: 05-Apr-16
 * Time: 2:47 PM
 */

class Category
{
    private $categId;
    private $categName;

    /**
     * Category constructor.
     * @param $categName
     * @param $categId
     */
    public function __construct($categName, $categId)
    {
        $this->categName = $categName;
        $this->categId = $categId;
    }

    /**
     * @return mixed
     */
    public function getCategId()
    {
        return $this->categId;
    }

    /**
     * @param mixed $categId
     */
    public function setCategId($categId)
    {
        $this->categId = $categId;
    }

    /**
     * @return mixed
     */
    public function getCategName()
    {
        return $this->categName;
    }

    /**
     * @param mixed $categName
     */
    public function setCategName($categName)
    {
        $this->categName = $categName;
    }

}