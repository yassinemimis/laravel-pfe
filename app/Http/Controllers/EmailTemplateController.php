<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    // Afficher tous les templates
    public function index()
    {
        $templates = EmailTemplate::all();
        return response()->json($templates);
    }

    // Créer un nouveau template
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient' => 'required|string',
            'send_date' => 'nullable|date',
            'reminder_date' => 'nullable|date',
        ]);

        $template = EmailTemplate::create($request->all());
        return response()->json($template, 201);
    }

    // Afficher un template spécifique
    public function show($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return response()->json($template);
    }

    // Mettre à jour un template existant
    public function update(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient' => 'required|string',
            'send_date' => 'nullable|date',
            'reminder_date' => 'nullable|date',
        ]);

        $template->update($request->all());
        return response()->json($template);
    }

    // Supprimer un template
    public function destroy($id)
    {
        $template = EmailTemplate::findOrFail($id);
        $template->delete();
        return response()->json(['message' => 'Template supprimé avec succès']);
    }
}
