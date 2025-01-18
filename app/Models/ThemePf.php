<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemePf extends Model
{
    use HasFactory;

    protected $table = 'theme_pf';
    protected $primaryKey = 'id_theme';
    public $incrementing = true;


    protected $fillable = [
        'titre_theme',
        'type_pf',
        'description',
        'affectation1',
        'affectation2',
        'encadrant_president',
        'co_encadrant',
        'intitule_option',
        'technologies_utilisees', 
    'besoins_materiel',
    'depse',
    ];

    // Relations
    public function affectation1()
    {
        return $this->belongsTo(Etudiant::class, 'affectation1');
    }

    public function affectation2()
    {
        return $this->belongsTo(Etudiant::class, 'affectation2');
    }

    public function coEncadrant()
    {
        return $this->belongsTo(Enseignant::class, 'co_encadrant');
    }
    public function Encadrant()
    {
        return $this->belongsTo(Enseignant::class, 'encadrant_president');
    }
    public function option()
    {
        return $this->belongsTo(Option::class, 'intitule_option');
    }
}
