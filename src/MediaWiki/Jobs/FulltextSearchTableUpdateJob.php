<?php

namespace SMW\MediaWiki\Jobs;

use Hooks;
use SMW\ApplicationFactory;
use SMW\SQLStore\QueryEngine\FulltextSearchTableFactory;
use Title;

/**
 * @license GNU GPL v2+
 * @since 2.5
 *
 * @author mwjames
 */
class FulltextSearchTableUpdateJob extends JobBase {

	/**
	 * @since 2.5
	 *
	 * @param Title $title
	 * @param array $params job parameters
	 */
	public function __construct( Title $title, $params = array() ) {
		parent::__construct( 'SMW\FulltextSearchTableUpdateJob', $title, $params );
		$this->removeDuplicates = true;
	}

	/**
	 * @see Job::run
	 *
	 * @since  2.5
	 */
	public function run() {

		$fulltextSearchTableFactory = new FulltextSearchTableFactory();

		$textChangeUpdater = $fulltextSearchTableFactory->newTextChangeUpdater(
			ApplicationFactory::getInstance()->getStore( '\SMW\SQLStore\SQLStore' )
		);

		$textChangeUpdater->pushUpdatesFromJobParameters(
			$this->params
		);

		Hooks::run( 'SMW::Job::AfterFulltextSearchTableUpdateComplete', array( $this ) );

		return true;
	}

}
