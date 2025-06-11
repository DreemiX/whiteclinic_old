<?php 
namespace NinjaForms\CfConversionTool;

use NinjaForms\CfConversionTool\Handlers\Configure;
class Master{

    /** @var Configure */
    protected $configure;

    public function configure(string $filename, ?string $extension='php'): array
    {
        if(!isset($this->configure)){
            $this->configure= new Configure(__DIR__);
        }

        return $this->configure->configure($filename,$extension);
    }
}