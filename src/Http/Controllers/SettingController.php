<?php

namespace ElipZis\Setting\Http\Controllers;

use ElipZis\Setting\Repositories\SettingRepository;
use Illuminate\Routing\Controller;

/**
 * Controller-based access to our configured settings
 */
class SettingController extends Controller
{
    /**
     * @var SettingRepository
     */
    protected SettingRepository $repository;

    /**
     *
     * @param SettingRepository $settingRepository
     */
    public function __construct(SettingRepository $settingRepository)
    {
        $this->repository = $settingRepository;
    }

    /**
     * @param string $setting
     * @return array
     */
    public function getSetting(string $setting)
    {
        return [
            'setting' => $this->repository->getSetting($setting),
        ];
    }

    /**
     * @param string $setting
     * @return array
     */
    public function getValue(string $setting)
    {
        return [
            'setting' => $this->repository->getValue($setting),
        ];
    }
}
