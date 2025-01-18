<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntEntreprise extends Model
{
    use HasFactory;


    protected $table = 'ententreprise';

    protected $primaryKey = 'id_entreprise';
    protected $fillable = [
        'denomination_entreprise',
        'id_utilisateur',
        'created_at',
        'updated_at',
    ];

    
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur_pf::class, 'id_utilisateur', 'id_utilisateur');
    }
}
