<?php
require_once(WPMU_PLUGIN_DIR . '/includes/wideimage/WideImage.php');

/**
 * Description of Mobilize
 *
 * @author rafael
 */
class Mobilize {
    const SETTINGS_NONCE = 'save-mobilize';
    const ADESIVE_NONCE = 'adesivenonce';
    const ENVIE_NONCE = 'envienonce';
    const OPTION_NAME = 'mobilize';
    
    static $errors = array('banners'=>array(), 'adesive'=>array(), 'redes'=>array(), 'envie'=>array());
    
    
    static function isActive($section){
        $option = self::getOption($section);
        
        return isset($option['active']);
    }
    
    static function getErrors($section = null){
        if($section)
            return self::$errors[$section];
        else
            return self::$errors;
    }

    static function addError($section, $error){
        self::$errors[$section][] = $error;
    }
    
    static function printSettingsNonce() {
        wp_nonce_field(self::SETTINGS_NONCE, self::SETTINGS_NONCE);
    }

    static function saveSettings() {
        if ($_POST && isset($_POST[self::SETTINGS_NONCE]) && wp_verify_nonce($_POST[self::SETTINGS_NONCE], self::SETTINGS_NONCE)) {
            $option = self::getOption();
            
            // para não perder as imagens quando salvar o post sem enviar outras imagens.
            // se for implementar mais de uma imagem por seção, tem que pensar num modo de deletar imagens
            $_POST['mobilize']['banners']['files'] = $option['banners']['files'];
            $_POST['mobilize']['adesive']['files'] = $option['adesive']['files'];
            
            self::handleBannerUploads();
            self::handleAdesiveUploads();
            

            self::updateOption($_POST['mobilize']);
        }
    }

    static function getOption($index = null) {
        $option = get_option(self::OPTION_NAME);
        if ($index)
            $result = @$option[$index];
        else
            $result = $option;
        
        return is_array($result) ? $result : array();
    }

    static function updateOption($option) {
        update_option(self::OPTION_NAME, $option);
    }

    static function deleteBunner($index = 0) {
        $option = self::getOption('banners');

        $filename = self::getBannerFilename($index);

        if ($filename && file_exists($filename))
            unlink($filename);
    }

     static function deleteAdesive($index = 0) {
        $option = self::getOption('adesive');

        $filename = self::getAdesiveFilename($index);

        if ($filename && file_exists($filename))
            unlink($filename);
    }

    
    static function getNumBanners(){
        $option = self::getOption('banners');
        return count($option['files']);
    }
    
    static function getBannerURL($index = 0) {
        $option = self::getOption('banners');

        if (isset($option['files'][$index]))
            return GRAPHIC_MATERIAL_URL . 'banners/' . $option['files'][$index];
        else
            return '';
    }

    static function getAdesiveURL($index = 0) {
        $option = self::getOption('adesive');

        if (isset($option['files'][$index]))
            return GRAPHIC_MATERIAL_URL . 'adesives/' . $option['files'][$index];
        else
            return '';
    }
    
    static function getBannersPath() {
        return GRAPHIC_MATERIAL_DIR . 'banners/';
    }
    
    static function getAdesivesPath() {
        return GRAPHIC_MATERIAL_DIR . 'adesives/';
    }


    static function getBannerFilename($index = 0) {
        $option = self::getOption('banners');
        $path = self::getBannersPath();
        if (isset($option['files'][$index]))
            return $path . $option['files'][$index];
        else
            return '';
    }

    static function getAdesiveFilename($index = 0) {
        $option = self::getOption('adesive');
        $path = self::getAdesivesPath();
        if (isset($option['files'][$index]))
            return $path . $option['files'][$index];
        else
            return '';
    }
    
    static function handleBannerUploads() {
        if (isset($_FILES['banner']) && is_array($_FILES['banner']['name'])) {
            $path = self::getBannersPath();
            if(!file_exists($path) && !is_dir($path))
                mkdir($path);
            
            foreach ($_FILES['banner']['tmp_name'] as $index => $tmp_name) {
                if (self::validateBanner($index)) {
                    self::deleteBunner($index);
                    
                    $fname = $_FILES['banner']['name'][$index];
                    
                    move_uploaded_file($tmp_name, $path . $fname);
                    
                    // adciona o arquivo no array do _POST para ser salvo posteriormente no update_option
                    $_POST['mobilize']['banners']['files'][$index] = $fname;
                }
            }
        }
    }
    
    static function handleAdesiveUploads() {
        if (isset($_FILES['adesive']) && is_array($_FILES['adesive']['name'])) {
            $path = self::getAdesivesPath();
            if(!file_exists($path) && !is_dir($path))
                mkdir($path);
            
            foreach ($_FILES['adesive']['tmp_name'] as $index => $tmp_name) {
                if (self::validateAdesive($index)) {
                    self::deleteAdesive($index);
                    
                    $fname = $_FILES['adesive']['name'][$index];
                    
                    move_uploaded_file($tmp_name, $path . $fname);
                    
                    // adciona o arquivo no array do _POST para ser salvo posteriormente no update_option
                    $_POST['mobilize']['adesive']['files'][$index] = $fname;
                }
            }
        }
    }

    static function validateBanner($index) {
        //TODO: inplementar esta função pelo mime type e o que mais for preciso
        $ok = $_FILES['banner']['error'][$index] == UPLOAD_ERR_OK;
        if(!$ok)
            self::addError('banners', "O upload do banner falhou.");
        
        return $ok;
            
    }

    static function validateAdesive($index) {
        //TODO: inplementar esta função pelo mime type e o que mais for preciso
        $ok = $_FILES['adesive']['error'][$index] == UPLOAD_ERR_OK;
        if(!$ok)
            self::addError('adesive', "O upload do adesive falhou.");
        
        return $ok;
            
    }
    
    
    static function printAdesiveNonce() {
        wp_nonce_field(self::ADESIVE_NONCE, self::ADESIVE_NONCE);
    }

    static function adesivar(){
        if ($_POST && isset($_POST[self::ADESIVE_NONCE]) && wp_verify_nonce($_POST[self::ADESIVE_NONCE], self::ADESIVE_NONCE) && isset($_FILES['photo']['error']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
            $option = self::getOption('adesive');
            
            // se um dia tiver mais de um adesivo é só enviar o $index pelo post
            $index = 0;
            $adesive_filename = self::getAdesiveFilename($index);
            if($adesive_filename){
                
                $adesivo = WideImage::load($adesive_filename);
                $uploaded = WideImage::loadFromUpload('photo');
                
                $resized = $adesivo->resize($uploaded->getWidth());
                $new = $uploaded->merge($resized, 'center','bottom-0');
                $new->output('jpg',100);
                die;
            }
            
        }
    }
    
    
    static function printEnvieNonce() {
        wp_nonce_field(self::ENVIE_NONCE, self::ENVIE_NONCE);
    }

    static function enviar(){
        if ($_POST && isset($_POST[self::ENVIE_NONCE]) && wp_verify_nonce($_POST[self::ENVIE_NONCE], self::ENVIE_NONCE)) {
            $option = self::getOption('envie');
            
            // TODO: ENVIAR EMAIL
            _pr($_POST);
        }
    }
}

function do_mobilize_action(){
    Mobilize::enviar();
    Mobilize::adesivar();
}

add_action('init', 'do_mobilize_action',100);
?>