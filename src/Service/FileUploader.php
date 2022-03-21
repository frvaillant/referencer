<?php


namespace App\Service;


use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FileUploader
{

    /**
     * @var SessionInterface
     */
    private $session;


    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

    }

    const EXTENSIONS = [
        'jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG'
    ];

    const LIMIT_SIZE = 2000;

    const FILE_LIMIT_WEIGHT = 8000000;

    private function resize($uploadedFile): void
    {
        $manager = new ImageManager(array('driver' => 'imagick'));
        $image = $manager->make($uploadedFile);
        $height = $image->getHeight();
        $width = $image->getWidth();
        $orientation = $width > $height ? 'landscape' : 'portrait';
        if ($height > self::LIMIT_SIZE || $width > self::LIMIT_SIZE) {
            $ratio = $width / self::LIMIT_SIZE;
            if ('portrait' === $orientation) {
                $ratio = $height / self::LIMIT_SIZE;
            }
            $image->resize($width / $ratio, $height / $ratio);
            $image->save();
        }
    }

    private function removeLastFile($entity, $methodName, $destination): void
    {
        $destination = str_replace('/uploads', '', $destination);
        $methodName = str_replace('set', 'get', $methodName);

        if ($entity->{$methodName}()) {
            if (is_file($destination . $entity->{$methodName}())) {
                unlink($destination . $entity->{$methodName}());
            }
        }
    }

    public function upload($uploadedFile, $newFilename, $destination, $extension, $structure, $methodName = 'setLogo'): void
    {
        ini_set('post_max_size', '9M');
        ini_set('upload_max_filesize', '9M');

        if (in_array($extension, self::EXTENSIONS)) {
            $manager = new ImageManager(array('driver' => 'imagick'));
            $image = $manager->make($uploadedFile);
            if ($image->filesize() > self::FILE_LIMIT_WEIGHT) {
                $this->session->getFlashBag()->add('error', 'Votre image est trop grosse pour être chargée');
                return;
            }
            $this->removeLastFile($structure, $methodName, $destination);
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $this->resize($destination . '/' . $newFilename);
            $structure->{$methodName}('/uploads/' . $newFilename);
        } else {
            $this->session->getFlashBag()->add('error', 'Type de fichier non autorisé et ignoré');
        }
    }

}
