<?php

namespace ElipZis\Setting\Commands;

use ElipZis\Setting\Facades\Setting;
use ElipZis\Setting\Repositories\SettingRepository;
use Illuminate\Console\Command;

/**
 * (Re-)Sync all settings to a disc
 */
class SyncSettingsCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setting:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command to (re)sync all settings to a disc';

    /**
     * @param SettingRepository $settingRepository
     * @return int
     */
    public function handle(SettingRepository $settingRepository): int {
        $filename = config('simple-settings.sync.filename');
        $this->info("[SyncSettings] (Re-)Syncing settings to {$filename}...");
        Setting::storeConfig($filename, $settingRepository->all());
        $this->comment("[SyncSettings] (Re-)Synced settings!");

        return self::SUCCESS;
    }
}
