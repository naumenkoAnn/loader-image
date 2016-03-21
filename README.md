# loader-image

Upload images from a remote host

#Code Examples

$loaders = new LoaderImage\Image();

$loaders->setUploadsDir('uploads'); //Directory for uploads

$loaders->loadByUrl('http://example.com.ua/');
