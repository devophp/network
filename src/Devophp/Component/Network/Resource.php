<?php

namespace Devophp\Component\Network;

abstract class Resource
{
    private $id;
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    private $description;
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function getDescription()
    {
        return $this->description;
    }
    
    
    
    private $classnames = array();
    public function setClassNames($names)
    {
        $this->classnames = $names;
    }
    
    public function getClassNames()
    {
        return $this->classnames;
    }
    
    public function hasClassName($name)
    {
        foreach ($this->classnames as $classname) {
            if ($classname == $name) {
                return true;
            }
        }
        return false;
    }
}
