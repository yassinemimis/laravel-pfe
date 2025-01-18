<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory;

    protected $table = 'enseignant';
    protected $primaryKey = 'id_ens';
    public $incrementing = true;
  

    protected $fillable = [
        'id_utilisateur',
        'date_recrutement',
        'grade_ens',
        'est_responsable',
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(UtilisateurPf::class, 'id_utilisateur','id_utilisateur');
    }

    public function themes()
    {
        return $this->hasMany(ThemePf::class, 'co_encadrant',);
    }
    
}
