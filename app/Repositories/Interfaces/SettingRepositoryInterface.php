<?php

namespace App\Repositories\Interfaces;

interface SettingRepositoryInterface
{
    public function get(string $key, $default = null);

    public function set(string $key, $value);
 
    public function all();
 
    public function updateMultiple(array $settings);

    public function handleMediaUploads($request, array $fields);

    public function clearMedia(string $key, string $collection);
}