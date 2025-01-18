<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoixUn extends Model
{
    use HasFactory;


    protected $table = 'choix_un';


    protected $fillable = [
        'id_etu',
        'id_theme',
        'id_etu2',
        'valid',
    ];


    public function etudiant1()
    {
        return $this->belongsTo(Etudiant::class, 'id_etu');
    }


    public function etudiant2()
    {
        return $this->belongsTo(Etudiant::class, 'id_etu2');
    }
    public function theme()
    {
        return $this->belongsTo(ThemePf::class, 'id_theme', 'id_theme');
    }
}
