<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash; 
class Utilisateur_pf extends Model
{
    use HasFactory;

    protected $table = 'Utilisateur_pf'; 

    protected $primaryKey = 'id_utilisateur'; 

    public $timestamps = true; 

    protected $fillable = [
        
        'nom',
        'prenom',
        'type_utilisateur',
        'adresse_email',
        'password',
    ];

  
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->password = Hash::make($user->password); 
        });

        static::updating(function ($user) {
            if ($user->isDirty('password')) {
                $user->password = Hash::make($user->password); 
            }
        });
    }
    public function etudiant()
    {
        return $this->hasOne(Etudiant::class, 'id_utilisateur');
    }
    
}
