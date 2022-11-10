<?

    /**
     * Класс url
     */
    class Url
    {
        public ?string $lvl1 = null;
        public ?string $lvl2 = null;
        public ?string $lvl3 = null;
        public ?string $lvl4 = null;

        /**
         * @param string|null $lvl1
         * @param string|null $lvl2
         * @param string|null $lvl3
         * @param string|null $lvl4
         */
        public function __construct(?string $lvl1, ?string $lvl2, ?string $lvl3, ?string $lvl4)
        {
            $this->lvl1 = $lvl1;
            $this->lvl2 = $lvl2;
            $this->lvl3 = $lvl3;
            $this->lvl4 = $lvl4;
        }
    }
