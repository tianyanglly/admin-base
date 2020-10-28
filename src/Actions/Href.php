<?php

namespace AdminCommon\Actions\Row;

use AdminBase\Models\AdminBaseModel;
use Encore\Admin\Actions\RowAction;
use Illuminate\Http\Request;

class Href extends RowAction
{
    public $name;

    public $url;

    public function __construct($name, $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    public function render()
    {
        return "<a href='{$this->url}' target='_blank'>{$this->name}</a>";
    }
}