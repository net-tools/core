<?php

// namespace
namespace Nettools\Core\Helpers\RequestSecurityHelper;




/**
 * Base class to interface with a client
 */
interface ClientInterface
{
	public function get($k);
	public function set($k, $v);
	public function delete($k);
}