<?php


function url($url, array $params = null, $mergeParams = true)
{
    if (null === $url) {
        $url = Spirit::get('uri');
    }
    $ret = URL_PREFIX . $url;
    if (null !== $params) {
        if (true === $mergeParams) {
            $params = array_replace($_GET, $params);
        }
        $ret = URL_PREFIX . $url . '?' . http_build_query($params);
    }
    return htmlspecialchars($ret);
}


function __($identifier, $context = 'default', array $tags = [])
{
    static $terms = [];


    // load definitions for the given context
    if (array_key_exists($context, $terms)) {
        $defs = $terms[$context];
    } else {
        $defs = [];
        $file = APP_DICTIONARY_PATH . '/' . $context . '.php';
        if (false === file_exists($file)) {
            throw new \Exception("translation file not found: " . $file);
        }
        require $file;
        $terms[$context] = $defs;
    }


    // use the loaded definitions and check if there is a matching identifier
    if (array_key_exists($identifier, $defs)) {
        $value = $defs[$identifier];
        if (count($tags) > 0) {
            $ks = array_map(function ($v) {
                return '{' . $v . '}';
            }, array_keys($tags));
            $vs = array_values($tags);
            $value = str_replace($ks, $vs, $value);
        }
        return $value;
    } else {
        // error?
        throw new \Exception("__ error: dictionary term not found: " . $identifier);
        return $identifier;
    }
}

function ___()
{
    return htmlspecialchars(call_user_func_array('__', func_get_args()));
}


//------------------------------------------------------------------------------/
// BONUS FUNCTIONS, SO HANDFUL... (a huge time saver in the end)
//------------------------------------------------------------------------------/
if (!function_exists('a')) {
    function a()
    {
        foreach (func_get_args() as $arg) {
            ob_start();
            var_dump($arg);
            $output = ob_get_clean();
            if ('1' !== ini_get('xdebug.default_enable')) {
                $output = preg_replace("!\]\=\>\n(\s+)!m", "] => ", $output);
            }
            if ('cli' === PHP_SAPI) {
                echo $output;
            } else {
                echo '<pre>' . $output . '</pre>';
            }
        }
    }

    function az()
    {
        call_user_func_array('a', func_get_args());
        exit;
    }
}

