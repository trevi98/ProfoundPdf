<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function area(){
        return $this->belongsTo(Area::class);
    }
    public function developer(){
        return $this->belongsTo(Developer::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($pdf) {
            // $product->slug = $product->title.'-'.$product->id;
            // dd($pdf);

            // $product->update([
            //     'country_id' => Auth::user()->company->country->id,
            //     'slug' =>$slug,
            //     'company_id' => Auth::user()->company->id,
            //     'verified' => Auth::user()->company->verified,
            //     'status' => Auth::user()->company->status,
            //     // 'company_id' => $product->user->company->id,
            // ]);
        });
    }
}
