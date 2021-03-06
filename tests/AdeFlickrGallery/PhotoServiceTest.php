<?php
use AdeFlickrGallery\Service\FotoService;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;
use AdeFlickrGallery\Service\FlickrPhoto;

class PhotoServiceTest extends AbstractControllerTestCase
{
    private $photoService;
    private $flickr;
    private $config;
    private $cacheDir;
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->setApplicationConfig(
            include '/paginas/pueblapictures.com/config/application.config.php'
        );
        
        $config = include '/paginas/pueblapictures.com/config/autoload/flickr.local.php';
        $this->config = $config;
        
        $key     = $config['flickr']['key'];
        
        $flickr = new FlickrPhoto($key);
        $flickr->getHttpClient()->setOptions(array('sslverifypeer' => false));
        $this->flickr = $flickr;
        
        $this->cacheDir = '/paginas/pueblapictures.com/data/cache';
        
        $this->photoService = new FotoService();
    }
    
    public function testGetPhotoDetails()
    {
        $id = '6903385984';
        $this->photoService->getPhotoDetails($this->flickr, $id, $this->cacheDir);
        echo 'pato';
    }
    
    public function testGetPhotoTrio()
    {
        $id = '16274443098+15639103582+15683111516';
        $idarray = explode('+', $id);
        $photos = array();
        foreach ($idarray as $id) {
            $photos[] = array(
                'id'      => $id,
                'details' => $this->photoService->getPhotoDetails($this->flickr, $id, $this->cacheDir),
                'info'    => $this->photoService->getPhotoInfo($this->flickr, $id, $this->cacheDir),
                'exif'    => $this->photoService->getPhotoExif($this->flickr, $id, $this->cacheDir),
            );
        }
        //$this->assertArrayHasKey('details', $photos);
    }
}