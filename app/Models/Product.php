<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Product extends Model
{
    protected $fillable = ['category_id', 'code', 'name', 'unit', 'price', 'stock'];

    public function getFormattedPriceAttribute()
{
    return 'Rp ' . number_format($this->attributes['price'], 0, ',', '.');
}
 
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
 
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
    public function index()
{
    $products = Product::all();

    return view('dashboard', compact('products'));
}
}

