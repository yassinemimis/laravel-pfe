<?php
namespace App\Http\Controllers;

use App\Models\Utilisateur_pf;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class EtudiantController extends Controller
{

    public function index()
    {
        return Utilisateur_pf::all();
    }


    public function store(Request $request)
    {

        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'adresse_email' => 'required|email|unique:utilisateur_pf',
            'type_utilisateur' => 'required|string',
            'intitule_option' => 'required|integer',
            'moyenne_m1' => 'nullable|numeric',
        ]);

        $utilisateur_pf = Utilisateur_pf::create($request->only(['nom', 'prenom', 'adresse_email', 'type_utilisateur']));

        $etudiant = Etudiant::create([
            'id_utilisateur' => $utilisateur_pf->id_utilisateur, 
            'intitule_option' => $request->intitule_option,
            'moyenne_m1' => $request->moyenne_m1,
        ]);

        return response()->json(['utilisateur_pf' => $utilisateur_pf, 'etudiant' => $etudiant], 201);
    }

    public function show(Utilisateur_pf $utilisateur_pf)
    {
        return response()->json([
            'utilisateur_pf' => $utilisateur_pf,
            'etudiant' => $utilisateur_pf->etudiant, 
        ]);
    }

    public function update(Request $request, Utilisateur_pf $utilisateur_pf)
{
    $request->validate([
        'nom' => 'required|string',
        'prenom' => 'required|string',
        'adresse_email' => 'required|email|unique:utilisateur_pf,adresse_email,' . $utilisateur_pf->id_utilisateur,
        'type_utilisateur' => 'nullable|string',
        'intitule_option' => 'nullable|integer',
        'moyenne_m1' => 'nullable|numeric',
    ]);

    $utilisateur_pf->update($request->only(['nom', 'prenom', 'adresse_email', 'type_utilisateur']));

    if ($utilisateur_pf->etudiant) {
        $utilisateur_pf->etudiant->update($request->only(['intitule_option', 'moyenne_m1']));
    }

    return response()->json(['utilisateur_pf' => $utilisateur_pf, 'etudiant' => $utilisateur_pf->etudiant], 200);
}


public function destroy($id_utilisateur)
{

    $utilisateur_pf = Utilisateur_pf::find($id_utilisateur);

    if (!$utilisateur_pf) {
        return response()->json(['message' => 'Utilisateur not found'], 404);
    }

    if ($utilisateur_pf->etudiant) {
        $utilisateur_pf->etudiant->delete();
    }

    $utilisateur_pf->delete();

    return response()->json(['message' => 'Deleted successfully'], 204);
}

public function getEtudiant(Request $request) {
    $query = $request->input('query');

    // البحث عن أسماء المدرسين
    $results = DB::table('etudiant')
        ->join('utilisateur_pf', 'etudiant.id_utilisateur', '=', 'utilisateur_pf.id_utilisateur')
        ->select('etudiant.id', 'utilisateur_pf.nom', 'utilisateur_pf.prenom', 'etudiant.intitule_option')
        ->where('utilisateur_pf.nom', 'LIKE', '%' . $query . '%')
        ->orWhere('utilisateur_pf.prenom', 'LIKE', '%' . $query . '%')
        ->get();

    return response()->json($results);
}
}
