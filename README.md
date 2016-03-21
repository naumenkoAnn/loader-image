# loader-image

Upload images from a remote host

#Code Examples

$loaders = new LoaderImage\Image();

//Directory for uploads

$loaders->setUploadsDir('uploads');

$loaders->loadByUrl('http://example.com.ua/');

