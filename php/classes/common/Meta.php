<?
    /**
     * Класс данных для метатегов
     */
    class Meta
    {
        public $title = "test_mc_group";
        public $description = "test_mc_group";
        public $keywords = "test_mc_group";
        public $image = "/img/og-image.png";
        public $h1 = "test_mc_group";
        public $url;

        /**
         * Meta constructor
         */
        public function __construct()
        {
            $this->url = get_current_url(false);
        }
    }
