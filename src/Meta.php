<?php

namespace Hungnm28\Meta;

class Meta
{

    /**
     * @var array
     */
    private $attributes = [];
    /**
     * @var array
     */
    private $jsonLD = [];
    /**
     * @var array
     */
    private $links = [];

    /**
     * @param $attributes
     * @return array
     */
    public function set($attributes = [])
    {
        $this->attributes = array_replace_recursive($this->attributes, $attributes);

        return $this->attributes;
    }

    /**
     * @param $data
     * @return array
     */
    /*
    public function setJsonLDs($data = [])
    {
        $this->jsonLD = array_replace_recursive($this->jsonLD, $data);

        return $this->jsonLD;
    }

    /**
     * @param $data
     * @return array
     */
    /*
    public function setLinks($data = [])
    {
        $this->links = array_replace_recursive($this->links, $data);
        return $this->links;
    }

    /**
     * @param $displayTitle
     * @param $defaults
     * @return string
     */
    public function display($displayTitle = false, $defaults = [])
    {
        $metaAttributes = array_replace_recursive($defaults, $this->attributes);
        $results = array();

        if ($displayTitle && array_key_exists('title', $metaAttributes)) {
            $results[] = $this->titleTag($metaAttributes['title']);
        }
        foreach ($metaAttributes as $name => $content) {

            if ($name === 'keywords') {
                $keywords = $this->prepareKeywords($content);
                $results[] = $this->metaTag('keywords', $keywords);
            } elseif ($this->isAssociativeArray($content)) {
                $results = array_merge($results, $this->processNestedAttributes($name, $content));
            } else {
                foreach ((array)$content as $con) {
                    $results[] = $this->metaTag($name, $con);
                }
            }
        }
        return implode("\n", $results);
    }

    /**
     * @return string
     */
    /*
    public function displayJsonLDs()
    {
        $results = [];
        foreach ($this->jsonLD as $row) {
            $results[] = $this->jsonLdTag(json_encode($row));
        }
        return implode("\n", $results);
    }

    /**
     * @return string
     */
    /*
    public function displayLinks()
    {
        $results = [];
        foreach ($this->links as $row) {
            $results[] = $this->linkTag($row);
        }
        return implode("\n", $results);
    }

    /**
     * @return array
     */
    public function clear()
    {
        $this->attributes = array();

        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param $keywords
     * @return string|null
     */
    private function prepareKeywords($keywords)
    {
        if ($keywords === null)
            return null;

        if (is_array($keywords))
            $keywords = implode(', ', $keywords);

        return strtolower(strip_tags($keywords));
    }

    /**
     * @param $property
     * @param $content
     * @return array
     */
    private function processNestedAttributes($property, $content)
    {
        $results = array();

        if ($this->isAssociativeArray($content)) {
            foreach ($content as $key => $value) {
                $results = array_merge($results, $this->processNestedAttributes("{$property}:{$key}", $value));
            }
        } else {
            foreach ((array)$content as $con) {
                if ($this->isAssociativeArray($con))
                    $results = array_merge($results, $this->processNestedAttributes($property, $con));
                else
                    $results[] = $this->metaTag($property, $con);
            }
        }

        return $results;
    }

    /**
     * @param $value
     * @return bool
     */
    public function isAssociativeArray($value)
    {
        return is_array($value) && (bool)count(array_filter(array_keys($value), 'is_string'));
    }

    /**
     * @param $name
     * @param $content
     * @return string
     */
    private function metaTag($name, $content)
    {
        if (substr($name, 0, 3) == 'og:' || substr($name, 0, 3) == 'fb:')
            return "<meta property=\"$name\" content=\"" . htmlspecialchars($content) . "\"/>";
        else
            return "<meta name=\"$name\" content=\"" . htmlspecialchars($content) . "\"/>";
    }

    /**
     * @param $content
     * @return string
     */
    private function titleTag($content)
    {
        return "<title>" . htmlspecialchars($content) . "</title>";
    }

    /**
     * @param $content
     * @return string
     */
    private function linkTag($content)
    {

        $attr = ' ';
        foreach ($content as $key => $val) {
            $attr .= $key . '="' . trim($val) . '" ';
        }
        return '<link ' . $attr . ' />';
    }

    /**
     * @param $content
     * @return string
     */

    private function jsonLdTag($content)
    {
        return '<script type="application/ld+json">' . $content . '</script>';
    }

    /**
     * @param $array
     * @param $key
     * @return mixed|null
     */
    private function removeFromArray(&$array, $key)
    {
        if (array_key_exists($key, $array)) {
            $val = $array[$key];
            unset($array[$key]);
            return $val;
        }
        return null;
    }
}
