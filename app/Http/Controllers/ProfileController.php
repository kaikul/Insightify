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
                $token->save();
                unset($appNames[$appId]);
               
            }
        }
    dd($request->all());
        
        return redirect('profile')
        ->withInput()
        ->withErrors($appNames);
        
    }

}
