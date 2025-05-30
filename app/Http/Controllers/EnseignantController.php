<?php


namespace App\Http\Controllers;
use App\Models\Utilisateur_pf;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnseignantController extends Controller
{
    public function index()
    {
  
        $result = DB::table('utilisateur_pf')
        ->join('enseignant', 'utilisateur_pf.id_utilisateur', '=', 'enseignant.id_utilisateur')
        ->where('type_utilisateur', 'enseignant')
        ->select('utilisateur_pf.*', 'enseignant.*') 
        ->get();

        return $result;
    }

    public function store(Request $request)
    {
       

        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'adresse_email' => 'required|email|unique:utilisateur_pf',
            'type_utilisateur' => 'required|string',
            'password' =>'required|string',
            'date_recrutement' => 'required|date',
            'grade_ens' => 'required|string|max:50',
            'est_responsable' => 'required|boolean',
        ]);

        $utilisateur_pf = Utilisateur_pf::create($request->only(['nom', 'prenom', 'adresse_email', 'type_utilisateur','password']));

        $etudiant = Enseignant::create([
            'id_utilisateur' => $utilisateur_pf->id_utilisateur, 
            'date_recrutement' => $request->date_recrutement,
            'grade_ens' => $request->grade_ens,
            'est_responsable' => $request->est_responsable,
        ]);
    }

    public function show($id)
    {
        return Enseignant::findOrFail($id); // Récupère un enseignant spécifique
    }

    public function update(Request $request, $id)
    {
        $enseignant = Enseignant::findOrFail($id);

        $data = $request->validate([
            'id_utilisateur' => 'sometimes|exists:utilisateur_pf,id_utilisateur',
            'date_recrutement' => 'sometimes|date',
            'grade_ens' => 'sometimes|string|max:50',
            'est_responsable' => 'sometimes|boolean',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse_email' => 'required|email',
        ]);
    
        if (isset($data['id_utilisateur'])) {
            $Utilisateur_pf = Utilisateur_pf::find($data['id_utilisateur']);
            if ($Utilisateur_pf) {
                $Utilisateur_pf->update($data);
            } else {
                return response()->json(['error' => 'Utilisateur not found'], 404);
            }
        }

        $enseignant->update($data);
    

        return response()->json($enseignant);
    }
    

    public function destroy($id_utilisateur)
{
  
    $utilisateur_pf = Utilisateur_pf::find($id_utilisateur);


    if (!$utilisateur_pf) {
        return response()->json(['message' => 'Utilisateur not found'], 404);
    }

 
    if ($utilisateur_pf->enseignant) {
        try {
    
            $utilisateur_pf->enseignant->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete from enseignant', 'error' => $e->getMessage()], 500);
        }
    }

    try {

        $utilisateur_pf->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to delete utilisateur_pf', 'error' => $e->getMessage()], 500);
    }
}
    public function getCoEncadrants(Request $request) {
        $query = $request->input('query');


        $results = DB::table('enseignant')
            ->join('utilisateur_pf', 'enseignant.id_utilisateur', '=', 'utilisateur_pf.id_utilisateur')
            ->select('enseignant.id_ens', 'utilisateur_pf.nom', 'utilisateur_pf.prenom', 'enseignant.grade_ens')
            ->where('utilisateur_pf.nom', 'LIKE', '%' . $query . '%')
            ->orWhere('utilisateur_pf.prenom', 'LIKE', '%' . $query . '%')
            ->get();

        return response()->json($results);
    }
    public function getEnseignant4(Request $request) {
        $searchQuery = $request->input('query');
    
        $results = DB::table('theme_pf')
            ->where(function ($query) use ($searchQuery) {
                $query->where('encadrant_president', $searchQuery)
                      ->orWhere('co_encadrant', $searchQuery);
            })
            ->where('status', 'En attente')
            ->get();
    
        return response()->json($results);
    }
}
