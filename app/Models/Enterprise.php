<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enterprise extends Model
{
  use HasFactory;
  use HasUlids;

  protected $fillable = ["name", "NIT", "email", "phone_number", "currency"];

  public function products(): HasMany
  {
    return $this->hasMany(Product::class);
  }

  public function users(): HasMany
  {
    return $this->hasMany(User::class);
  }
}
