<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Mail\Binomeemail;
use App\Models\ThemePf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        if($request->depse =='Etudiant'){
            Mail::to($request->affectation2email)->send(new Binomeemail());
        }
        return ThemePf::create($data); 
    }

    public function show($id)
    {
        return ThemePf::findOrFail($id); // Récupère un thème spécifique
    }

    public function update(Request $request, $id)
    {
        $theme = ThemePf::findOrFail($id);
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

        $theme->update($data);
        return $theme;
    }
    public function update1(Request $request, $id)
    {
   
        $theme = ThemePf::find($id);

       
        if (!$theme) {
            return response()->json(['message' => 'المشروع غير موجود!'], 404);
        }

        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);
        if($request->id2 == 1){
        $emails = DB::table('theme_pf')
        ->join('etudiant', 'theme_pf.affectation1', '=', 'etudiant.id')
        ->join('utilisateur_pf', 'utilisateur_pf.id_utilisateur', '=', 'etudiant.id_utilisateur')
        ->where('theme_pf.id_theme', $id)
        ->pluck('utilisateur_pf.adresse_email'); 
        if ($emails->isNotEmpty()) {
            Mail::to($emails->toArray())->send(new Binomeemail());
        }}
        else if($request->id2 == 2){
            $emails = DB::table('theme_pf')
            ->join('enseignant', 'theme_pf.encadrant_president', '=', 'enseignant.id_ens')
            ->join('utilisateur_pf', 'utilisateur_pf.id_utilisateur', '=', 'enseignant.id_utilisateur')
            ->where('theme_pf.id_theme', $id)
            ->pluck('utilisateur_pf.adresse_email'); 
            if ($emails->isNotEmpty()) {
                Mail::to($emails->toArray())->send(new Binomeemail());
            }}
          else{
            $emails = DB::table('theme_pf')
            ->join('ententreprise', 'theme_pf.id_entreprise', '=', 'ententreprise.id_entreprise')
            ->join('utilisateur_pf', 'utilisateur_pf.id_utilisateur', '=', 'ententreprise.id_utilisateur')
            ->where('theme_pf.id_theme', $id)
            ->pluck('utilisateur_pf.adresse_email'); 
            if ($emails->isNotEmpty()) {
                Mail::to($emails->toArray())->send(new Binomeemail());
            }
          }  
        $theme->status = $request->status;
        $theme->save();
    
        return response()->json(['message' => 'تم تحديث حالة المشروع بنجاح!'], 200);
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
        $idEntreprise = DB::table('theme_pf')
            ->where('id_theme', $assignment['id_theme'])
            ->value('id_entreprise');
        if ($idEntreprise != null) {
            $result = DB::table('ententreprise')
                ->join('utilisateur_pf', 'utilisateur_pf.id_utilisateur', '=', 'ententreprise.id_utilisateur')
                ->where('ententreprise.id_entreprise',$idEntreprise)
                ->pluck('utilisateur_pf.adresse_email');
        
         
            if ($result->isNotEmpty()) {
                Mail::to($result->toArray())->send(new Binomeemail());
            }
        }
        else{
            $idaffectation1 = DB::table('theme_pf')
            ->where('id_theme', $assignment['id_theme'])
            ->value('affectation1');

            $result = DB::table('etudiant')
            ->join('utilisateur_pf', 'utilisateur_pf.id_utilisateur', '=', 'etudiant.id_utilisateur')
            ->where('etudiant.id',$idaffectation1)
            ->pluck('utilisateur_pf.adresse_email');
    
     
        if ($result->isNotEmpty()) {
            Mail::to($result->toArray())->send(new Binomeemail());
        }
        }
        
        $updatedProjects[] = $theme; 
    }

    return response()->json([
        'message' => 'Projets assignés avec succès.',
        'updated_projects' => $updatedProjects,
    ]);
}
public function getPendingProjects()
{

    $pendingProjects = ThemePf::where('status', 'En attente')->where('depse', 'Etudiant')->get();

    return response()->json($pendingProjects, 200);
}

public function getPendingProjects1()
{

    $pendingProjects = ThemePf::where('status', 'En attente')->where('depse', 'Enseignant')->get();

    return response()->json($pendingProjects, 200);
}
public function getPendingProjects2()
{

    $pendingProjects = ThemePf::where('status', 'En attente')->where('depse', 'Entreprise')->get();

    return response()->json($pendingProjects, 200);
}
public function getChoixEtudiant(Request $request)
{

    $request->validate([
        'intitule_option' => 'sometimes|exists:options,id',
    ]);

    $results = DB::table('theme_pf')
        ->whereNull('affectation1')
        ->where('intitule_option', $request->intitule_option) 
        ->get();

    return response()->json($results, 200);
}

}
