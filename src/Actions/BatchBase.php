<?php


namespace AdminBase\Actions;


use Encore\Admin\Actions\BatchAction;
use Illuminate\Http\Request;

/**
 * 批量操作处理
 * Class BatchBase
 * @package AdminBase\Actions
 */
class BatchBase extends BatchAction
{
    protected $slug;

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function retrieveModel(Request $request)
    {
        if (!$key = $request->get('_key')) {
            return false;
        }

        $modelClass = str_replace('_', '\\', $request->get('_model'));
        if (is_numeric($key)) {
            $key = [$key];
        }elseif (is_string($key)) {
            $key = explode(',', $key);
        }
        return $modelClass::findOrFail($key);
    }
}