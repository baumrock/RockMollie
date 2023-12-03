<?php

namespace ProcessWire;

/**
 * @author Bernhard Baumrock, 14.08.2020
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
class RockMollie extends WireData implements Module, ConfigurableModule
{

  public static function getModuleInfo()
  {
    return [
      'title' => 'RockMollie',
      'version' => json_decode(file_get_contents(__DIR__ . "/package.json"))->version,
      'summary' => 'Integrate Mollie Payments into your ProcessWire website',
      'autoload' => false,
      'singular' => true,
      'icon' => 'money',
      'requires' => [],
      'installs' => [],
    ];
  }

  public function init()
  {
  }

  /**
   * Get mollie api instance
   */
  public function api()
  {
    require_once("vendor/autoload.php");
    $api = new \Mollie\Api\MollieApiClient();

    if (getenv('DDEV_HOSTNAME') || !$this->live) {
      // use the test api key
      $api->setApiKey($this->testapikey);
    } else {
      // we are live
      if (!$this->liveapikey) throw new WireException("No Live API kay found for RockMollie");
      $api->setApiKey($this->liveapikey);
    }
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
      'type' => 'toggle',
      'label' => 'API Mode',
      'name' => 'live',
      'value' => $this->live ?: false,
      'notes' => 'Selected item is gray',
      'labelType' => InputfieldToggle::labelTypeCustom,
      'yesLabel' => 'LIVE',
      'noLabel' => 'TEST',
      'columnWidth' => 33,
    ]);

    $inputfields->add([
      'type' => 'text',
      'label' => 'TEST API Key',
      'name' => 'testapikey',
      'value' => $this->testapikey,
      'prependMarkup' => '<style>#Inputfield_testapikey:not(:focus) {
        color: transparent;
        text-shadow: 0 0 10px rgba(0,0,0,0.5);
      }</style>',
      'columnWidth' => 33,
    ]);

    $inputfields->add([
      'type' => 'text',
      'label' => 'LIVE API Key',
      'name' => 'liveapikey',
      'value' => $this->liveapikey,
      'prependMarkup' => '<style>#Inputfield_liveapikey:not(:focus) {
        color: transparent;
        text-shadow: 0 0 10px rgba(0,0,0,0.5);
      }</style>',
      'columnWidth' => 33,
    ]);

    return $inputfields;
  }
}
