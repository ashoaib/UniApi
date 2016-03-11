<?php

    /**
     * Created by PhpStorm.
     * User: jkirkby91
     * Date: 10/03/2016
     * Time: 17:15
     */

    namespace UniApiClient;

    use UniApiClient\MimeTypes;
    use UniApiClient\Handlers\CsvHandler;
    use UniApiClient\Handlers\XmlHandler;
    use UniApiClient\Handlers\JsonHandler;
    use UniApiClient\Handlers\FormHandler;
    use UniApiClient\Handlers\HandlerAdapter;

    /**
     * Class Gateway
     *
     * @package UniApiClient
     */
    class UniApiClient
    {
        public $registered = false;
        private $mimeRegistrar = array();

        public function __Construct()
        {
            $this->registerHandlers(new MimeTypes);
        }

        /**
         * Register default mime handlers.  Is idempotent.
         */
        public function registerHandlers(MimeTypes $mimeTypes)
        {
            if ($this->registered === true) {
                return;
            }

            // @TODO create some kind of factory pattern
            $handlers = array(
                $mimeTypes::JSON => new JsonHandler,
                $mimeTypes::XML  => new XmlHandler,
                $mimeTypes::FORM => new formHandler,
                $mimeTypes::CSV  => new CsvHandler,
            );
            foreach ($handlers as $mime => $handler) {
                // Don't overwrite if the handler has already been registered
                if ($this->hasParserRegistered($mime))
                    continue;
                $this->register($mime, $handler);
            }
            $this->registered = true;
        }

        /**
         * Does this particular Mime Type have a parser registered
         * for it?
         * @param string $mimeType
         * @return bool
         */
        public function hasParserRegistered($mimeType)
        {
            return isset($this->mimeRegistrar[$mimeType]);
        }

        /**
         * @param $mimeType
         * @param HandlerAdapter $handler
         */
        public function register($mimeType, HandlerAdapter $handler)
        {
            $this->mimeRegistrar[$mimeType] = $handler;
        }
    }
