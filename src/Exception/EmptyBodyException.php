<?php

namespace App\Exception;

use Throwable;

class EmptyBodyException extends \Exception
{
   public function __contruct(
       string $message = '',
       int $code = 0,
       Throwable $previous = null
   ) {
       parent::__contruct($message, $code, $previous);
   }
}
