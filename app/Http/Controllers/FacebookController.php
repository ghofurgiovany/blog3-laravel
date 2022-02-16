<?php

namespace App\Http\Controllers;

use App\Models\Facebook\Page;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacebookController extends Controller
{
    public function auth()
    {
        return redirect('https://www.facebook.com/v12.0/dialog/oauth?' . http_build_query([
            'client_id'         =>  \setting('facebook_client_id'),
            'redirect_uri'      =>  url('/auth/facebook/callback', [], true),
            'state'             =>  '',
            'scope'             =>  implode(',', [
                'email',
                'ads_management',
                'business_management',
                'instagram_basic',
                'instagram_content_publish',
                'pages_read_engagement',
                'pages_manage_posts',
                'pages_show_list',
                'publish_to_groups',
                'groups_access_member_info',
                'pages_read_user_content',
            ])
        ]));
    }

    public function callback(Request $request)
    {
        $response       =   Http::get('https://graph.facebook.com/v12.0/oauth/access_token', [
            'client_id'     =>  \setting('facebook_client_id'),
            'redirect_uri'  =>  url('/auth/facebook/callback', [], true),
            'client_secret' =>  \setting('facebook_secret'),
            'code'          =>  $request->code
        ])->json();

        if (!isset($response['access_token'])) {
            return $response;
        }

        $token          =   $this->exchange($response['access_token']);

        Setting::upsert([
            [
                'key'   =>  'facebook_access_token',
                'value' =>  $token
            ]
        ], ['key']);

        // $token          =   'EAAGfUVZCXBZCwBAP9XuSdEVsR1P6GPsHsoSDkmaDAI2bEv6ZAayQomEJbXYPkgBSr9vBL4OWs3YIJ0OBXmpbBISPz5u8ywqEZCPH99eHOEGaOePrfH2dhcKw4o7IZAhubSkgURce8zl6ZCMsQFGVKpCLa4oD2mt2hCBzvYG5L9ZCwZDZD';
        $pages          =   Http::get('https://graph.facebook.com/me/accounts?' . \http_build_query([
            'access_token'  =>  $token
        ]))->json();

        if (!isset($pages['data'])) {
            throw new Exception("Cannot get pages data.", 1);
        }

        $pages          =   \collect($pages['data'])->map(function ($page) {
            Page::upsert([
                [
                    'page_id'       =>  $page['id'],
                    'name'          =>  $page['name'],
                    'access_token'  =>  $this->exchange($page['access_token'])
                ]
            ], ['page_id']);
        });

        return ['success'];
    }

    private function exchange(string $token)
    {
        $response       =   Http::get('https://graph.facebook.com/v12.0/oauth/access_token?' . \http_build_query([
            'grant_type'        =>  'fb_exchange_token',
            'client_id'         =>  \setting('facebook_client_id'),
            'client_secret'     =>  \setting('facebook_secret'),
            'fb_exchange_token' =>  $token
        ]))->json();

        if (!isset($response['access_token'])) {
            throw new Exception("Failed while exhange the access token", 1);
        }

        return $response['access_token'];
    }
}
