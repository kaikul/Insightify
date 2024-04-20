<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\ApiLogs;
use App\Models\Token as Token;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\InstagramService;
use Exception;
use Google\Service\Batch\Message;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PostController extends Controller
{
    public function postPage(Request $request)
    {
        // Fetch Instagram posts
        $posts = $this->GetPost($request);
    
        // Check if posts were successfully fetched
        if ($posts === false) {
            // Handle error when posts cannot be fetched
            return response()->json(['error' => 'Failed to fetch Instagram posts'], 500);
        }
     
       
    
        // Pass the fetched posts to the view
        return view('post', ['posts' => $posts]);
    }
    
  
    protected function GetPost(Request $request)
    {
        // Fetch the access token from the database based on application_id_fk = 2 and token_id = 17
        $token = Token::where('application_id_fk', 2)
                      ->where('token_id', 17)
                      ->first();
    
        if (!$token) {
            return response()->json(['error' => 'Access token not found'], 404);
        }
    
        $client = new Client();
        // Request towards the Instagram API to get posts
        $postsUrl = "https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,permalink,timestamp&access_token={$token->application_token}";
        $postsResponse = $client->get($postsUrl);
    
        if ($postsResponse->getStatusCode() != 200) {
            return response()->json(['error' => 'API request failed'], $postsResponse->getStatusCode());
        }
    
        $posts = json_decode($postsResponse->getBody(), true)['data'];
       
        try{
            foreach ($posts as &$post) {
                // Fetch insights for each post
                $post_id = $post['id'];
                $insightsUrl = "https://graph.instagram.com/{$post_id}/insights?metric=reach&access_token={$token->application_token}";
                $insightsResponse = $client->get($insightsUrl);
        
                if ($insightsResponse->getStatusCode() == 200) {
                    $insights = json_decode($insightsResponse->getBody(), true)['data'];
                    // Merge post data with insights data
                    $post['insights'] = $insights;
                }
            }
        }catch(Exception $e){
            dd($e->getMessage());
        }
    
        return $posts;
    }
    
    

    protected function SyncAll(Request $request)
    {
        $token = Token::where('application_id_fk', 2)
                       ->where('token_id', 17)
                       ->first();
    
        // Fetch Instagram posts
        $posts = $this->GetPost($request);
    
        // Flag to track if any post has not been synchronized
        $synced = false;
    
        foreach ($posts as $post) {
            // Check if $post has 'id' key
            if (isset($post['id'])) {
                // Check if the post exists in the database
                if (!$this->CheckPost($post['id'])) {
                    // Save the post into the database
                    $apiLog = new ApiLogs();
                    $apiLog->log_id = $post['id'];
                    $apiLog->application_id = $token->application_id_fk;
                    $apiLog->token_id = $token->token_id;
                    $apiLog->log = json_encode([
                        'id' => $post['id'],
                        'caption' => $post['caption'] ?? null, // Handle if 'caption' is not set
                        'media_type' => $post['media_url'] ?? null // Handle if 'media_url' is not set
                    ]);
    
                    // Check if saving was successful
                    if ($apiLog->save()) {
                        // Set the flag to true if posts are synchronized
                        $synced = true;
                    }
                }
            }
        }
    
        if ($synced) {
            return response()->json(['status' => 'success', 'message' => 'Data has been successfully synchronized!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Posts are already synchronized in the database.']);
        }
    }
    
    
}
