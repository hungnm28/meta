<?php

namespace Hungnm28\Meta;

class Meta
{
    public $title, $description, $url;
    public $metas = [];
    public $links = [];
    public $jsons = [];
    public $breadcrumb = [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => []

    ];


    public function set($data)
    {
        $this->title = $this->validateData(data_get($data, "title", $this->title));
        $this->description = $this->validateData(data_get($data, "description", $this->description));
        $this->url = $this->validateData(data_get($data, "url", $this->url));
    }

    public function setJson($data, $name)
    {
        $this->jsons[$name] = $data;
    }

    public function setBreadcrumb($data, $k = '')
    {
        if ($k != "") {
            $k = intval($k);
        } else {
            $k = count($this->breadcrumb["itemListElement"]);
        }
        $this->breadcrumb["itemListElement"][$k] = [
            "@type" => "ListItem",
            "position" => $k + 1
            , "name" => $data["name"]
            , "item" => $data["item"]
        ];
    }

    public function setMeta($data, $name = "")
    {
        if ($name != "") {
            $this->metas[$name] = $data;
        } else {
            $this->metas[] = $data;
        }

    }

    public function setLink($data, $name = "")
    {
        if ($name != "") {
            $this->links[$name] = $data;
        } else {
            $this->links[] = $data;
        }
    }

    public function display()
    {
        $results = [];
        $results[] = $this->showTitle();
        $results[] = $this->getMeta();
        $results[] = $this->getLink();

        return implode("\n", $results);
    }

    public function showBreadcrumb()
    {
        return '<script type="application/ld+json">' . json_encode($this->breadcrumb) . '</script>';
    }

    public function showJson()
    {
        $results = [];
        foreach ($this->jsons as $json) {
            $results[] = '<script type="application/ld+json">' . json_encode($json) . '</script>';
        }
        return implode("\n", $results);
    }

    public function showTitle()
    {
        return "<title>$this->title</title>";
    }

    public function getMeta()
    {
        $default = [];

        if ($this->title != "") {
            $default["og:title"] = $this->showMeta(["property" => "og:title", "itemprop" => "name", "content" => $this->title]);
        }
        if ($this->description != "") {
            $default["description"] = $this->showMeta(["name" => "description", "content" => $this->description]);
            $default["og:description"] = $this->showMeta(["property" => "og:description", "content" => $this->description]);
        }
        if ($this->url != "") {
            $default["og:url"] = $this->showMeta(["property" => "og:url", "itemprop" => "url", "content" => $this->url]);
        }
        foreach ($this->metas as $k => $meta) {
            if (is_numeric($k)) {
                $default[] = $this->showMeta($meta);
            } else {
                $default[$k] = $this->showMeta($meta);
            }

        }
        return implode("\n", $default);
    }

    public function getLink()
    {
        $result = [];
        if ($this->url) {
            $result["canonical"] = $this->showLink(["rel" => "canonical", "href" => $this->url]);
            foreach ($this->links as $k => $link) {
                if (is_numeric($k)) {
                    $result[] = $this->showLink($link);
                } else {
                    $result[$k] = $this->showLink($link);
                }
            }
            return implode("\n", $result);
        }
    }

    private function showLink($data)
    {
        $str = '<link ';
        foreach ($data as $name => $val) {
            if ($name != "" && $val != "") {
                $val = $this->validateData($val);
                $str .= "$name=\"$val\" ";
            }
        }
        $str .= ' />';
        return $str;
    }

    private function showMeta($data)
    {
        $str = "<meta ";
        foreach ($data as $name => $val) {
            if ($name != "" && $val != "") {
                $val = $this->validateData($val);
                $str .= "$name=\"$val\" ";
            }
        }
        $str .= " />";
        return $str;
    }

    private function validateData($str)
    {
        $str = strip_tags($str);
        $str = trim($str);
        return $str;
    }
}
