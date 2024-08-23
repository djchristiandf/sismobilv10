<?php

namespace App\Http\Controllers;

use App\Models\ValeTransporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValeTransporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //listagem de toda tabela VAleTransporte
        $valeTransportes = ValeTransporte::all();
        return view('valeTransportes.index', compact('valeTransportes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //chama a view create
        return view('valeTransportes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //salvar valetransporte
        $validator = Validator::make($request->all(), [
            'EmpregadoId' => 'required|integer',
            'LinhaId' => 'required|integer',
            'Valor' => 'required|numeric',
            'Quantidade' => 'required|integer',
            // Adicione as demais validações para as colunas
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        ValeTransporte::create($request->all());

        return response()->json(['success' => 'ValeTransporte criado com sucesso']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $valeTransporte = ValeTransporte::findOrFail($id);
        return view('valeTransportes.show', compact('valeTransporte'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $valeTransporte = ValeTransporte::findOrFail($id);
        return view('valeTransportes.edit', compact('valeTransporte'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $valeTransporte = ValeTransporte::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'EmpregadoId' => 'required|integer',
            'LinhaId' => 'required|integer',
            'Valor' => 'required|numeric',
            'Quantidade' => 'required|integer',
            // Adicione as demais validações para as colunas
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $valeTransporte->update($request->all());

        return response()->json(['success' => 'ValeTransporte atualizado com sucesso']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $valeTransporte = ValeTransporte::findOrFail($id);
        $valeTransporte->delete();

        return response()->json(['success' => 'ValeTransporte excluído com sucesso']);
    }
}
