<?php

namespace ProcessWire;

use Mollie\Api\MollieApiClient;

/**
 * @author Bernhard Baumrock, 14.08.2020
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
class RockMollie extends WireData implements Module, ConfigurableModule
{

  /**
   * Get mollie api instance
   */
  public function api(): MollieApiClient
  {
    require_once("vendor/autoload.php");
    $api = new \Mollie\Api\MollieApiClient();
    $api->setApiKey($this->wire->config->mollieApiKey);
    return $api;
  }

  /**
   * Config inputfields
   * @param InputfieldWrapper $inputfields
   */
  public function getModuleConfigInputfields($inputfields)
  {
    $name = strtolower($this);
    $inputfields->add([
      'type' => 'markup',
      'label' => 'Documentation & Updates',
      'icon' => 'life-ring',
      'value' => "<p>Hey there, coding rockstars! 👋</p>
        <ul>
          <li><a class=uk-text-bold href=https://www.baumrock.com/modules/$name/docs>Read the docs</a> and level up your coding game! 🚀💻😎</li>
          <li><a class=uk-text-bold href=https://www.baumrock.com/rock-monthly>Sign up now for our monthly newsletter</a> and receive the latest updates and exclusive offers right to your inbox! 🚀💻📫</li>
          <li><a class=uk-text-bold href=https://github.com/baumrock/$name>Show some love by starring the project</a> and keep me motivated to build more awesome stuff for you! 🌟💻😊</li>
          <li><a class=uk-text-bold href=https://paypal.me/baumrockcom>Support my work with a donation</a>, and together, we'll keep rocking the coding world! 💖💻💰</li>
        </ul>",
    ]);

    $inputfields->add([
      'type' => 'markup',
      'label' => 'Setup',
      'icon' => 'code',
      'value' => 'Set $config->mollieApiKey = ... in your site config.php',
    ]);

    return $inputfields;
  }
}
