<?php

namespace App\Tags;

use Statamic\Tags\Tags;

class Seo extends Tags
{
    /**
     * The {{ seo }} tag
     *
     * @return string|array
     */
    public function index()
    {
        $template = '';
        $title = '';

        $title = $this->context->value('title');
        $description = $this->context->value('description') ?: '';
        $template = $this->context->get('template');

        $name = "Let's Get Creative";
        $title = $title ? "$title - $name" : $name;
        $base = 'https://' . $_SERVER['SERVER_NAME'];
        $url = !empty($_SERVER['REQUEST_URI']) ? htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8', false) : '';

        $header = <<<EOT
        <title>$title</title>
        <meta property="og:title" content="$title" />
        <meta name="twitter:title" content="$title" />

        <meta name="description" content="$description" />
        <meta property="og:description" content="$description" />
        <meta name="twitter:description" content="$description" />

        <meta property="og:url" content="{$base}{$url}" />
        <meta property="og:type" content="website" />
        <meta property="og:image:alt" content="$name" />
        <meta name="twitter:card" content="summary_large_image" />
EOT;

        return $header;
    }
}
