<?php

namespace MOS\Affiliate;

class MosAffiliatePlugin {

  private $views = [
    // shortcode name => view name
    'mos_uap_campaign_form' => 'campaign_form',
    'mos_uap_campaign_list' => 'campaign_list',
    'mos_uap_campaign_report' => 'campaign_report',
  ];


  public function __construct() {
    require( PLUGIN_DIR . "/includes/core/config.php" );
    require( PLUGIN_DIR . "/includes/core/functions.php" );
  }


  public function init() {
    $this->load_classes();
    $this->load_scripts();
    $this->register_views();
  }


  private function load_classes() {
    require( PLUGIN_DIR . "/includes/classes/MosAffiliateDb.php" );
    require( PLUGIN_DIR . "/includes/classes/MosAffiliateController.php" );
  }


  private function load_scripts() {
    \add_action( 'wp_enqueue_scripts', function() {
      \wp_enqueue_script( 'mosAffiliate', $this->url.'dist/js/mosAffiliate.js', ['jquery'], '1.0.0', true );
    });
  }


  private function register_views() {
    foreach ( $this->views as $shortcode => $view ) {
      add_shortcode( $shortcode, function() use ($view) {
        return get_view( $view );
      });
    }
  }

}