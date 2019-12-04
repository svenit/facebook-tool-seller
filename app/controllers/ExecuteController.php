<?php

namespace App\Controllers;
use App\Controllers\BaseController;
error_reporting(0);
class ExecuteController extends BaseController
{
    public function __construct()
    {
        return parent::__construct();
    }
    public function checkCookie($request)
    {
        if(isset($request['cookie']) && trim($request['cookie']))
        {
            $data = $this->requestWithCookie('https://facebook.com/profile.php',$request['cookie']);
            if(isset($data))
            {
                $userDetail = $this->regex($data);

                if(isset($userDetail['id'],$userDetail['fbDtsg']))
                {
                    return json_encode([
                        'msg' => 'Xin chào, '.$userDetail['name'].' ! Xác thực cookie thành công !',
                        'id' => $userDetail['id'],
                        'fb_dtsg' => $userDetail['fbDtsg'],
                        'type' => 'success',
                        'status' => 200
                    ]);
                }
                else
                {
                    return json_encode([
                        'type' => 'error',
                        'msg' => 'Cookie lỗi'
                    ]);
                }
            }
            else
            {
                return json_encode([
                    'type' => 'error',
                    'msg' => 'Cookie lỗi'
                ]);
            }
        }
        else
        {
            return json_encode([
                'type' => 'error',
                'msg' => 'Bạn chưa nhập cookie',
                'status' => 201
            ]);
        }
    }
    public function getGroupId($request)
    {
        $endpoint = 'https://www.facebook.com/api/graphql/';
        $data = [
            'fb_dtsg' => $request['fb_dtsg'],
            'doc_id' => 2315204321859694,
            'variables' => json_encode([
                'count' => 1000
            ])
        ];
        $listGroupId = json_decode($this->requestWithFields($endpoint,$data,$request['cookie']),TRUE);
        $logs = @file_get_contents(__DIR__.'../../../logs/'.date('d-m-Y').'.txt');
        foreach($listGroupId['data']['viewer']['account_user']['groups']['edges'] as $key => $groupId)
        {
            $response[$key]['id'] = $groupId['node']['id'];
            $response[$key]['name'] = $groupId['node']['name'];
            $group = $groupId['node']['id'];
            if(strpos("$logs","$group") !== FALSE)
            {
                $response[$key]['published'] = true;
            }
            else
            {
                $response[$key]['published'] = false;
            }
        }
        if(count($response) > 0)
        {
            return json_encode([
                'msg' => 'Đã tìm thấy '.count($response).' nhóm',
                'list_id' => $response,
                'type' => 'success',
                'status' => 200
            ]);
        }
        else
        {
            return json_encode([
                'msg' => 'Không tìm thấy nhóm nào',
                'type' => 'error',
                'status' => 201
            ]);
        }
    }
    public function shareLiveStream($request)
    {
        if(isset($request))
        {
            $share = $this->requestWithFields('https://www.facebook.com/ajax/updatestatus.php?av='.$request['id']. '','album_id&asset3d_id&asked_fun_fact_prompt_data&attachment[params][0]='.$request['postId'].'&attachment[type]=11&attachment[reshare_original_post]=false&attachment[shared_from_post_id]='.$request['postId'].'&audience&boosted_post_config&breaking_news_expiration=0&breaking_news_selected=false&cta_data&composer_entry_point=group&composer_entry_time=202&composer_session_id=ce18bace-1d94-4472-9f86-f54b77f85be0&composer_session_duration=10&composer_source_surface=group&composertags_city&composertags_place&civic_product_source&direct_share_status=0&sponsor_relationship=0&branded_content_data&extensible_sprouts_ranker_request&feed_topics&find_players_info&fun_fact_prompt_id&group_post_tag_ids&hide_object_attachment=false&has_support_now_cta=false&is_explicit_place=false&is_markdown=false&is_post_to_group=false&is_welcome_to_group_post=false&is_q_and_a=false&is_profile_badge_post=false&story_list_attachment_data&local_alert_expiration&multilingual_specified_lang=&num_keystrokes=6&num_pastes=0&place_attachment_setting=1&poll_question_data&privacyx&prompt_id&prompt_tracking_string&publisher_abtest_holdout&ref=group&stories_selected=false&todo_list_data&timeline_selected=true&xc_sticker_id=0&event_tag&target_type=group&xhpc_message='.$request['message'].'&xhpc_message_text='.$request['message'].'&is_forced_reshare_of_post=false&xc_disable_config&delight_ranges=[]&holiday_card&xc_share_params=['.$request['postId'].']&xc_share_target_type=11&is_react=true&xhpc_composerid=rc.u_fetchstream_1_14&xhpc_targetid='.$request['idGroup'].'&xhpc_context=profile&xhpc_timeline=false&xhpc_finch=false&xhpc_aggregated_story_composer=false&xhpc_publish_type=1&xhpc_fundraiser_page=false&scheduled=false&unpublished_content_type&scheduled_publish_time&detection_analytics_data[detection_id]=7ab2a3b2-439c-43fe-a8c6-011f4721fdd3&detection_analytics_data[device_advertising_id]&detection_analytics_data[product_id]=54&__user='.$request['id'].'&__a=1&__req=31&__be=1&__pc=PHASED%3Aufi_home_page_pkg&dpr=1&__rev=1000712384&__s=7yzdog%3A6t3cm5%3Avkf3tc&fb_dtsg='.$request['fb_dtsg'].'&jazoest=22115&__spin_r=1000712384&__spin_b=trunk&__spin_t=1557889430',$request['cookie']);
            $postId = $this->DOM($share);
            if(isset($postId))
            {
                $time = date('d-m-Y');
                $file = fopen(__DIR__.'../../../logs/'.$time.'.txt','a+');
                fwrite($file,$request['idGroup']."\n");

                $pendingMode = $this->checkPendingMode($postId,$request);
                if($pendingMode['status'] == 1)
                {
                    $msg = 'Chia sẻ thành công [ Chờ duyệt ]';
                }
                else
                {
                    $msg = 'Chia sẻ thành công';
                }
                return json_encode([
                    'post_id' => $postId,
                    'group_name' => $pendingMode['groupName'], 
                    'status' => 200,
                    'type' => 'success',
                    'msg' => $msg,
                    'group_id' => $request['idGroup']
                ]);
            }
            else
            {
                $reason = $this->regexReason($share);
                return json_encode([
                    'status' => 201,
                    'group_name' => $request['groupName'], 
                    'type' => 'error',
                    'msg' => 'Chia sẻ thất bại',
                    'reason' => $reason,
                    'group_id' => $request['idGroup']
                ]);
            }
        }
    }
    public function sharePost($request)
    {
        $time = date('d-m-Y');
        $file = fopen(__DIR__.'../../../logs/'.$time.'.txt','a+');
        if(isset($request))
        {
            $endpoint = "https://www.facebook.com/share/dialog/submit/?audience_type=group&audience_targets[0]=".$request['idGroup']."&composer_session_id=6b0dc63b-7f4d-4e23-abf1-58150d40c6ec&ephemeral_ttl_mode=0&internalextra[feedback_source]=22&message=".urlencode($request['message'])."&post_id=".$request['postId']."&share_to_group_as_page=false&share_type=22&shared_ad_id=&source=osbach&is_throwback_post=false&url=&shared_from_post_id=".$request['postId']."&logging_session_id=31d3a8ba-4a06-4e62-9e13-11d33d3354b0&perform_messenger_logging=true&video_start_time_ms=0&is_app_content_token=false&av=".$request['id'];
            $data = [
                'fb_dtsg' => $request['fb_dtsg'],
                '__spin_t' => 1575217879,
            ];
            $this->requestWithFields($endpoint,$data,$request['cookie']);
            fwrite($file,$request['idGroup']."\n");
            return json_encode([
                'post_id' => $request['postId'],
                'group_name' => $request['groupName'], 
                'status' => 200,
                'type' => 'success',
                'msg' => 'Chia sẻ bài viết thành công !',
                'group_id' => $request['idGroup']
            ]);
        }
    }
    public function checkPendingMode($postId,$request)
    {
        $endpoint = "https://www.facebook.com/$postId?fb_dtsg=".$request['fb_dtsg'];
        $data = [
            'fb_dtsg' => $request['fb_dtsg']
        ];
        $checkStatus = $this->requestWithFields($endpoint,$data,$request['cookie']);
        if(strpos($checkStatus,"URL=/groups/".$request['idGroup']."/pending/"))
        {
            return [
                'status' => 1,
                'groupName' => preg_match('#<title id="pageTitle">(.+?)</title>#is',$checkStatus, $matches) ? $matches[1] : '???'
            ];
        }
        else
        {
            return [
                'status' => 0,
                'groupName' => preg_match('#<title id="pageTitle">(.+?)</title>#is',$checkStatus, $matches) ? $matches[1] : '???'
            ];
        }
    }
    public function postToGroup($request)
    {
        $endpoint = count($request['attach']) > 0 ? "https://www.facebook.com/composerx/intercept/media/?xhpc_message=".urlencode($request['message'])."&xhpc_composerid=rc.js_16u&xhpc_targetid=".$request['idGroup']."&av=".$request['id'] : "https://www.facebook.com/composerx/intercept/status/?xhpc_message=".urlencode($request['message'])."&xhpc_composerid=rc.u_fetchstream_6_12&xhpc_targetid=".$request['idGroup']."&av=".$request['id'];
        $data = [
            'xhpc_aggregated_story_composer' => false,
            'xhpc_publish_type' => 1,
            'xhpc_fundraiser_page' => false,
            'scheduled' => false,
            'unpublished_content_type' => null,
            'scheduled_publish_time' => null,
            'application' => 'composer',
            'xhpc_message' => $request['message'],
            'xhpc_message_text' => $request['message'],
            'slideshow_spec' => null,
            'waterfallxapp' => 'web_react_composer',
            '__user' => $request['id'],
            '__a' => 1,
            '__csr' =>  null,
            '__pc' => 'PHASED:DEFAULT',
            'fb_dtsg' => $request['fb_dtsg'],
            'jazoest' => 22121,
            '__spin_r' => 1001482095,
            '__spin_b' => 'trunk',
            '__spin_t' => 1575339071,
        ];

        if(count($request['attach']) > 0)
        {
            foreach($request['attach'] as $key => $attach)
            {
                $data["composer_unpublished_photo[$key]"] = (int)$attach['id'];
            }
        }
        
        $payload = $this->requestWithFields($endpoint,$data,$request['cookie']);
        $post = json_decode($this->removeTag($payload),TRUE);

        if(isset($post['error']))
        {
            return json_encode([
                'type' => 'error',
                'group_id' => $request['idGroup'],
                'group_name' => $request['groupName'], 
                'msg' => 'Chia sẻ thất bại ( '.$post['errorDescription'].' )',
                'reason' => $post['errorDescription'],
                'status' => 201
            ]);
        }
        else
        {
            $data = explode('for (;;);',$payload);
            $data = json_decode($data[1],TRUE);

            if(empty($data['payload']['story_fbid']))
            {
                $response = [
                    'group_name' => $request['groupName'], 
                    'status' => 201,
                    'type' => 'error',
                    'msg' => 'Lỗi xác thực cookie Facebook, xin vui lòng nhập cookie mới',
                    'group_id' => $request['idGroup']
                ];
            }
            else
            {
                $time = date('d-m-Y');
                $file = fopen(__DIR__.'../../../logs/'.$time.'.txt','a+');
                fwrite($file,$request['idGroup']."\n");

                $pendingMode = $this->checkPendingMode($data['payload']['story_fbid'],$request);
                $msg = isset($pendingMode) && $pendingMode['status'] == 1 ? 'Đăng bài viết thành công [ Chờ duyệt ]' : 'Đăng bài viết thành công';

                $response = [
                    'post_id' => $data['payload']['story_fbid'],
                    'group_name' => $request['groupName'], 
                    'status' => 200,
                    'type' => 'success',
                    'msg' => $msg,
                    'group_id' => $request['idGroup']
                ];
            }
            return json_encode($response);
        }
    }
    public function postToMarket($request)
    {
        $storyEndpoint = "https://www.facebook.com/webgraphql/mutation/?doc_id=1740513229408093";
        $storyData = [
            '__hsi' => '6766000130703526326-0',
            '__rev' => 1001480872,
            '__user' => $request['id'],
            '__spin_t' => 1575303313,
            '__spin_r' => 1001480872,
            'jazoest' => 21977,
            '__s' => 'dckpbm:y9yv6u:39vyre',
            'fb_dtsg' => $request['fb_dtsg'],
            '__dyn' => '7AgNeS4amaWxd2u6aJGi9FxqeCwKyaF3ozGaiheCHxG4U9ES2N6xCagjGqK6otyEnCwMyaGubyRUC6UnGiidBCBXyEjF3e3KFUmzUggOdxK4rh4jUXU8S69V8FfyGzpFQcy42G5UKbGVoyaxG4o4O5lwxCypHh43Hg-ezFEmUC1mDBg5uaCCy894Hx6WBBKdxyho-ez9ECnhteEdRVpo-7UKVEkyoOmi9yF85XximfKK5VEtxi4otQdhVoOjyEaLK6Ux4ojUC6p8gUScBKmbDzUkwAyECmudx62abxuE9EKfAmF48K-quV8ycx6bxm4UGWzU4uVQuFpUgy8y5XxPByoCeCgS5AbxSu5S7EsyUy4ErxG1fx6fAwDxy5qxNDxeu3G4p8tyb-2efxW8Kqi5pob89EbaxS2G',
            'variables' => json_encode([
                'client_mutation_id' => 'fe275e5e-0e9b-4f73-98fe-6b9598738d44',
                'actor_id' => $request['id'],
                'input' => [
                    'actor_id' => $request['id'],
                    'client_mutation_id' => '1f18b293-7740-4b0f-9896-7024da390f4c',
                    'source' => 'WWW',
                    'audience' => [
                        'to_id' => $request['idGroup'],
                    ],
                    'logging' => [
                        'composer_session_id' => '357cb267-5d42-4155-841a-420724b8ae21',
                        'ref' => 'group'
                    ],
                    'with_tags_ids' => [],
                    'multilingual_translations' => [],
                    'composer_source_surface' => 'group',
                    'composer_entry_point' => 'group',
                    'composer_entry_time' => 7,
                    'composer_session_events_log' => [
                        'composition_duration' => 42
                    ],
                    'branded_content_data' => [],
                    'direct_share_status' => 'NOT SHARED',
                    'sponsor_relationship' => 'WITH',
                    'web_graphml_migration_params' => [
                        'target_type' => 'group',
                        'xhpc_composerid' => 'rc.u_fetchstream_8_6',
                        'xhpc_context' => 'profile',
                        'xhpc_publish_type' => 'FEED_INSERT',
                        'waterfall_id' => '357cb267-5d42-4155-841a-420724b8ae21',
                        'xpost_to_marketplace' => true,
                        'xpost_target_ids' => $request['idGroup']
                    ],
                    'external_movie_data' => [],
                    'place_attachment_setting' => 'HIDE_ATTACHMENT',
                    'attachments' => [
                        0 => [
                            'photo' => [
                                'id' => 818748341890760,
                                'tags' => []
                            ]
                        ]
                    ],
                    'product_item' => [
                        'title' => 'Ban cho',
                        'item_price' => [
                            'price' => 1,
                            'currency' => 'VND'
                        ],
                        'location_page_id' => null,
                        'condition' => 'USED'
                    ]
                ]
            ])
        ];
        return $this->requestWithFields($storyEndpoint,$storyData,$request['cookie']);
        $endpoint = "https://www.facebook.com/async/publisher/creation-hooks/?av=".$request['id'];
        $data = [
            'data[audience][to_id]' => $request['idGroup'],
            'data[web_graphml_migration_params][target_type]' => 'group',
            'data[web_graphml_migration_params][xhpc_composerid]' => 'rc.u_0_21',
            'data[web_graphml_migration_params][xhpc_context]' => 'profile',
            'data[web_graphml_migration_params][xhpc_publish_type]' => 1,
            'data[web_graphml_migration_params][waterfall_id]' => '90d7e4b7-6a59-4ed4-80ac-75d58e922175',
            'data[web_graphml_migration_params][xpost_target_ids]' => $request['idGroup'],
            'data[is_local_dev_platform_app_instance]' => false,
            'data[is_page_recommendation]' => false,
            'data[media_attachments][0][photo][id]' => 818124525286475,
            'data[logging_ref]' => 'group',
            'story_id' => 'UzpfSTEwMDAxMjY2ODA1MTM2MjpWSzo1NTQ0NzUxNDg2ODA0MzM=',
            '__spin_t' => 1575262380,
            '__spin_b' => 'trunk',
            '__spin_r' => 1001480500,
            'jazoest' => 22087,
            'fb_dtsg' => $request['fb_dtsg'],
        ];
        return $this->requestWithFields($endpoint,$data,$request['cookie']);
    }
    public function regex($data)
    {
        if(preg_match('/access_token:"(.+?)",/s',$data,$matches))
        {
            $accessToken = $matches[1];
        }
        if(preg_match('#<title id="pageTitle">(.+?)</title>#is',$data, $matches))
        {
            $name = $matches[1];
        }
        if(preg_match('/profile_id:(.+?),/s',$data,$matches))
        {
            $id = $matches[1];
        }
        if(preg_match('#name="fb_dtsg" value="(.+?)"#is',$data, $matches))
        {
            $fbDtsg = $matches[1];
        }
        return [
            'accessToken' => $accessToken,
            'name' => $name,
            'id' => $id,
            'fbDtsg' => $fbDtsg
        ];
    }
    public function regexReason($share)
    {
        $str = explode('for (;;);',$share);
        $jsonObject = json_decode($str[1],TRUE);
        return $jsonObject['errorSummary'] ? $jsonObject['errorSummary'] : NULL;
    }
    public function DOM($share)
    {
        $str = $this->removeTag($share);
        $jsonObject = json_decode($str,TRUE);
        return isset($jsonObject['jsmods']['require'][2][3][1]['contentID']) ? $jsonObject['jsmods']['require'][2][3][1]['contentID'] : NULL ;
    }
    public function removeTag($string)
    {
        $str = explode('for (;;);',$string);
        $str = str_replace('"{', "{", $str[1]);
        $str = str_replace('}"', "}", $str);
        return $str;
    }
    public function uploadImage($request)
    {
        $endpoint = "https://upload.facebook.com/ajax/react_composer/attachments/photo/upload?av=100012668051362&__user=100012668051362&__a=1&__dyn=7AgNeS4amaWxd2u6aJGi9FxqeCwKAKGgS8WyAAjFGUqxe2qdwIhEpyA4WCHxC7oG5VK2W8GFUDyRUC6UnGiidBCBXyEjF3e2y4GDxqfx138S6UhJ4hfzLwzomVV8FfyGzpFQcy42G5UKbGVoyaxG4oqwNxlo8pECqQh0WQfzEWq5K9wlFVk1nyFFEy2haUhKFprzooAmfzEOq9BQnjG3tummfx-bKq58CcBAyoGK9wle5aGfKK5QmEqxi4ogzd3kumcAUG2HQEry4hxfyopAg_zoOmVoKufxi2iayppUTCy88AaxuE9EKfAmF48K-quV8ycx6bxm4UBeE-3-uVQuFpUgy8y5XxPByoCeCgS5AbxSu5Sbxa78K8xa6UqwjUhzV89UoxmEspUjDwWx6i7oy_wzzUuybCAxmm2O2q2OEtwGw&__csr=&__req=9f&__pc=PHASED%3ADEFAULT&dpr=2&__rev=1001480872&__s=hqcf9l%3A54z9ov%3Aaggatw&__hsi=6765876211464851522-0&fb_dtsg=AQEAgVF9BpWo%3AAQEHvzBX6Dvq&jazoest=22064&__spin_r=1001480872&__spin_b=trunk&__spin_t=1575303313";
        $data = [
            'fb_dtsg' => $request['fb_dtsg'],
            'qn' => 'd02e0b49-2074-440f-9304-01cc6183b6d5',
            'source' => 8,
            'profile_id' => $request['id'],
            'farr' => curl_file_create($_FILES['farr']['tmp_name'],$_FILES['farr']['type'],$_FILES['farr']['name']),
            'waterfallxapp' => 'web_react_composer',
            'upload_id' => rand(),
            'js_resized' => true,
            'original_file_size' => $_FILES['farr']['size'],
        ];
        
        $upload = $this->requestWithFields($endpoint,$data,$request['cookie']);
        $str = explode('for (;;);',$upload);
        $str = str_replace('"{', "{", $str[1]);
        $str = str_replace('}"', "}", $str);
        $jsonObject = json_decode($str,TRUE);

        if(isset($jsonObject['payload']))
        {
            return json_encode([
                'status' => 200,
                'msg' => 'Tải ảnh lên thành công',
                'type' => 'success',
                'photo_id' => $jsonObject['payload']['photoID'],
                'url' => $jsonObject['payload']['imageSrc']
            ]);
        }
        else
        {
            return json_encode([
                'status' => 201,
                'type' => 'error',
                'msg' => 'Tải lên không thành công',
            ]);
        }
    }
    public function getCookie($request)
    {
        $endpoint = "https://b-graph.facebook.com/auth/login?generate_session_cookies=1&email=".$request['username']."&password=".$request['password']."&access_token=6628568379%7Cc1e620fa708a1d5696fb991c1bde5662&method=POST";
        $get = json_decode($this->requestRaw($endpoint),TRUE);
        if(isset($get['error']['message']))
        {
            return json_encode([
                'type' => 'error',
                'code' => 201,
                'msg' => $get['error']['message']
            ]);
        }
        else
        {
            if(isset($get['access_token']))
            {
                $cookie = '';
                foreach($get['session_cookies'] as $key => $item)
                {
                    $cookie .= $item['name'].'='.$item['value'].';';
                }
                return json_encode([
                    'type' => 'success',
                    'code' => 200,
                    'msg' => 'Thành công',
                    'cookie' => $cookie,
                    'token' => $get['access_token']
                ]);
            }
            else
            {
                return json_encode([
                    'type' => 'error',
                    'code' => 201,
                    'msg' => 'Đã có lỗi xảy ra'
                ]);
            }
        }
    }
}
