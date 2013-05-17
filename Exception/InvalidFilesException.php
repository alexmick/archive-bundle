<?php

namespace Ornj\Bundle\ArchiveBundle\Exception;

class InvalidFilesException extends \Exception
{
    public function __construct($files)
    {
        parent::__construct('Could not find files: ' . implode(', ', $files));
    }
}