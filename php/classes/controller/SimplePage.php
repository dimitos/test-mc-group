<?

    /**
     * Класс данных для простой страницы
     */
    class SimplePage extends Page
    {

        /**
         * @param int $code
         * @param string $name
         * @param string $title
         * @throws Exception
         */
        public function __construct(int $code, string $name, string $title)
        {
            parent::__construct($code, $name, $title);
        }

        /**
         * @param string $eng
         * @param string $title
         * @return Page
         * @throws Exception
         */
        public static function code200(string $eng, string $title = ""): Page
        {
            return new SimplePage(200, $eng, $title);
        }
    }
