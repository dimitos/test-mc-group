<?
    const ROOT = __DIR__;

    include ROOT . "/php/config.php";
    include ROOT . "/vendor/autoload.php";
    include ROOT . "/php/common.php";

    // db_connect();
    // session_start();

    if (file_exists(ROOT . "/.env")) {
        Dotenv\Dotenv::createImmutable(ROOT)->load();
    } else {
        $_ENV["MODE"] = "prod";
    }

    if ($_ENV["MODE"] === "dev") {
        include ROOT . "/php/display-errors.php";
    }

    $page = get_page();
    $referer = new Referer();

    if ($page->code === 404) {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    }

    //инициализируем шаблонизатор
    $smarty = init_smarty();

    $smarty->assign([
        "page" => $page,
        "meta" => new Meta(),
        "inline_scripts" => external_scripts(actual_bundle_path("dist/js", "inline")),
        "vendor_scripts" => external_scripts(actual_bundle_path("dist/js", "vendor")),
        "common_scripts" => external_scripts(actual_bundle_path("dist/js", "common")),
        "vendor_styles" => external_styles(actual_bundle_path("dist/css/prod", "vendor")),
        "common_styles" => styles_by_mode("common"),
        "url" => get_url_lvls()
    ])->display($page->view);

    // db_disconnect();
