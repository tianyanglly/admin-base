<?php

namespace AdminCommon\Actions\Row;

use Encore\Admin\Actions\RowAction;

class Href extends RowAction
{
    public $name;

    /**
     * 跳转地址
     * @var string
     */
    public $url;

    /**
     * 是否开启新窗口
     * @var bool
     */
    public $blank = true;

    public function __construct($name, $url, $blank = true)
    {
        $this->name = $name;
        $this->url = $url;
        $this->blank = $blank;
        parent::__construct();
    }

    public function render()
    {
        if ($this->blank) {
            return "<a href='{$this->url}' target='_blank'>{$this->name}</a>";
        }
        return "<a href='{$this->url}'>{$this->name}</a>";
    }
}