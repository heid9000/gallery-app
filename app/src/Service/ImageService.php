<?php

namespace App\Service;

use App\Entity\Dimension;
use App\Entity\Image;
use App\Repository\CachedImageRepository;
use App\Repository\DimensionRepository;
use App\Repository\ImageRepository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ImageService
{
    const IMG_DIR   = '/var/www/app/public/images';
    protected array $sizes;

    /**
     * @param ImageRepository $imageRepository
     * @param DimensionRepository $dimensionRepository
     * @param CachedImageRepository $cachedImageRepository
     * @param CacheService $cacheService
     */
    public function __construct(
        protected ImageRepository $imageRepository,
        protected DimensionRepository $dimensionRepository,
        protected CachedImageRepository $cachedImageRepository,
        protected CacheService $cacheService
    )
    {
    }

    /**
     * Очистка папки с кешем и соотв. записей в бд
     * @param Image $image
     * @return void
     */
    public function resetCache(Image $image): void
    {
        $this->imageRepository->clearCache($image);
        $this->cacheService->clearCacheFiles($image->getName());
        foreach ($this->getSizes() as $size) {
            $this->cacheService->store($image, $size);
        }
    }

    /**
     * Создать объект изображения из файла
     * @param string $src
     * @return Image|null
     */
    public function create(string $src): ?Image
    {
        try {
            $im = new \Imagick($src);
            $image = new Image();
            $image->setWidth($im->getImageWidth());
            $image->setHeight($im->getImageHeight());
            $image->setType(explode('/', $im->getImageMimeType())[1]);
            $image->setImageHash(hash_file('md5', $src));
            $image->setSrc($src);
            $fileName = basename($src);
            $image->setName(str_contains($fileName, '.') ?
                explode('.', $fileName)[0] : $fileName);
            return $image;
        } catch (\ImagickException) {
            return null;
        }
    }

    /**
     * Согласуем состояние файлов изображений с бд, при изменении хещ-суммы изображения будет перезаписан соотв. кеш
     * @return void
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    public function sync(): void
    {
        $finder = new Finder();
        $finder->files()->name(['*.jpg', '*.jpeg', '*.png']);
        /** @var SplFileInfo $file */
        foreach($finder->in(self::IMG_DIR) as $file)
        {
            $src = $file->getPathName();
            $imgFs = $this->create($src);
            if (! $imgFs) continue;
            $imgDb = $this->imageRepository->findBySrc($src);
            if (isset($imgDb)) {
                if ($imgFs->getImageHash() !== $imgDb->getImageHash()) {
                    $imgFs = $this->imageRepository->replace($imgDb, $imgFs);
                    unset($imgDb);
                    $this->resetCache($imgFs);
                }
            } else {
                $this->imageRepository->store($imgFs);
                $this->resetCache($imgFs);
            }
        }
    }

    /**
     * Получаем требуемые размеры изображений для конвертации
     * @return array|Dimension[]
     */
    public function getSizes(): array
    {
        if (! isset($sizes)) {
            $this->sizes = $this->dimensionRepository->findAll();
        }
        return $this->sizes;
    }
}