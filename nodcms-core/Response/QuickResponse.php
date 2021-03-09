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

namespace NodCMS\Core\Response;

use Config\Services;

class QuickResponse
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $ajax;

    protected $message;

    protected $url;

    protected $data;

    /**
     * NodCMS Response type error
     */
    public const RESPONSE_TYPE_ERROR = 'error';

    /**
     * NodCMS Response type success
     */
    public const RESPONSE_TYPE_SUCCESS = 'success';

    /**
     * NodCMS Response type form error
     */
    public const RESPONSE_TYPE_FORM_ERROR = 'form-error';

    /**
     * NodCMS response types
     *
     * @var string[][]
     */
    private $_types = [
        self::RESPONSE_TYPE_ERROR => [
            'ajax' => [
                'status' => "error",
                'messageVar'=> "error"
            ],
            'redirect' => [
                'messageVar' => "error",
            ]
        ],
        self::RESPONSE_TYPE_SUCCESS => [
            'ajax' => [
                'status' => "success",
                'messageVar'=> "msg"
            ],
            'redirect' => [
                'messageVar' => "success",
            ]
        ],
        self::RESPONSE_TYPE_FORM_ERROR => [
            'ajax' => [
                'status' => "form-error",
                'messageVar'=> "error"
            ],
            'redirect' => [
                'messageVar' => "static_error",
            ]
        ],
    ];

    /**
     * Set the error message.
     *
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * Set a url to redirect
     *
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * Set additional data to add on a json response
     *
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Set the ajax response flag
     *
     * @param bool $value
     */
    public function setAjax(bool $value)
    {
        $this->ajax = $value;
    }

    /**
     * Returns response result
     *
     * @param string $type
     * @param string|null $message
     * @param string|null $uri
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     * @throws \Exception
     */
    private function get(string $type, string $message = null, string $uri = null)
    {
        if(!key_exists($type, $this->_types)) {
            throw new \Exception("Response type \"{$type}\" is undefined.");
        }

        $_type = $this->_types[$type];

        if($message != null)
            $this->setMessage($message);

        if($uri != null)
            $this->setUrl($uri);

        if($this->ajax === null) {
            $request = Services::request();
            $this->ajax = $request->isAJAX();
        }

        if($this->ajax){
            $data = array(
                "url"=>$this->url,
                "status"=>$_type['ajax']['status'],
            );

            if($this->message!=null)
                $data[$_type['ajax']['messageVar']] = $this->message;

            if(!empty($this->data))
                $data["data"] = $this->data;

            return json_encode($data);
        }

        if($this->message!=null) {
            $session = Services::session();
            $session->setFlashdata($_type['redirect']['messageVar'], $this->message);
        }
        return redirect()->to($this->url);
    }

    /**
     * Returns an error response
     *
     * @param string|null $message
     * @param string|null $uri
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     * @throws \Exception
     */
    public function getError(string $message = null, string $uri = null)
    {
        return $this->get(self::RESPONSE_TYPE_ERROR, $message, $uri);
    }

    /**
     * Returns a success response
     *
     * @param string|null $message
     * @param string|null $uri
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     * @throws \Exception
     */
    public function getSuccess(string $message = null, string $uri = null)
    {
        return $this->get(self::RESPONSE_TYPE_SUCCESS, $message, $uri);
    }

    /**
     * @param $ajaxMessage
     * @param $redirectMessage
     * @param $uri
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     * @throws \Exception
     */
    public function getFormError($ajaxMessage, $redirectMessage, $uri)
    {
        $message = Services::request()->isAJAX() ? $ajaxMessage : $redirectMessage;
        return $this->get(self::RESPONSE_TYPE_FORM_ERROR, $message, $uri);
    }
}