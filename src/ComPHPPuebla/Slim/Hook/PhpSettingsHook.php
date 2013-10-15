<?php
namespace ComPHPPuebla\Slim\Hook;

class PhpSettingsHook
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return void
     */
    public function __invoke()
    {
        $this->setPhpIniSettings();
    }

    /**
     * @return void
     */
    public function setPhpIniSettings()
    {
        foreach ($this->settings as $iniKey => $iniValue) {
            ini_set($iniKey, $iniValue);
        }
    }
}
