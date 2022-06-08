<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory;
    protected $guarded=[];
    use HasTranslations;

    public $translatable = ['name','description'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class);
    }

    public function getProfitPercentAttribute(){
        $salePrice=$this->sale_price;
        $purchasePrice=$this->purchase_price;
        $profit=$salePrice-$purchasePrice;
        $profitPercent= $profit*100/$purchasePrice;
        return $profitPercent;

    }

      public function getImagePathAttribute(){
       return   asset('uploads/product_images/'.$this->image);

    }
}
