<?php

namespace Hungnm28\Meta;

class Meta
{
    public $title, $description, $url;
    public $metas = [];
    public $links = [];
    public $jsons = [];
    public $colors = [
        "c50" => "#f0fdf4",
        "c100" => "#dcfce7",
        "c200" => "#bbf7d0",
        "c300" => "#86efac",
        "c400" => "#4ade80",
        "c500" => "#22c55e",
        "c600" => "#16a34a",
        "c700" => "#15803d",
        "c800" => "#166534",
        "c900" => "#14532d",
        "c950" => "#052e16",
    ];


    public function set($data)
    {
        $this->title = $this->validateData(data_get($data, "title", $this->title));
        $this->description = $this->validateData(data_get($data, "description", $this->description));
        $this->url = $this->validateData(data_get($data, "url", $this->url));
    }

    public function setAll($data)
    {
        $this->title = $this->validateData(data_get($data, "title", $this->title));
        $this->description = $this->validateData(data_get($data, "description", $this->description));
        $this->url = data_get($data, "url", $this->url);
        $this->metas = data_get($data, "meta", $this->metas);
        $this->links = data_get($data, "link", $this->links);
        $this->jsons = data_get($data, "json", $this->jsons);
        $color = data_get($data,"color",[]);
        $this->setColor($color);
    }

    public function setJson($data, $name)
    {
        $this->jsons[$name] = $data;
    }

    public function removeJson($key)
    {
        $jsons = $this->jsons;
        if (isset($jsons[$key])) {
            unset($jsons[$key]);
        }
        $this->jsons = $jsons;
    }

    public function setBreadcrumb($name, $url, $k = '')
    {
        $data = data_get($this->jsons, 'breadcrumb.itemListElement', []);
        if ($k != "") {
            $k = intval($k);
        } else {
            $k = count($data);
        }
        $data[$k] = [
            "@type" => "ListItem",
            "position" => $k + 1
            , "name" => $name
            , "item" => $url
        ];
        $this->jsons['breadcrumb'] = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $data
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
        $data = data_get($this->jsons, "breadcrumb.itemListElement", []);
        if (empty($data) || !is_array($data)) {
            return null;
        }
        $rt = '<ul class="breadcrumb">';
        foreach ($data as $item) {
            $rt .= '<li class="item"><a href="' . $item['item'] . '" title="' . $item['name'] . '">' . $item['name'] . '</a></li>';
        }
        $rt .= '</ul>';
        return $rt;
    }

    public function showJson()
    {
        $results = [];
        foreach ($this->jsons as $json) {
            $results[] = '<script type="application/ld+json">' . json_encode($json, JSON_UNESCAPED_UNICODE) . '</script>';
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
        }
        return implode("\n", $result);
    }

    public function setColor($data)
    {
        if ($data) {
            $this->colors["c50"] = data_get($data, "c50", $this->colors["c50"]);
            $this->colors["c100"] = data_get($data, "c100", $this->colors["c100"]);
            $this->colors["c200"] = data_get($data, "c200", $this->colors["c200"]);
            $this->colors["c300"] = data_get($data, "c300", $this->colors["c300"]);
            $this->colors["c400"] = data_get($data, "c400", $this->colors["c400"]);
            $this->colors["c500"] = data_get($data, "c500", $this->colors["c500"]);
            $this->colors["c600"] = data_get($data, "c600", $this->colors["c600"]);
            $this->colors["c700"] = data_get($data, "c700", $this->colors["c700"]);
            $this->colors["c800"] = data_get($data, "c800", $this->colors["c800"]);
            $this->colors["c900"] = data_get($data, "c900", $this->colors["c900"]);
            $this->colors["c950"] = data_get($data, "c950", $this->colors["c950"]);
        }
    }

    public function getColors()
    {
        return $this->colors;
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
