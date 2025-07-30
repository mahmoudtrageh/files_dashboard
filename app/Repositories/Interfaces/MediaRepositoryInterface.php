<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface MediaRepositoryInterface
{
    public function getPaginatedMedia(int $perPage = 15);

    public function uploadMedia(Request $request, string $fileInputName, string $keyName);

    public function deleteMedia(int $id);

    public function findById(int $id);
}