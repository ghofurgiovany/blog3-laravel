<?php

use App\Models\Setting;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use greeflas\tools\ImageDownloader;
use greeflas\tools\validators\ImageValidator;

function setting($key, $default = '')
{
  if ($setting = Setting::find($key)) {
    return $setting->value;
  }

  return $default;
}

function getThumbnail($url, $absolute = false)
{

  $downloader   = new ImageDownloader([
    'class' =>  ImageValidator::class
  ]);

  $filename     = md5(\Str::random(16) . '-' . time()) . '.jpg';
  $downloader->download($url, storage_path('app/public/uploads'), $filename);

  return env('APP_URL') . '/uploads/' . $filename;

  if (App::environment(['production'])) {

    $downloader   = new ImageDownloader([
      'class' =>  ImageValidator::class
    ]);

    $filename     = md5(\Str::random(16) . '-' . time()) . '.jpg';
    $downloader->download($url, storage_path('app/public/uploads'), $filename);

    return env('APP_URL') . '/uploads' . $filename;

    // try {
    //   $image  = Cloudinary::upload($url);
    //   return $image->getSecurePath();
    // } catch (\Throwable $th) {
    //   return 'https://res.cloudinary.com/dyflpaklp/image/upload/v1640329427/tvrk1ro0hagdwbmfl9fz.png';
    // }

    // return;
  }

  if (!$absolute) {
    $articlePage    =   Http::get($url)->body();
    \preg_match_all('/og:image" content="(.*)"/', $articlePage, $thumbnails);

    $thumbnail      =   isset($thumbnails[1][0]) ? $thumbnails[1][0] : false;
  } else {
    $thumbnail = $url;
  }

  if (!$thumbnail) {
    return 'https://res.cloudinary.com/dyflpaklp/image/upload/v1640329427/tvrk1ro0hagdwbmfl9fz.png';
  }

  return getThumbnailDev($url);
}

function getThumbnailDev($thumbnail)
{
  $response       =   Http::get($thumbnail);
  if ($response->getStatusCode() != 200) {
    throw new Exception("Status code not equal 200", $response->getStatusCode());
  }

  $contentType    = $response->getHeaders()['Content-Type'];
  if (!isset($contentType[0])) {
    throw new Exception("Cannot retrieve content type", 1);
  }

  $contentType    =   explode('image/', $contentType[0]);
  $contentType    =   $contentType[1];
  $imageData      =   $response->body();

  if (!$imageData || !$contentType) {
    throw new Exception("Image data / content type invalid.", 1);
  }

  $filename   =   'uploads/' . md5($thumbnail) . '.' . $contentType;
  \Storage::disk('public')->put($filename, $imageData);

  return \url($filename);
}
