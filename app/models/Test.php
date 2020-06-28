<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model{
    protected $table = "test_model";
    protected $fillable = ['name','website'];
}
?>