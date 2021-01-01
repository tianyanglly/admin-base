<?php

namespace AdminBase\Actions;

use Encore\Admin\Actions\Action;

/**
 * 提示
 * Class Tips
 * @package AdminBase\Actions
 */
class Tips extends Action
{
    protected $selector = 'tips';

    private $value;

    private $color;

    public function __construct($value, $color = 'red')
    {
        parent::__construct();
        $this->value = $value;
        $this->color = $color;
    }

    public function html()
    {
        return "<a style='color: ".$this->color."'>$this->value</a>";
    }
}