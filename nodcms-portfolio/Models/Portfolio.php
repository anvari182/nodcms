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
 *  @since      Version 3.1.0
 *  @filesource
 *
 */

namespace NodCMS\Portfolio\Models;

use NodCMS\Core\Models\Model;

class Portfolio extends Model
{
    public function init()
    {
        $table_name = "portfolio";
        $primary_key = "portfolio_id";
        $fields = array(
            'portfolio_id'=>"INT(11) UNSIGNED NOT NULL AUTO_INCREMENT",
            'portfolio_name'=>"VARCHAR(255) NULL DEFAULT NULL",
            'portfolio_image'=>"VARCHAR(255) NULL DEFAULT NULL",
            'portfolio_public'=>"INT(1) UNSIGNED NULL DEFAULT NULL",
            'portfolio_date'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
            'created_date'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = array('title','details');
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}