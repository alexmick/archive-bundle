#Ornj ArchiveBundle

This bundle provides a service for creating a zip archive of files on the filesystem. This is useful for delivering multiple 
files as a single download. At the moment the only supported compression type is Zip through PHP's 
[ZipArchive](http://php.net/manual/en/class.ziparchive.php) although I indend to expand it to support other formats.

## Installation

Install this bundle in your Symfony2 project by adding it to your `composer.json`.

```json
{
    "require": {
        "ornj/archive-bundle": "dev-master"
    }
}
```

After updating composer, register the bundle in `app/AppKernel.php`.

```php
$bundles = array(
   // ...
   new Ornj\Bundle\OrnjArchiveBundle\OrnjArchiveBundle(),
);
```

## Usage

The service currently has a single method `create` which takes an array containing the following parameters:

*  string filename: the name of the resulting archive
*  array files: paths to the files that should be contained in the archive
*  string destination: where to write the archive (if none is supplied, web/uploads will be used)
*  bool overwrite: if the service should write over any archive with the same file name

```php
$archive = $this->get('ornj_archive.zip');
$created = $archive->create(array(
    'files'         => $files,
    'filename'      => $entity->getId() . '.zip',
    'destination'   => $basePath. '/archives/',
    'overwrite'     => false
));

if ($created === true) {
    return $this->redirect($webPath. '/archives/' . $entity->getId() . '.zip');
}
```