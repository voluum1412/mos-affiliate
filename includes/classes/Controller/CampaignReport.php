<?php declare(strict_types=1);

namespace MOS\Affiliate\Controller;

use MOS\Affiliate\Controller;
use MOS\Affiliate\Database;
use MOS\Affiliate\User;

class CampaignReport extends Controller {

  private $campaigns = [];


  public function __construct() {
    $db = new Database();
    $campaigns = $this->get_campaign_data();

    // Add empty partners column to campaigns
    foreach ( $campaigns as &$campaign ) {
      $campaign['partners'] = 0;
    }
    
    // Get a list of referrals
    $referrals = $db->get_referrals(['level', 'campaign']);

    // Count partners
    foreach ( $referrals as $referral ) {
      if ( strpos( 'partner', $referral['level']) !== false ) {
        $campaigns[$referral['campaign']]['partners']++;
      }
    }

    $this->campaigns = $campaigns;
  }


  protected function export_campaigns(): array {
    return $this->campaigns;
  }


  protected function export_headers(): array {
    if ( empty( $this->campaigns ) ) {
      return [];
    }
    $first_element = reset( $this->campaigns );
    $headers = empty( $first_element ) ? [] : array_keys( $first_element );
    return $headers;
  }


  private function get_campaign_data(): array {
    global $wpdb;

    // Get affid of current user
    $affid = User::current()->get_affid();

    // Check if affid is valid
    if ( empty( $affid ) ) {
      return [];
    }

    // Perform SQL lookup
    $table = $wpdb->prefix.'uap_campaigns';
    $query = "SELECT `name`, `visit_count` as clicks, `unique_visits_count` as unique_clicks, `referrals` FROM $table WHERE affiliate_id = $affid";
    $campaign_data = $wpdb->get_results( $query, \ARRAY_A );

    // Check if campaign data is valid
    if ( empty( $campaign_data ) ) {
      return [];
    }

    foreach( $campaign_data as $index => $campaign ) {
      $campaign_data[$campaign['name']] = $campaign;
      unset( $campaign_data[$index] );
    }

    return $campaign_data;
  }



}