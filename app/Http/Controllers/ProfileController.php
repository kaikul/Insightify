<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Token as Token;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $applicationNames = $this->getAppNames();
        return view('profileSetup', compact('applicationNames'));
    }

    protected function getAppNames()
     {
        $applicationNames = Application::select('name', 'application_id')
        ->get()
        ->pluck('name', 'application_id')
        ->toArray();


        return $applicationNames;
    }

    protected function saveAPITokens(Request $request)
    {
        // Get Application Data
        $appNames = Application::pluck('name', 'application_id')->toArray();
    
        
        $inputNames = [];
    
        // Iterate through the applications and check for their corresponding inputs
        foreach ($appNames as $appId => $appName) {
            $inputName = strtolower($appName . '_' . $appId);
    
            // Check if input exists in the request
            if ($request->has($inputName)) {
                $token = new Token();
                $token->application_token = $request->input($inputName);
                $token->application_id_fk = $appId;

                if($request->has($inputName .'_id') && $request->has($inputName . '_secret')){
                    $token->application_ID=$request->input($inputName .'_id');
                    $token->application_secret=$request->input($inputName . '_secret');
                }
                $token->save();
                unset($appNames[$appId]);
               
            }
        }
    
        
        return redirect('profile')
        ->withInput()
        ->withErrors($appNames);
        
    }

    

}
