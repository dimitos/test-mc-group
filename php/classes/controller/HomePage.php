<?
    /**
     * Класс данных для страницы объектов
     */
    class HomePage extends Page
    {
        /**
         * @param int $code
         * @param string $name
         * @throws Exception
         */
        public function __construct(int $code, string $name)
        {
            parent::__construct($code, $name, "Home");
        }

        /**
         * @return Page
         * @throws Exception
         */
        public static function code200(): Page
        {
            return new HomePage(200, "home");
        }
    }
