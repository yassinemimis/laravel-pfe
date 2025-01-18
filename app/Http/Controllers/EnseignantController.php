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
        $data = $request->validate([
            'id_utilisateur' => 'required|exists:utilisateur_pf,id_utilisateur',
            'date_recrutement' => 'required|date',
            'grade_ens' => 'required|string|max:50',
            'est_responsable' => 'required|boolean',
        ]);

        return Enseignant::create($data); // Crée un enseignant
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
        ]);

        $enseignant->update($data);
        return $enseignant;
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
}
