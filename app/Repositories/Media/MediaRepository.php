<?php
namespace App\Repositories\Media;

use App\Repositories\BaseRepository;
use App\Repositories\Media\MediaRepositoryInterface;

class MediaRepository extends BaseRepository implements MediaRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Medias::class;
    }
}
