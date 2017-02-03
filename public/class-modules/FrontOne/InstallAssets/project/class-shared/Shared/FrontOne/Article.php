<?php

namespace Shared\FrontOne;

class Article
{
    private $content;
    private $anchor; // passed via the url
    private $label; // shown in the menu
    private $position; // position in the menu
    private $_isProtected; // prevent its deletion from gui
    private $_isActive; // will show in menu?
    private $_isDynamic; // has dynamic (php) content


    public function __construct()
    {
        $this->content = '';
        $this->anchor = '';
        $this->label = '';
        $this->position = 0;
        $this->_isProtected = true;
        $this->_isActive = true;
        $this->_isDynamic = false;
    }

    /**
     * @return string
     * If dynamic, returns the fileName of the content to execute later
     */
    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnchor()
    {
        return $this->anchor;
    }

    public function setAnchor($anchor)
    {
        $this->anchor = $anchor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return bool
     */
    public function isProtected()
    {
        return $this->_isProtected;
    }

    public function setIsProtected($isProtected)
    {
        $this->_isProtected = $isProtected;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->_isActive;
    }

    public function setIsActive($isActive)
    {
        $this->_isActive = $isActive;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDynamic()
    {
        return $this->_isDynamic;
    }

    public function setIsDynamic($isDynamic)
    {
        $this->_isDynamic = $isDynamic;
        return $this;
    }



}


