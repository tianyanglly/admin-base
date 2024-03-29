<?php


namespace AdminBase\Controllers\Admin;

use AdminBase\Actions\LogInput;
use AdminBase\Traits\Search;
use Encore\Admin\Auth\Database\OperationLog;
use Encore\Admin\Grid;
use Illuminate\Support\Arr;

/**
 * 操作日志
 * Class LogController
 * @package AdminBase\Controllers\Admin
 */
class LogController extends \Encore\Admin\Controllers\LogController
{
    use Search;

    /**
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OperationLog());
        $grid->disableExport();
        $grid->disableCreateButton();
        $grid->model()->orderBy('id', 'DESC');

        $grid->column('id', 'ID')->sortable();
        $grid->column('path', '路径');
        $grid->column('user.name', '用户名称');
        $grid->column('ip', 'IP地址')->label('primary');
        $grid->column('method')->display(function ($method) {
            $color = Arr::get(OperationLog::$methodColors, $method, 'grey');
            return "<span class=\"badge bg-$color\">$method</span>";
        });
        $grid->column('do', '操作');
        $grid->column('created_at', trans('admin.created_at'));
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableView();
            $actions->disableDelete();
            //模态框 form 表单提交
            $actions->add(new LogInput());
        });
        $this->search($grid);
        return $grid;
    }

    /**
     * @inheritDoc
     */
    protected function filter(Grid\Filter &$filter)
    {
        $filter->column(1 / 3, function (Grid\Filter $filter) {
            $filter->equal('user_id', '用户ID');
            $filter->like('path', '路径');
        });
        $filter->column(1 / 3, function (Grid\Filter $filter) {
            $filter->like('do', '操作');
        });
        $filter->column(1 / 3, function (Grid\Filter $filter) {
            $filter->like('input', '操作内容');
        });
    }
}