<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Foundation\Auth\User as Authenticatable;
class Utilisateur_pf extends Authenticatable
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

  
  
    public function etudiant()
    {
        return $this->hasOne(Etudiant::class, 'id_utilisateur', 'id_utilisateur');
    }
    
    
}
