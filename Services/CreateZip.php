<?php

namespace Ornj\Bundle\ArchiveBundle\Services;

use \ZipArchive;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

use Ornj\Bundle\ArchiveBundle\Exception\InvalidFilesException;

class CreateZip
{
    protected $options;
    
    public function __construct (array $options) {
        $this->options = $options;
    }
    
    /**
     * Create a zip file
     *
     * @param array options
     *  - string filename
     *  - array files
     *  - string destination
     *  - boolean overwrite
     * @return boolean
     * @throws AccessDeniedException
     * @throws InvalidFilesException
     */
    public function create (array $options) {
        $options = array_merge($this->options, $options);
        
        if (file_exists($options['destination'] . $options['filename']) && $options['overwrite'] !== false ) {
            // Zip file exists and not allowed to overwrite
            throw new AccessDeniedException($options['destination'] . $options['filename']);
        }
        
        if (!file_exists($options['destination'])) {
            @mkdir($options['destination'], 0777, true);
        }
        
        $files = array();
        $invalid = array();
            
        if (is_array($options['files'])) {
            foreach ($options['files'] as $file) {
                if (file_exists($file)) {
                    $files[] = $file;
                }
                else {
                    $invalid[] = $file;
                }
            }
        }
        
        if (count($invalid)) {
            // One of more files were invalid
            throw new InvalidFilesException($invalid);
        }
        
        $archive = new ZipArchive();
        
        if ($archive->open($options['destination'] . $options['filename'], $options['overwrite'] === true ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
            // Unable to create or overwrite file
            throw new AccessDeniedException($options['destination'] . $options['filename']);
        }
        
        foreach ($files as $file) {
            $archive->addFile($file, basename($file));
        }
        
        $archive->close();
        return file_exists($options['destination'] . $options['filename']);
    }
}