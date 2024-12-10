<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageService
{
    private string $imagesDirectory;
    private SluggerInterface $slugger;

    public function __construct(string $imagesDirectory, SluggerInterface $slugger)
    {
        $this->imagesDirectory = $imagesDirectory;
        $this->slugger = $slugger;
    }

    public function uploadImage(UploadedFile $imageFile): string
    {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

        try {
            $imageFile->move($this->imagesDirectory, $newFilename);
        } catch (FileException $e) {
            throw new \RuntimeException('Failed to upload image: ' . $e->getMessage());
        }

        return $newFilename;
    }
}
