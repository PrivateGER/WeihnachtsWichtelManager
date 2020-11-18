<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder;

/**
 * Class Benutzer
 * @package App\Models
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Benutzer extends Model
{
    use HasFactory;

    public function targets() {
        return $this->hasMany("App\Models\Relationships", "from", "id");
    }
}
