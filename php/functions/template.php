<?

    /**
     * @return Smarty
     */
    function init_smarty(): Smarty
    {
        $smarty = new Smarty;
        $smarty->setCompileDir(ROOT . "/cache/templates")->setTemplateDir(ROOT . "/views");
        if ($_ENV["MODE"] === "dev") {
            $smarty->setCaching(Smarty::CACHING_OFF);
        }
        return $smarty;
    }

    /**
     * @param string $path
     * @return string
     */
    function file_content(string $path): string
    {
        $full_path = ROOT . "/{$path}";

        if (file_exists($full_path)) {
            return file_get_contents($full_path);
        }

        return "";
    }

    /**
     * @param string $folder
     * @param string $name
     * @return string
     */
    function actual_bundle_path(string $folder, string $name): string
    {
        $files_with_timestamp = array_map(function($file_path) {
            return [
                "path" => $file_path,
                "timestamp" => filectime($file_path)
            ];
        }, glob("{$folder}/*"));

        $target_files = array_filter($files_with_timestamp, function($file) use ($name) {
            return preg_match("/{$name}(\.[0-9a-f]{8})?\.(css|js)$/", $file["path"]);
        });

        if ($target_files) {
            usort($target_files, function ($a, $b) {
                return $b["timestamp"] - $a["timestamp"];
            });

            return $target_files[0]["path"];
        } else {
            return "";
        }
    }

    /**
     * @param string $name
     * @return string
     */
    function styles_by_mode(string $name): string
    {
        if ($_ENV["MODE"] === "dev") {
            return external_styles(actual_bundle_path("dist/css/dev", $name));
        } else {
            return inline_styles(actual_bundle_path("dist/css/prod", $name));
        }
    }

    /**
     * @param string $path
     * @return string
     */
    function external_styles(string $path): string
    {
        if (file_exists(ROOT . "/{$path}")) {
            return "<link rel='stylesheet' href='/{$path}'>";
        }

        return "";
    }

    /**
     * @param string $path
     * @return string
     */
    function inline_styles(string $path): string
    {
        if (file_exists(ROOT . "/{$path}")) {
            $content = file_content($path);
            return "<style>{$content}</style>";
        }

        return "";
    }

    /**
     * @param string $path
     * @return string
     */
    function external_scripts(string $path): string
    {
        if (file_exists(ROOT . "/{$path}")) {
            return "<script src='/{$path}'></script>";
        }

        return "";
    }

    /**
     * @param string $path
     * @return string
     */
    function inline_scripts(string $path): string
    {
        if (file_exists(ROOT . "/{$path}")) {
            $content = file_content($path);
            return "<script>{$content}</script>";
        }

        return "";
    }
