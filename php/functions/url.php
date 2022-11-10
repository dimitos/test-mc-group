<?

    /**
     * Редирект
     * @param string $location
     * @param int $code
     */
    function redirect(string $location, int $code = 303)
    {
        header("Location: $location", true, $code);
        exit;
    }

    /**
     * @return Page
     * @throws Exception
     */
    function get_page(): Page
    {
        $url = get_url_lvls();

        try {
            // Страница не найдена
            if ($url->lvl1 == "404") {
                return Page::code404();
            } elseif (!$url->lvl1) {
                return HomePage::code200();
            } elseif (file_exists(ROOT . "/views/pages/{$url->lvl1}.tpl")) {
                return SimplePage::code200($url->lvl1);
            } else {
                // Страница не найдена
                return Page::code404();
            }
        } catch (Exception $e) {
            return Page::code415();
        }
    }

    /**
     * @param string $url_in
     * @return Url
     */
    function get_url_lvls(string $url_in = ""): Url
    {
        if (!$url_in) {
            $url_in = $_SERVER["REQUEST_URI"];
        }
        $url_in = explode("?", $url_in, 5);
        $url_full = explode("/", $url_in[0]);
        $url_lvl1 = $url_full[1];
        $url_lvl2 = null;
        $url_lvl3 = null;
        $url_lvl4 = null;
        if (count($url_full) >= 3) {
            $url_lvl2 = $url_full[2];
        }
        if (count($url_full) >= 4) {
            $url_lvl3 = $url_full[3];
        }
        if (count($url_full) >= 5) {
            $url_lvl4 = $url_full[4];
        }

        return new Url($url_lvl1, $url_lvl2, $url_lvl3, $url_lvl4);
    }

    /**
     * Возвращает текущий URL
     * @param bool $with_query
     * @return string
     */
    function get_current_url(bool $with_query = true): string
    {
        $https = false;
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
            $https = true;
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https" || !empty($_SERVER["HTTP_X_FORWARDED_SSL"]) && $_SERVER["HTTP_X_FORWARDED_SSL"] == "on") {
            $https = true;
        }
        $REQUEST_PROTOCOL = $https ? "https" : "http";

        $current_url = $REQUEST_PROTOCOL . "://{$_SERVER["HTTP_HOST"]}";
        if ($with_query) {
            $current_url .= $_SERVER["REQUEST_URI"];
        } else {
            $url = explode("?", $_SERVER["REQUEST_URI"], 5);
            $current_url .= $url[0];
        }
        return $current_url;
    }

    /**
     * @param string $url
     * @param string $param
     * @return string
     */
    function remove_query_param(string $url, string $param): string
    {
        $url = preg_replace("/(&|\?)" . preg_quote($param) . "=[^&]*$/", "", $url);
        $url = preg_replace("/(&|\?)" . preg_quote($param) . "=[^&]*&/", "$1", $url);
        return $url;
    }
