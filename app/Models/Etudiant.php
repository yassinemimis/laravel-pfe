<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $table = 'etudiant'; 
    protected $primaryKey = 'id_etu';

    protected $fillable = [
        'id_utilisateur',
        'intitule_option',
        'moyenne_m1',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur_pf::class, 'id_utilisateur', 'id_utilisateur');
    }
}
