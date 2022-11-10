<?

    /**
     * Класс определения источника
     */
    class Referer
    {
        /**
         * UTM метки переработанные
         * @var UTM
         */
        public UTM $utm_intop;
        /**
         * UTM метки оригинальные
         * @var UTM
         */
        public UTM $utm_original;
        /**
         * Источник перехода
         * @var string
         */
        public string $referer = "";
        /**
         * Тип источника
         * @var string
         */
        private string $ref_type = "unknown";

        /**
         * Referer constructor
         */
        function __construct()
        {
            $this->utm_intop = new UTM();
            $this->utm_original = new UTM();

            if (isset($_GET["utm_campaign"]) || isset($_GET["utm_source"])) {
                $this->convert_input();
            } elseif (isset($_SESSION["ref_type"])) {
                $this->convert_stored($_SESSION);
            } elseif (isset($_COOKIE["ref_type"])) {
                $this->convert_stored($_COOKIE);
            }

            // Если мы узнали источник, то запишем его
            if (($this->utm_intop->utm_campaign != "") || ($this->utm_intop->utm_source != "")) {
                $this->store_source();
            } else {
                $this->convert_seo();
            }
        }

        /**
         * Присваиваем referer и все метки из сохраненного массива
         * @param array $source
         */
        private function convert_stored(array $source): void
        {
            $this->ref_type = $source["ref_type"];
            if (isset($source["referer"])) {
                $this->referer = $source["referer"];
            }
            if (isset($source["utm_campaign"])) {
                $this->utm_intop->utm_campaign = $source["utm_campaign"];
            }
            if (isset($source["utm_source"])) {
                $this->utm_intop->utm_source = $source["utm_source"];
            }
            if (isset($source["utm_medium"])) {
                $this->utm_intop->utm_medium = $source["utm_medium"];
            }
            if (isset($source["utm_term"])) {
                $this->utm_intop->utm_term = $source["utm_term"];
            }
            if (isset($source["utm_content"])) {
                $this->utm_intop->utm_content = $source["utm_content"];
            }
            if (isset($source["utm_campaign2"])) {
                $this->utm_original->utm_campaign = $source["utm_campaign2"];
            }
            if (isset($source["utm_source2"])) {
                $this->utm_original->utm_source = $source["utm_source2"];
            }
            if (isset($source["utm_medium2"])) {
                $this->utm_original->utm_medium = $source["utm_medium2"];
            }
            if (isset($source["utm_term2"])) {
                $this->utm_original->utm_term = $source["utm_term2"];
            }
            if (isset($source["utm_content2"])) {
                $this->utm_original->utm_content = $source["utm_content2"];
            }
        }

        /**
         * Присваиваем referer и все метки из get параметров
         */
        private function convert_input(): void
        {
            if (isset($_SERVER["HTTP_REFERER"])) {
                $this->referer = $_SERVER["HTTP_REFERER"];
            }
            if (isset($_GET["utm_campaign"])) {
                $this->utm_intop->utm_campaign = trim(strtolower($_GET["utm_campaign"]));
                $this->utm_original->utm_campaign = $this->utm_intop->utm_campaign;
            }
            if (isset($_GET["utm_source"])) {
                $this->utm_intop->utm_source = trim(strtolower($_GET["utm_source"]));
                $this->utm_original->utm_source = $this->utm_intop->utm_source;
            }
            if (isset($_GET["utm_medium"])) {
                $this->utm_intop->utm_medium = $_GET["utm_medium"];
                $this->utm_original->utm_medium = $this->utm_intop->utm_medium;
            }
            if (isset($_GET["utm_term"])) {
                $this->utm_intop->utm_term = $_GET["utm_term"];
                $this->utm_original->utm_term = $this->utm_intop->utm_term;
            }
            if (isset($_GET["utm_content"])) {
                $this->utm_intop->utm_content = $_GET["utm_content"];
                $this->utm_original->utm_content = $this->utm_intop->utm_content;
            }

            if (in_array($this->utm_original->utm_campaign, ["v1", "v2", "v3", "v4", "v5", "v1-ekb", "v2-ekb", "v3-ekb", "v4-ekb", "v5-ekb", "intop-sales-v1", "intop-sales-v2", "intop-sales-v3", "intop-sales-v4", "intop-sales-v5", "soc"])) {
                if ($this->utm_original->utm_source == "soc") {
                    $this->ref_type = "soc";
                    $this->utm_intop->utm_campaign = "InTop SMM продвижение";
                    $this->utm_intop->utm_source = "Социальные сети";
                } else {
                    if (preg_match("/^ga.*/", $this->utm_original->utm_source)) {
                        $this->utm_intop->utm_source = "Google Ads Поиск";
                    } elseif (preg_match("/^yd.*/", $this->utm_original->utm_source)) {
                        $this->utm_intop->utm_source = "Яндекс Директ Поиск";
                    } elseif (preg_match("/^rsya.*/", $this->utm_original->utm_source)) {
                        $this->utm_intop->utm_source = "Яндекс Директ РСЯ";
                    } else {
                        $this->utm_intop->utm_source = "Интернет-реклама";
                    }

                    if (preg_match("/.+-ekb$/", $this->utm_original->utm_campaign)) {
                        $this->ref_type = "ekb";
                        $this->utm_intop->utm_campaign = "";
                    } elseif (preg_match("/.+-rus$/", $this->utm_original->utm_campaign)) {
                        $this->ref_type = "regions";
                        $this->utm_intop->utm_campaign = "Регионы России";
                    } elseif (preg_match("/.+-regions$/", $this->utm_original->utm_campaign)) {
                        $this->ref_type = "regions";
                        $this->utm_intop->utm_campaign = "Регионы России";
                    } else {
                        $this->ref_type = "ads";
                        $this->utm_intop->utm_campaign = "Общая кампания";
                    }
                }
            }
        }

        /**
         * Запоминаем все источники
         */
        private function store_source(): void
        {
            SetCookie("ref_type", $this->ref_type, time() + (60 * 60 * 24 * 14));
            $_SESSION["ref_type"] = $this->ref_type;
            SetCookie("utm_campaign", $this->utm_intop->utm_campaign, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_campaign"] = $this->utm_intop->utm_campaign;
            SetCookie("utm_source", $this->utm_intop->utm_source, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_source"] = $this->utm_intop->utm_source;
            SetCookie("utm_medium", $this->utm_intop->utm_medium, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_medium"] = $this->utm_intop->utm_medium;
            SetCookie("utm_term", $this->utm_intop->utm_term, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_term"] = $this->utm_intop->utm_term;
            SetCookie("utm_content", $this->utm_intop->utm_content, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_content"] = $this->utm_intop->utm_content;
            SetCookie("utm_campaign2", $this->utm_original->utm_campaign, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_campaign2"] = $this->utm_original->utm_campaign;
            SetCookie("utm_source2", $this->utm_original->utm_source, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_source2"] = $this->utm_original->utm_source;
            SetCookie("utm_medium2", $this->utm_original->utm_medium, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_medium2"] = $this->utm_original->utm_medium;
            SetCookie("utm_term2", $this->utm_original->utm_term, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_term2"] = $this->utm_original->utm_term;
            SetCookie("utm_content2", $this->utm_original->utm_content, time() + (60 * 60 * 24 * 14));
            $_SESSION["utm_content2"] = $this->utm_original->utm_content;
            SetCookie("referer", $this->referer, time() + (60 * 60 * 24 * 14));
            $_SESSION["referer"] = $this->referer;
        }

        /**
         * Присваиваем referer и все метки из SEO
         */
        private function convert_seo(): void
        {
            $this->utm_intop->utm_source = "SEO/другое";
            if (isset($_SERVER["HTTP_REFERER"])) {
                $this->referer = $_SERVER["HTTP_REFERER"];
            }
            if (stristr($this->referer, "avito.ru")) {
                $this->utm_intop->utm_source = "Avito";
            }
            if (stristr($this->referer, "cian.ru")) {
                $this->utm_intop->utm_source = "Cian";
            }
            if (stristr($this->referer, "domclick.ru")) {
                $this->utm_intop->utm_source = "ДомКлик";
            }
            if (stristr($this->referer, "instagram.com")) {
                $this->utm_intop->utm_source = "Instagram";
            }
            if (stristr($this->referer, "facebook.com")) {
                $this->utm_intop->utm_source = "Facebook";
            }
            if (stristr($this->referer, "vk.com")) {
                $this->utm_intop->utm_source = "ВКонтакте";
            }
            if (stristr($this->referer, "yandex.ru")) {
                $this->utm_intop->utm_source = "Yandex SEO";
            }
            if (stristr($this->referer, "google.ru")) {
                $this->utm_intop->utm_source = "Google SEO";
            }
            if (stristr($this->referer, "google.com")) {
                $this->utm_intop->utm_source = "Google SEO";
            }
            if (stristr($this->referer, "mail.ru")) {
                $this->utm_intop->utm_source = "Mail.Ru";
            }
            if (stristr($this->referer, "bing.com")) {
                $this->utm_intop->utm_source = "Bing";
            }
            if (stristr($this->referer, "2gis.ru")) {
                $this->utm_intop->utm_source = "2GIS";
            }
        }
    }
