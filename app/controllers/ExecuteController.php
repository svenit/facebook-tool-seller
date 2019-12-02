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
                        'msg' => 'Xác thực cookie thành công !',
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
                'msg' => 'Đã tìm thấy danh sách nhóm',
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
            $share = $this->requestWithFields('https://www.facebook.com/ajax/updatestatus.php?av='.$request['id'].'','album_id&asset3d_id&asked_fun_fact_prompt_data&attachment[params][0]='.$request['postId'].'&attachment[type]=11&attachment[reshare_original_post]=false&attachment[shared_from_post_id]='.$request['postId'].'&audience&boosted_post_config&breaking_news_expiration=0&breaking_news_selected=false&cta_data&composer_entry_point=group&composer_entry_time=202&composer_session_id=ce18bace-1d94-4472-9f86-f54b77f85be0&composer_session_duration=10&composer_source_surface=group&composertags_city&composertags_place&civic_product_source&direct_share_status=0&sponsor_relationship=0&branded_content_data&extensible_sprouts_ranker_request&feed_topics&find_players_info&fun_fact_prompt_id&group_post_tag_ids&hide_object_attachment=false&has_support_now_cta=false&is_explicit_place=false&is_markdown=false&is_post_to_group=false&is_welcome_to_group_post=false&is_q_and_a=false&is_profile_badge_post=false&story_list_attachment_data&local_alert_expiration&multilingual_specified_lang=&num_keystrokes=6&num_pastes=0&place_attachment_setting=1&poll_question_data&privacyx&prompt_id&prompt_tracking_string&publisher_abtest_holdout&ref=group&stories_selected=false&todo_list_data&timeline_selected=true&xc_sticker_id=0&event_tag&target_type=group&xhpc_message='.$request['message'].'&xhpc_message_text='.$request['message'].'&is_forced_reshare_of_post=false&xc_disable_config&delight_ranges=[]&holiday_card&xc_share_params=['.$request['postId'].']&xc_share_target_type=11&is_react=true&xhpc_composerid=rc.u_fetchstream_1_14&xhpc_targetid='.$request['idGroup'].'&xhpc_context=profile&xhpc_timeline=false&xhpc_finch=false&xhpc_aggregated_story_composer=false&xhpc_publish_type=1&xhpc_fundraiser_page=false&scheduled=false&unpublished_content_type&scheduled_publish_time&detection_analytics_data[detection_id]=7ab2a3b2-439c-43fe-a8c6-011f4721fdd3&detection_analytics_data[device_advertising_id]&detection_analytics_data[product_id]=54&__user='.$request['id'].'&__a=1&__req=31&__be=1&__pc=PHASED%3Aufi_home_page_pkg&dpr=1&__rev=1000712384&__s=7yzdog%3A6t3cm5%3Avkf3tc&fb_dtsg='.$request['fb_dtsg'].'&jazoest=22115&__spin_r=1000712384&__spin_b=trunk&__spin_t=1557889430',$request['cookie']);
            $postId = $this->DOM($share);
            if(isset($postId))
            {
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
    public function postToMarket($request)
    {
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
        $str = explode('for (;;);',$share);
        $str = str_replace('"{', "{", $str[1]);
        $str = str_replace('}"', "}", $str);
        $jsonObject = json_decode($str,TRUE);
        return isset($jsonObject['jsmods']['require'][2][3][1]['contentID']) ? $jsonObject['jsmods']['require'][2][3][1]['contentID'] : NULL ;
    }
}
