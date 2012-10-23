<?php
/**
 * если в параметрах ресурса в application.ini указать параметр canonical,
 * то этот параметр сравнится с текущим урлом и в случае не совпадения 
 * выставит его в мета-теги
 * параметр нужен для seo
 */
class myZend_Controller_Plugin_Canonical extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch($request)
    {
        if ($url = $request->getParam('canonical')) {
            $params = preg_match_all('|%(.*)%|U', $url, $matchesarray);
            if ($params) {
                foreach ($matchesarray[1] as $key) {
                    $replaces['%'. $key . '%'] = $request->getParam($key);
                }
                $url = str_replace(array_keys($replaces), array_values($replaces), $url);
            }
            
            $front = Zend_Controller_Front::getInstance();            
            $baseUrl = $front->getBaseUrl();
            
            if ($request->getRequestUri() !== '/'. $url) {
                $view = Zend_Controller_Front::getInstance()
                      ->getParam('bootstrap')
                      ->getResource('view');
                $view->headLink(
                    array(
                        'rel' => 'canonical', 
                        'href' => $baseUrl . '/' . $url
                    ), 'SET'
                );
            }
        }
    }
}
?>
