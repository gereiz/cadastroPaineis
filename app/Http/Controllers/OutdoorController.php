<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outdoor;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;

class OutdoorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $paineis = Outdoor::paginate(5);

        return view('outdoors.OutdoorGrid',[
            'paineis' => $paineis
        ]);

    }

    public function addForm()
    {
        return view('outdoors.OutdoorForm');
    }

    public function editForm($id)
    {
        $painel = Outdoor::find($id);
        return view('outdoors.OutdoorForm',[
            'painel' => $painel
        ]);
    }

    public function viewForm($id)
    { 
        $painel = Outdoor::find($id);
        //dd($painel->image_url);
        //dd(json_encode($painel->image_url));
        return view('outdoors.OutdoorViewForm',[
            'painel' => $painel,
        ]);
    }

    public function store(Request $request)
    {
        $this->validaForm($request);
        
        try {

            DB::beginTransaction();

            $painel = new Outdoor();
            if(Outdoor::find($request->id))
                $painel = Outdoor::find($request->id);
            $painel->identificacao = $request->identificacao; 
            $painel->localizacao = $request->localizacao;
            $painel->posicao = $request->posicao;
            $painel->dimensao = $request->dimensao;
            $painel->dimensao_lona = $request->dimensao_lona;
            $painel->ponto_referencia = $request->ponto_referencia;
            $painel->latitude = $request->latitude;
            $painel->longitude = $request->longitude;

            $painel->save(); 

            $file = base64_decode($request->image);
            $folder = $painel->id."/";
            $safeName = $painel->id.'.'.'png';
            $destinationPath = Storage::disk('outdoorImages')->path('');

            if (!is_dir($destinationPath. $folder)) {
                // dir doesn't exist, make it
                mkdir($destinationPath. $folder);
            }

            file_put_contents($destinationPath.$folder.$safeName, $file);

            $painel->image_url = 'outdoorImages/'.$folder.$safeName;

            $painel->save();

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);

        }

        DB::commit();
        if($request->id != 0)
            return response()->json(['success' => true, 'message' => 'Registro Editado com Sucesso!']);

        return response()->json(['success' => true, 'message' => 'Registro Cadastrado com Sucesso!']);
    }

    public function delete($id)
    {
        try 
        {
            DB::beginTransaction();
            Outdoor::find($id)->delete();
        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);

        }
        DB::commit();
        return response()->json(['success' => true, 'message' => 'Registro Deletado com Sucesso!']);

    }

    public function validaForm(Request $request)
    {

        $customMessages = [
            'identificacao.required' => 'A Identificação deve ser informada',
            'localizacao.required' => 'A Localização deve ser informada',
            'posicao.required' => 'A Posição deve ser informada',
            'dimensao.required' => 'A Dimensão deve ser informada',
            'dimensao_lona.required' => 'A Dimensão da Lona deve ser informada',
            'ponto_referencia.required' => 'O Ponto de referência deve ser informado',
            'latitude.required' => 'A Latitude deve ser informada',
            'longitude.required' => 'A Longitude deve ser informada',
            'image.required' => 'A Imagem deve ser informada',
        ];


        $this->validate($request, [
            'identificacao' => 'required|string',
            'localizacao' => 'required|string',
            'posicao' => 'required|string',
            'dimensao' => 'required|string',
            'dimensao_lona' => 'required|string',
            'ponto_referencia' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'image'  => 'required|string',
        ], $customMessages);

        
    }


}