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
     * Return all settings in key => value format
     *
     * @return array
     */
    public function all()
    {
        return [
            'settings' => $this->repository->all(),
        ];
    }

    /**
     * Return the given setting keys model values
     *
     * @param string $setting
     * @return array
     */
    public function get(string $setting)
    {
        return [
            $setting => $this->repository->getSetting($setting),
        ];
    }

    /**
     * Return the given setting keys value
     *
     * @param string $setting
     * @return array
     */
    public function value(string $setting)
    {
        return [
            $setting => $this->repository->getValue($setting),
        ];
    }
}
