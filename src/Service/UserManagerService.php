<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Image;
use App\Repository\UserRepository;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserManagerService
{
    private UserRepository $userRepository;
    private ImageRepository $imageRepository;

    public function __construct(UserRepository $userRepository, ImageRepository $imageRepository)
    {
        $this->userRepository = $userRepository;
        $this->imageRepository = $imageRepository;
    }

    public function saveUser(User $user): void
    {
        $this->userRepository->saveUser($user);
    }

    public function uploadProfileImage(User $user, UploadedFile $file, string $uploadDir): bool
    {
        try {
            $newFilename = uniqid() . '.' . $file->guessExtension();
            $file->move($uploadDir, $newFilename);

            $image = new Image();
            $image->setPath($newFilename);
            $this->imageRepository->save($image);

            $user->setImage($image);
            $this->userRepository->saveUser($user);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
