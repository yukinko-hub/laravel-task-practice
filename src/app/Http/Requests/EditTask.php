<?php

namespace App\Http\Requests;

use App\Task;
use Illuminate\Validation\Rule;
//use Illuminate\Foundation\Http\FormRequest;

class EditTask extends CreateTask
{
    public function rules()
    {
        $rule = parent::rules();

        $status_rule = Rule::in(array_keys(Task::STATUS));
        // -> 'in(1, 2, 3)' を出力する

        return $rule + [
            'status' => 'required|' . $status_rule,
            // 'status' => 'required|in(1, 2, 3)',
        ];
    }

    public function attributes()
    {
        $attributes = parent::attributes();

        return $attributes + [
            'status' => '状態',
        ];
    }

    public function messages()
    {
        $messages = parent::messages();

        $status_labels = array_map(function($item) {
            return $item['label'];
        }, Task::STATUS);

        $status_labels = implode($status_labels, '、');

        return $messages + [
            'status.in' => ':attribute には ' . $status_labels. ' のいずれかを指定してください。',
        ];
    }
}
