<?php
/**
 * Copyright (c) 2016 CardGate B.V.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @license     The MIT License (MIT) https://opensource.org/licenses/MIT
 * @author      CardGate B.V.
 * @copyright   CardGate B.V.
 * @link        https://www.cardgate.com
 */
namespace cardgate\api\resource {

	/**
	 * CardGate resource object.
	 */
	final class Methods extends Base {

		/**
		 * This method can be used to receive a {@link \cardgate\api\Method} instance.
		 * @param String $sId_ Method id to receive method instance for.
		 * @return \cardgate\api\Method
		 * @throws Exception
		 * @access public
		 * @api
		 */
		public function get( $sId_ ) {
			return new \cardgate\api\Method( $this->_oClient, $sId_, $sId_ );
		}

		/**
		 * This method can be used to retrieve a list of all available payment methods for a site.
		 * @param Integer $iSiteId_ The site to retrieve payment methods for.
		 * @return Array
		 * @throws Exception
		 * @access public
		 * @pai
		 */
		public function all( $iSiteId_ ) {
			if ( ! is_integer( $iSiteId_ ) ) {
				throw new Exception( 'Methods.SiteId.Invalid', 'invalid site id: ' . $iSiteId_ );
			}

			$sResource = "options/{$iSiteId_}/";

			$aResult = $this->_oClient->doRequest( $sResource, NULL, 'GET' );

			if ( empty( $aResult['options'] ) ) {
				throw new \cardgate\api\Exception( 'Method.Issuers.Invalid', 'invalid issuer data returned' );
			}

			$aMethods = [];
			foreach( $aResult['options'] as $aOption ) {
				if ( in_array( $aOption['id'], ( new \ReflectionClass( '\cardgate\api\Method' ) )->getConstants() ) ) {
					$aMethods[] = new \cardgate\api\Method( $this->_oClient, $aOption['id'], $aOption['name'] );
				}
			}

			return $aMethods;
		}

	}

}
