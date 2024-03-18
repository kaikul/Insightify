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
        return view('profileSetup');
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
                foreach ($request->post() as $keyToken => $valueToken)
                {
                    if ($appCount>count($appNames))
                        break;
                    $appCount=+1;

                    foreach ($appNames as $keyApp => $appValue)
                    {
                        if (strtolower($appValue) == $keyToken)
                        {

                            $application_id = $keyApp;
                            break;
                        }
                    }
                    unset($appNames[$application_id]);
                    $token = new Token();

                    $token->application_token = $valueToken;
                    $token->application_id_fk = $application_id;
                    $token->save();
                }
            }
        return redirect('profile')
            ->withInput ()
            ->withErrors ($appNames);

    }

    public function FetchAppNames(){

           //fetching Application Names and sending it to the view
        $applications= Application::select('name','application_id')
            ->get()
            ->pluck('name','application_id')
            ->toArray();

            return view('profileSetup',compact('applications'));

    }
}
