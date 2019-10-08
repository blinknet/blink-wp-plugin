<?php

namespace Blink;
defined('ABSPATH') or die;


class PostComment
{
    public $title;
    public $url;

    public function __construct($post_title, $post_url)
    {
        $this->title = $post_title;
        $this->url = $post_url;
    }
}