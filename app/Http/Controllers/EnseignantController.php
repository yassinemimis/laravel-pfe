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
        return Enseignant::all(); // Récupère tous les enseignants
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

    public function destroy($id)
    {
        Enseignant::destroy($id); // Supprime un enseignant
        return response()->json(['message' => 'Enseignant supprimé']);
    }
    public function getCoEncadrants(Request $request) {
        $query = $request->input('query');

        // البحث عن أسماء المدرسين
        $results = DB::table('enseignant')
            ->join('utilisateur_pf', 'enseignant.id_utilisateur', '=', 'utilisateur_pf.id_utilisateur')
            ->select('enseignant.id_ens', 'utilisateur_pf.nom', 'utilisateur_pf.prenom', 'enseignant.grade_ens')
            ->where('utilisateur_pf.nom', 'LIKE', '%' . $query . '%')
            ->orWhere('utilisateur_pf.prenom', 'LIKE', '%' . $query . '%')
            ->get();

        return response()->json($results);
    }
}
