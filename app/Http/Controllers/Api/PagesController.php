<?php

namespace App\Http\Controllers\Api;


use App\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PagesController extends ApiController
{
    public function about()
    {
        $page = Page::select('title', 'content')->where('id', 1)->first();

        if(!empty($page))
        {
            $data['title'] = $page->title;
            $data['content'] = $page->getPs();

            $this->response->setData($data);
            $this->response->toggleSuccess();
        }

        return $this->response->responseData();
    }

    public function howPlay()
    {
        $page = Page::where('id', 2)->first();

        if(!empty($page))
        {
            $data['title'] = $page->title;
            $data['content'] = $page->getPs();

            $data['playTitle1'] = $page->item_title1;
            $data['playTitle2'] = $page->item_title2;
            $data['playTitle3'] = $page->item_title3;
            $data['playContent1'] = $page->item_desc1;
            $data['playContent2'] = $page->item_desc2;
            $data['playContent3'] = $page->item_desc3;

            $this->response->setData($data);
            $this->response->toggleSuccess();
        }

        return $this->response->responseData();
    }

    
}
