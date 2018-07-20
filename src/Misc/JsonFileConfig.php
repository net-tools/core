<?php


namespace Nettools\Core\Misc;



/**
 * Helper for config data as json file
 */
class JsonFileConfig extends ObjectConfig{
	
	/** 
	 * Constructor
	 *
	 * @param string $fname
	 */
	public function __construct($fname)
	{
		if ( file_exists($fname) )
        {
            $json = json_decode(file_get_contents($fname));
            if ( is_null($json) )
                throw new \Exception("JSON config file error");
            else
                parent::__construct($json);
        }
        else 
            throw new \Exception("JSON config file does not exist");
	}
}


?>