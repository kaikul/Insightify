<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Token as Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\InstagramService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ProfileController extends Controller
{

    protected $instagramService;
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

    public function postPage(Request $request)
{
    // Fetch Instagram posts
    $posts = $this->FetchInstagramPosts($request);

    // Check if posts were successfully fetched
    if ($posts === false) {
        // Handle error when posts cannot be fetched
        return response()->json(['error' => 'Failed to fetch Instagram posts'], 500);
    }

    // Pass the fetched posts to the view
    return view('post', ['posts' => $posts]);
}


    protected function FetchInstagramPosts(Request $request)
{
    // Fetch the access token from the database based on application_id_fk = 2 and token_id = 17
    $token = Token::where('application_id_fk', 2)
        ->where('token_id', 17)
        ->first();

    if (!$token) {
        // Handle the case when the access token is not found in the database
        return response()->json(['error' => 'Access token not found'], 404);
    }

    $client = new Client();
    //request toward the instagram Api
    $response = $client->get('https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,permalink,timestamp&access_token=' . $token->application_token);

    if ($response->getStatusCode() != 200) {
        // Handle the case when the API request fails
        return response()->json(['error' => 'API request failed'], $response->getStatusCode());
    }

    $posts = json_decode($response->getBody(), true)['data'];

    
    return $posts; 
}


   
}
 