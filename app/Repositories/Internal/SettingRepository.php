<?php

namespace App\Repositories\Internal;

use App\Models\Internal\Setting;

class SettingRepository
{
    protected $model;

    public function __construct(
        Setting $model
    ) {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function update($request, $data)
    {
        $setting = $this->model->where('key', $request)->first();
        if ($setting) {
            $setting->update(['value' => $data]);

            return $setting;
        }

        return null;
    }
}
