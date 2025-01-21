<?php


namespace App\Http\Controllers;
use App\Models\Utilisateur_pf;
use App\Models\EntEntreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntrepriseController extends Controller
{
    public function index()
    {
  
        $result = DB::table('utilisateur_pf')
        ->join('ententreprise', 'utilisateur_pf.id_utilisateur', '=', 'ententreprise.id_utilisateur')
        ->where('type_utilisateur', 'entreprise')
        ->select('utilisateur_pf.*', 'ententreprise.*') 
        ->get();
      
        return $result;
    }

    public function store(Request $request)
    {
    
        $validatedData = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'adresse_email' => 'required|email|unique:utilisateur_pf',
            'type_utilisateur' => 'required|string',
            'password' =>'required|string',
            'denomination_entreprise' => 'required|string',
        ]);
        
       
        $utilisateur_pf = Utilisateur_pf::create([
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'adresse_email' => $validatedData['adresse_email'],
            'type_utilisateur' => $validatedData['type_utilisateur'],
            'password' => $validatedData['password'],
        ]);
    
       
        $entreprisent = EntEntreprise::create([
            'id_utilisateur' => $utilisateur_pf->id_utilisateur, 
            'denomination_entreprise' => $validatedData['denomination_entreprise'],
        ]);
    
        return response()->json(['utilisateur_pf' => $utilisateur_pf, 'entreprisent' => $entreprisent], 201);
    }
    

    public function show($id)
    {
        return EntEntreprise::findOrFail($id); 
    }

    public function update(Request $request, $id)
    {
        $ententreprise = EntEntreprise::findOrFail($id);

        $data = $request->validate([
            'id_utilisateur' => 'sometimes|exists:utilisateur_pf,id_utilisateur',
            'denomination_entreprise' => 'required|string',
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

        $ententreprise->update($data);
    

        return response()->json($ententreprise);
    }
    

    public function destroy($id_utilisateur)
{
    $utilisateur_pf = Utilisateur_pf::find($id_utilisateur);

    if (!$utilisateur_pf) {
        return response()->json(['message' => 'Utilisateur not found'], 404);
    }

    $ententreprise = $utilisateur_pf->ententreprise;
    if ($ententreprise) {
        try {

            $ententreprise->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete from ententreprise', 'error' => $e->getMessage()], 500);
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
    public function getEntreprise4(Request $request) {
        $searchQuery = $request->input('query'); 
    
        $results = DB::table('theme_pf')
            ->where(function ($query) use ($searchQuery) {
                $query->where('id_entreprise', $searchQuery);
            })
            ->where('status', 'En attente')
            ->get();
    
        return response()->json($results);
    }
}
