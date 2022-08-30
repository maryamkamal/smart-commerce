<?php
namespace Modules\User\Entities;

use Modules\Support\Eloquent\Model;

class SocialAuth extends Model
{
    protected $table = 'social_auth';
    protected $guarded = [];
   protected $dates = [
       'created_at',
       'updated_at'
   ];

   public function user()
   {
       return $this->belongsTo(User::class);
   }

}