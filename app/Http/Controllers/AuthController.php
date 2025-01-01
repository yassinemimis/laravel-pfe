<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Utilisateur_pf;
use Illuminate\Support\Facades\DB;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        
        $request->validate([
            'adresse_email' => 'required|email',
            'password' => 'required|string',
        ]);

       
        $utilisateur_pf = Utilisateur_pf::where('adresse_email', $request->adresse_email)->first();

   
        if ( Hash::check($request->password, $utilisateur_pf->password)) {
           
            Auth::login($utilisateur_pf);
            $result = DB::table('utilisateur_pf')
            ->join('etudiant', 'utilisateur_pf.id_utilisateur', '=', 'etudiant.id_utilisateur')
            ->where('utilisateur_pf.id_utilisateur', $utilisateur_pf->id_utilisateur)
            ->select('etudiant.id')
            ->get();
          
            return response()->json([
                'type_utilisateur' => $utilisateur_pf->type_utilisateur,
                'message' => $utilisateur_pf->id_utilisateur,
                'message1' => $request->password,
                'nom' => $utilisateur_pf->nom,
                'prenom' => $utilisateur_pf->prenom,
                'result' => $result
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "error 404"
            ], 401);
        }
    }
    
   
    public function logout(Request $request)
    {
        Auth::logout();
        
        return response()->json([
            'success' => true,
            'message' => 'logout valid'
        ]);
    }
}
