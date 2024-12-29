<?php

namespace App\Http\Controllers;

use App\Models\ThemePf;
use Illuminate\Http\Request;

class ThemePfController extends Controller
{
    public function index()
    {
        return ThemePf::all(); // Récupère tous les thèmes
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre_theme' => 'nullable|string|max:255',
            'type_pf' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'affectation1' => 'nullable|exists:etudiant,id',
            'affectation2' => 'nullable|exists:etudiant,id',
            'encadrant_president' => 'nullable|exists:enseignant,id_ens',
            'co_encadrant' => 'nullable|exists:enseignant,id_ens',
            'intitule_option' => 'nullable|exists:options,id',
            'technologies_utilisees' => 'nullable|string',
            'besoins_materiel' => 'nullable|string',
            'depse'=>'nullable|string',

        ]);
  
        return ThemePf::create($data); // Crée un thème
    }

    public function show($id)
    {
        return ThemePf::findOrFail($id); // Récupère un thème spécifique
    }

    public function update(Request $request, $id)
    {
        $theme = ThemePf::findOrFail($id);
        $data = $request->validate([
            'titre_theme' => 'sometimes|string|max:255',
            'type_pf' => 'sometimes|string|max:50',
            'description' => 'sometimes|string',
            'affectation1' => 'sometimes|exists:utilisateur_pf,id_utilisateur',
            'affectation2' => 'sometimes|exists:utilisateur_pf,id_utilisateur',
            'encadrant_president' => 'nullable|exists:enseignant,id_ens',
            'co_encadrant' => 'sometimes|exists:enseignant,id_ens',
            'intitule_option' => 'sometimes|exists:option,id_option',
        ]);

        $theme->update($data);
        return $theme;
    }

    public function destroy($id)
    {
        ThemePf::destroy($id); // Supprime un thème
        return response()->json(['message' => 'Thème supprimé']);
    }
    public function getWithoutEncadrantPresident()
{
    $themesSansEncadrant = ThemePf::whereNull('encadrant_president')->get();
    return response()->json($themesSansEncadrant);
}
public function assignMultipleProjects(Request $request)
{
    $data = $request->validate([
        'assignments' => 'required|array', 
        'assignments.*.id_theme' => 'required|exists:theme_pf,id_theme', 
        'assignments.*.encadrant_president' => 'required|exists:enseignant,id_ens', 
    ]);

    $updatedProjects = [];

    foreach ($data['assignments'] as $assignment) {
        $theme = ThemePf::findOrFail($assignment['id_theme']);

       
        $theme->update([
            'encadrant_president' => $assignment['encadrant_president'],
        ]);

        $updatedProjects[] = $theme; 
    }

    return response()->json([
        'message' => 'Projets assignés avec succès.',
        'updated_projects' => $updatedProjects,
    ]);
}

}
