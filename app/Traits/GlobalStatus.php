<?php

namespace App\Traits;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait GlobalStatus
{
    public static function changeStatus($id, $column = 'status')
    {

        $modelName = get_class();
        $query     = $modelName::findOrFail($id);
        $column = strtolower($column);
        if ($query->$column == Status::ENABLE) {
            $query->$column = Status::DISABLE;
        } else {
            $query->$column = Status::ENABLE;
        }
        $message       = keyToTitle($column). ' changed successfully';

        $query->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }


    public function statusBadge(): Attribute
    {
        return new Attribute(function(){
            $html = '';
            if ($this->status == Status::ENABLE) {
                $html = '<span class="badge badge--success">' . trans('Enabled') . '</span>';
            } else {
                $html = '<span><span class="badge badge--warning">' . trans('Disabled') . '</span></span>';
            }
            return $html;
        }
        );
    }


    public function scopeActive($query)
    {
        return $query->where('status', Status::ENABLE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', Status::DISABLE);
    }
}
