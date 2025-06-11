<?php

namespace NinjaForms\CfConversionTool\Handlers;

/**
 * Load configuration files as arrays and present on demand
 *
 * Instantiated with a plugin's root directory, Configure has a method called
 * `configure` that accepts the name of a configuration file stored in the root
 * directory's `Config` folder.  By default, it will look for a php file
 * delivering an array, but an optional parameter for extension enables the use
 * of a JSON file, which will be converted into an array.  The file is loaded
 * once and stored such that future requests for the same configuration do not
 * need an additional file load.
 */
class Configure
{

    /**
     *  Root directory
     * @var string
     */
    protected $rootDirectory;

    public function __construct(string $rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
    }

    
    /**
     * Return a configured array, storing configuration for reuse
     * 
     * @param string $configurationName
     * @param string|null $extension
     * @return array
     */
    public function configure(string $configurationName, ?string $extension = 'php'): array
    {

        if (!isset($this->configs[$configurationName])) {

            $filename = $this->rootDirectory . '/Config/' . $configurationName . '.' . $extension;

            if (file_exists($filename)) {

                switch ($extension) {
                    case 'json':
                        $this->configs[$configurationName] = json_decode(file_get_contents($filename), true);
                        break;
                    default;
                    case 'php':
                        $this->configs[$configurationName] = include $filename;
                        break;
                }
            } else {
                // set empty array since configuration cannot be determined
                $this->configs[$configurationName] = [];
            }
        }
        try{

            return $this->configs[$configurationName];
        }
        catch(\TypeError $e){
            // could not resolve into an array
            return [];
        }
    }
}
