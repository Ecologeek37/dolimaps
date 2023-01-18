<?php
/* Copyright (C) 2023 Vincent Coulon
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file    dolimaps/class/actions_dolimaps.class.php
 * \ingroup dolimaps
 * \brief   Example hook overload.
 *
 * Put detailed description here.
 */

/**
 * Class ActionsDoliMaps
 */
class ActionsDoliMaps
{
	/**
	 * @var DoliDB Database handler.
	 */
	public $db;

	/**
	 * @var string Error code (or message)
	 */
	public $error = '';

	/**
	 * @var array Errors
	 */
	public $errors = array();


	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var int		Priority of hook (50 is used if value is not defined)
	 */
	public $priority;


	/**
	 * Constructor
	 *
	 *  @param		DoliDB		$db      Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array           $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action         Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function printAddress($parameters, &$object, &$action) {

		global $conf;

		if (in_array($parameters['currentcontext'], array('main'))) {
			$address = $object; //store adress
			if ($conf->global->DOLIMAPS_MAPS_TYPE == 1) {
				$link = 'https://www.google.com/maps/place/' . dol_escape_htmltag(str_replace(" ", "+", $address));
				//$title = 'Ouvrir dans Google Maps<br><div style="width: 100%"><iframe width="100%" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=' . dol_escape_htmltag(str_replace(" ", "+", $address)) . '&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe></div>'; //TODO
				$title = 'Ouvrir dans Google Maps';
				$object = '<a href="' . $link . '" target="_blank" title="' . dol_escape_htmltag($title) . '" class="classfortooltip">' . $address . '</a>';
			}
			if ($conf->global->DOLIMAPS_MAPS_TYPE == 2) {
				$link = 'https://www.openstreetmap.org/search?query=' . dol_escape_htmltag($address);
				$title = 'Ouvrir dans Open Street Maps';
				$object = '<a href="' . $link . '" target="_blank" title="' . dol_escape_htmltag($title) . '" class="classfortooltip">' . $address . '</a>';
			}
		}
	}
}
