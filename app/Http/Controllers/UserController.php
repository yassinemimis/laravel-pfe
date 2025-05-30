<?php
namespace App\Http\Controllers;

use App\Models\Utilisateur_pf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\EntEntreprise;
class UserController extends Controller
{
    public function import(Request $request)
    {
    
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');

            if ($file->getSize() > 5000000) { 
                return response()->json([
                    'success' => false,
                    'message' => 'La taille du fichier est trop grande'
                ]);
            }

            if ($file->isValid()) {
                $filePath = $file->store('uploads');
                
                Log::info('Fichier reçu: ', ['file_name' => $file->getClientOriginalName()]);

                $data = array_map('str_getcsv', file($file));

                if (count($data) < 2) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Le fichier CSV est vide ou ne contient pas de données valides.'
                    ]);
                }

                $header = $data[0];
                unset($data[0]);    

                $errors = [];
                foreach ($data as $key => $row) {
                    $rowData = array_combine($header, $row);

                    $validator = Validator::make($rowData, [
                        'nom' => 'required|string|max:255',
                        'prenom' => 'required|string|max:255',
                        'type_utilisateur' => 'required|string|max:50',
                        'adresse_email' => 'required|email|unique:utilisateur_pf,adresse_email',
                        'date_recrutement' => 'required|date',
                    ]);

                    if ($validator->fails()) {
                        $errors[] = "la ligne ".($key + 1)." Contient des erreurs : " . implode(", ", $validator->errors()->all());
                        continue;
                    }

                    $randomPassword = Str::random(6);  

                    $hashedPassword = Hash::make($randomPassword);

               
                    $utilisateur_pf = Utilisateur_pf::create([
                        'nom' => $rowData['nom'],
                        'prenom' => $rowData['prenom'],
                        'type_utilisateur' => $rowData['type_utilisateur'],
                        'adresse_email' => $rowData['adresse_email'],
                        'password' => $hashedPassword,
                    ]);
                    if($rowData['type_utilisateur']=='enseignant'){
                    $enseignant = Enseignant::create([
                        'id_utilisateur' => $utilisateur_pf->id_utilisateur,
                        'date_recrutement' => $rowData['date_recrutement'],
                        'intitule_option' => $rowData['intitule_option'],
                        'moyenne_m1' => $rowData['moyenne_m1'],
                    ]);
                    }
                    else if($rowData['type_utilisateur']=='etudiant'){
                        $etudiant = Etudiant::create([
                            'id_utilisateur' => $utilisateur_pf->id_utilisateur,
                            'grade_ens' => $rowData['grade_ens'],
                            'est_responsable' => $rowData['est_responsable'], 
                        ]);
                    }
                    else{
                        $entreprise = EntEntreprise::create([
                            'id_utilisateur' => $utilisateur_pf->id_utilisateur,
                            'denomination_entreprise' => $rowData['denomination_entreprise'],                        
                        ]);
                    }
                    Mail::to($rowData['adresse_email'])->send(new SendPasswordEmail($randomPassword));
                }

             
                if (!empty($errors)) {
                    return response()->json([
                        'success' => false,
                        'message' => implode("\n", $errors)
                    ]);
                }


                return response()->json([
                    'success' => true,
                    'message' => $randomPassword
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Fichier invalide'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' =>'Fichier non envoyé'
        ]);
    }
}
