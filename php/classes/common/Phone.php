<?

    /**
     * Класс телефона
     */
    class Phone
    {
        /**
         * Телефон для отображения
         * @var string
         */
        public $title;
        /**
         * Телефон для ссылок href
         * @var string
         */
        public $link;

        /**
         * Функция преобразовывает телефон в ссылку для верстки
         * @param string $phone_in
         * @return string
         */
        protected function phone_to_href(string $phone_in): string
        {
            return "tel:" . normalize_phone($phone_in, true);
        }

        /**
         * Phone constructor
         * @param string $phone
         */
        function __construct(string $phone)
        {
            $this->title = format_phone($phone);
            $this->link = $this->phone_to_href($phone);
        }
    }
