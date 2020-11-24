<?php

namespace MOS\Affiliate;

class Upgrader {

  const MIGRATIONS = [
    'mis_migration',
  ];

  
  public static function upgrade() {
    self::run_migrations();
  }


  private static function run_migrations(): void {
    foreach ( self::MIGRATIONS as $migration_name ) {
      $class_name = class_name( $migration_name, 'Migration' );
      if ( class_exists( $class_name ) ) {
        $migration = new $class_name();
        $migration->run();
      }
    }
  }


}