<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\ChoixUn;
use Illuminate\Support\Facades\DB;
use App\Mail\Binomeemail;
class ChoixUnController extends Controller
{

    public function index()
    {
        $choix = ChoixUn::with(['etudiant', 'theme'])->get(); // جلب البيانات مع العلاقات
        return response()->json($choix, 200);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_etu' => 'required|exists:etudiant,id',
            'id_etu2' => 'nullable|exists:etudiant,id',
            'id_theme' => 'required|exists:theme_pf,id_theme',
        ]);
    
        $choix = ChoixUn::create($validated);
        $email = DB::table('utilisateur_pf')
    ->join('etudiant', 'utilisateur_pf.id_utilisateur', '=', 'etudiant.id')
    ->where('etudiant.id', 5)
    ->value('adresse_email');
     Mail::to($email)->send(new Binomeemail());
        return response()->json(['message' => 'Choix ajouté avec succès.', 'choix' => $choix], 201);
    }
    


    public function show($id)
    {
        $choix = ChoixUn::with(['etudiant', 'theme'])->find($id);

        if (!$choix) {
            return response()->json(['message' => 'Choix non trouvé.'], 404);
        }

        return response()->json($choix, 200);
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_etu' => 'required|exists:etudiant,id_etu',
            'id_theme' => 'required|exists:theme_pf,id_theme',
        ]);

        $choix = ChoixUn::find($id);

        if (!$choix) {
            return response()->json(['message' => 'Choix non trouvé.'], 404);
        }

        $choix->update($validated);
        return response()->json(['message' => 'Choix mis à jour avec succès.', 'choix' => $choix], 200);
    }


    public function destroy($id)
    {
        $choix = ChoixUn::find($id);

        if (!$choix) {
            return response()->json(['message' => 'Choix non trouvé.'], 404);
        }

        $choix->delete();
        return response()->json(['message' => 'Choix supprimé avec succès.'], 200);
    }
    public function getBinome(Request $request) {
       
        $request->validate([
            'id_etu2' => 'sometimes|exists:etudiant,id',
        ]);
    
       
        if (!$request->has('id_etu2')) {
            return response()->json(['error' => 'ID of the second student (id_etu2) is required'], 400);
        }
    
        
        $result = DB::table('choix_un')
            ->join('etudiant', 'etudiant.id', '=', 'choix_un.id_etu')
            ->join('utilisateur_pf', 'utilisateur_pf.id_utilisateur', '=', 'etudiant.id_utilisateur')
            ->join('theme_pf', 'theme_pf.id_theme', '=', 'choix_un.id_theme')
            ->where('choix_un.id_etu2', '=', $request->id_etu2)
            ->where('choix_un.valid', '=', 0)
            ->select('theme_pf.titre_theme', 'utilisateur_pf.prenom', 'utilisateur_pf.nom')
            ->get();
    
   
        if ($result->isEmpty()) {
            return response()->json(['message' => 'No records found for the given student ID'], 404);
        }
    
       
        return response()->json([
            'status' => 'success',
            'data' => $result
        ], 200);
    }
    
}
