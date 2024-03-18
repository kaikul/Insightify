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

        $token = new Token();
        //Get Applicaition Data


        $appNames = Application::select('name','application_id')
            ->get()
            ->pluck('name','application_id')
            ->toArray();

     
        //Compare the applications and the input from input page

        if (count($appNames) == count($request->post()))
            {
                $application_id = 0;
                $appCount=0;
                foreach ($appNames as $appId => $appName) {
                    $inputName = strtolower(implode('_', str_split($appName . '_' . $appId)));
            
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
}
