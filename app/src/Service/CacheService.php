<?php

namespace App\Service;

use App\Entity\Cached;
use App\Entity\Dimension;
use App\Entity\Image;
use App\Repository\CachedImageRepository;
use App\Request\CacheRequest;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class CacheService
{
    const CACHE_DIR = '/var/www/app/public/cached';
    const TARGET_FORMAT = 'jpeg';

    /**
     * @param CachedImageRepository $cachedImageRepository
     */
    public function __construct(
        private CachedImageRepository $cachedImageRepository,
    )
    {
    }

    /**
     * @param CacheRequest $request
     * @return Cached|null
     */
    public function findCache(CacheRequest $request): ?Cached
    {
        return $this->cachedImageRepository->findBySize($request->getName(), $request->getSize());
    }

    /**
     * Генерация пути до файла с кешем
     * @param Image $image
     * @param Dimension $size
     * @return string
     */
    public function createCachePath(Image $image, Dimension $size): string
    {
        return sprintf('%s/%s_%s.%s',
            self::CACHE_DIR,
            $image->getName(), $size->getName(), self::TARGET_FORMAT);
    }

    /**
     * Создаем кеш и сохраняем его объект в бд
     * @param Image $image
     * @param Dimension $size
     * @return null|Cached
     */
    public function store(Image $image, Dimension $size): ?Cached
    {
        if ($cache = $this->convert($image, $size)) {
            $this->cachedImageRepository->store($cache);
            return $cache;
        }
        return null;
    }

    /**
     * Очищаем каталог с кешем
     * @param string $name
     * @return void
     */
    public function clearCacheFiles(string $name): void
    {
        $finder = new Finder();
        $finder->files()->name(["$name*.jpeg"])->in(self::CACHE_DIR);
        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            unlink($file->getPathname());
        }
    }

    /**
     * Создаем кеш от исходного изображения и требуемого разрешение
     * @param Image $image
     * @param Dimension $target
     * @param string|null $path
     * @return Cached|null
     */
    function convert(Image $image, Dimension $target, ?string $path = null): ?Cached
    {
        $path = $path ?? $this->createCachePath($image, $target);
        try{
            if ($image->getRatio() === $target->getRatio() ||
                ($image->getWidth() > $target->getWidth() && $image->getHeight() >= $target->getHeight())
               ) {
                $imagick = new \Imagick($image->getSrc());
                $width  = round($image->getRatio() * $target->getWidth());
                $height = round($width / $image->getRatio());
                $imagick->resizeImage($width, $height, \Imagick::FILTER_CATROM, 0.9);
                $imagick->setImageFormat('jpeg');
                $imagick->setImageCompressionQuality(90);
                // при разных пропорциях не теряем в качестве
                $x = round(($width - $target->getWidth())/2);
                $y = round(($height - $target->getHeight())/2);
                $imagick->cropImage($target->getWidth(), $target->getHeight(), $x, $y);
                $imagick->writeImage($path);
            } else {
                return null;
            }
            $cachedImage = new Cached();
            $cachedImage->setImage($image);
            $cachedImage->setSrc($path);
            $cachedImage->setDimension($target);
            $cachedImage->setWidth($target->getWidth());
            $cachedImage->setHeight($target->getHeight());
            return $cachedImage;
        } catch (\ImagickException) {
            return null;
        }
    }
}