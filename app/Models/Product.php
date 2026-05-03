<?php

namespace App\Models;
use Laravel\Scout\Searchable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
        use Searchable; // مهم جداً

    protected $fillable = ['name', 'description', 'size','quantity','image','price','unit','supplier_id','category_id','brand_id'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function getImageUrlAttribute()
    {
        // تحقق إذا كان حقل الصورة يحتوي على قيمة وأنه ليس رابطاً كاملاً بالفعل
        if ($this->image && !str_starts_with($this->image, 'http' )) {
            // يفترض أن الصور مخزنة في 'storage/app/public/products'
            // الدالة Storage::url() ستنشئ الرابط الصحيح
            return Storage::url('products/' . $this->image);
        }

        // إذا لم تكن هناك صورة، أو كانت رابطاً خارجياً، أرجعها كما هي
        // أو أرجع صورة افتراضية
        return $this->image ?? 'https://via.placeholder.com/150';
    }
    
  public function toSearchableArray(): array
    {
        // تحميل العلاقات لضمان وجودها
        $this->load('category', 'brand');

        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category_name' => $this->category->name ?? '',
            'brand_name' => $this->brand->name ?? '',
        ];
    }

}
