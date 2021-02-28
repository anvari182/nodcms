<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2021.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

namespace NodCMS\Core\View;


class Layout extends View
{
    // All add-ons css files in header tag
    public $css_files = array();

    // Add add-ons js files in header tag
    public $header_js_files = array();

    // All add-ons js files in footer content (append to body tag)
    public $footer_js_files = array();


    /**
     * Define controller public variables as view variables to use in the view files.
     * For example: $this->language, $this->settings, etc.
     * TODO: Change this routine.
     *
     * @param $controller
     */
    public function loadControllerVars($controller)
    {
        foreach($controller as $key=>$item) {
            $this->$key = $item;
        }
    }

    /**
     * Add a css file at your at the end of css pools
     *
     * @param string $path
     * @param string|null $ltr_path
     */
    public function addCssFile(string $path, string $ltr_path = null)
    {
        $this->addAsset($this->css_files, ".css", $path, $ltr_path);
    }

    /**
     * Add a js file at your at the end of js pools
     *
     * @param string $path
     * @param string|null $ltr_path
     */
    public function addJsFile(string $path, string $ltr_path = null)
    {
        $this->addAsset($this->footer_js_files, ".js", $path, $ltr_path);
    }

    /**
     * Add a js file at your in the header tag
     *
     * @param string $path
     * @param string|null $ltr_path
     */
    public function addHeaderJsFile(string $path, string $ltr_path = null)
    {
        $this->addAsset($this->header_js_files, ".js", $path, $ltr_path);
    }

    /**
     * Add all css files to your view files.
     * It will use on your main template frame file.
     */
    public function fetchAllCSS()
    {
        foreach ($this->css_files as $item){
            $file = ROOTPATH . "public/" . $item;
            if(!file_exists($file)) continue;
            echo "<link rel='stylesheet' href='".base_url($item)."'>\n";
        }
    }

    /**
     * Add all js files to your view files at the end of body tag.
     * It will use on your main template frame file.
     */
    public function fetchAllJS()
    {
        foreach ($this->footer_js_files as $item){
            $file = ROOTPATH . "public/" . $item;
            if(!file_exists($file)) continue;
            echo "<script type='text/javascript' src='".base_url($item)."'></script>\n";
        }
    }

    /**
     * Add all js files to your view files at the end of head tag.
     * It will use on your main template frame file.
     */
    public function fetchAllHeaderJS()
    {
        foreach ($this->header_js_files as $item){
            $file = ROOTPATH . "public/" . $item;
            if(!file_exists($file)) continue;
            echo "<script type='text/javascript' src='".base_url($item)."'></script>\n";
        }
    }

    /**
     * Add an asset file to an assets array
     *
     * @param $variable
     * @param string $file_type
     * @param string $path
     * @param string|null $ltr_path
     */
    private function addAsset(&$variable, string $file_type, string $path, string $ltr_path = null) {
        if($ltr_path != null) {
            if(!isset($this->language) || $this->language == null)
                return;
            if($this->language["rtl"]){
                if(!in_array($ltr_path . $file_type, $variable))
                    array_push($variable, $ltr_path . $file_type);
                return;
            }
        }

        if(!in_array($path . $file_type, $variable))
            array_push($variable, $path . $file_type);
    }
}