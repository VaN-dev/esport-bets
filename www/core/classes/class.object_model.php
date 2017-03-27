<?php
/*
=======================================================================
SCRIPT:			Class : Object Model
PROJECT:		Template
AUTHOR:			David Micheau
=======================================================================
Methods:		- [M001] HTML 		methods
				- [M002] String 	methods
				- [M003] Date 		methods
				- [M004] Number 	methods
				- [M005] File 		methods
				- [M006] Notice 	methods
				- [M007] XML 		methods	
				- [M008] Arrays		methods
				- [M009] Security	methods
=======================================================================
Description : 	Set a lot of functions used in others classes
=======================================================================
*/ 

class Object_Model
{
	public function __construct()
	{
	}

	##################################################################
	##																##
	## [M001]	HTML METHODS										##
	##																##
	##################################################################
	// Chargement des fichiers JS
	function load_scripts($scripts_to_load)
	{
		global $available_scripts;

		$scripts_to_load = array_unique($scripts_to_load);

		foreach($scripts_to_load as $script_key => $script_to_load)
		{
			if (is_array($available_scripts[$script_to_load]))
			{
				foreach ($available_scripts[$script_to_load] as $child_key => $child_to_load)
				{
					substr($available_scripts[$script_to_load][$child_key], 0, 4) == 'http' ? $root_path = '' : $root_path = ROOT_PATH;
					echo '<script type="text/javascript" src="' . $root_path . $available_scripts[$script_to_load][$child_key] . '"></script>' . "\n";
				}
			}
			else
			{
				substr($available_scripts[$script_to_load], 0, 4) == 'http' ? $root_path = '' : $root_path = ROOT_PATH;
				echo '<script type="text/javascript" src="' . $root_path . $available_scripts[$script_to_load] . '"></script>' . "\n";
			}
			
		}
	}

	// Chargement des fichiers CSS
	function load_styles($styles_to_load)
	{
		global $available_styles;
		
		$styles_to_load = array_unique($styles_to_load);
		
		foreach ($styles_to_load as $style_key => $style_to_load)
		{
			if (is_array($available_styles[$style_to_load]))
			{
				foreach($available_styles[$style_to_load] as $child_key => $child_to_load)
				{
					substr($available_styles[$style_to_load][$child_key], 0, 4) == 'http' ? $root_path = '' : $root_path = ROOT_PATH;
					echo '<link rel="stylesheet" type="text/css" href="' . $root_path . $available_styles[$style_to_load][$child_key] . '"/>' . "\n";
				}
			}
			else
			{
				substr($available_styles[$style_to_load], 0, 4) == 'http' ? $root_path = '' : $root_path = ROOT_PATH;
				echo '<link rel="stylesheet" type="text/css" href="' . $root_path . $available_styles[$style_to_load] . '"/>' . "\n";
			}
		}
	}

	// PRINT_R un tableau, entoure de balises <pre>
	public static function print_r_pre($array, $color = "black")
	{
		echo "<pre style=\"color:".$color.";\">";
		print_r($array);
		echo "</pre>";
	}

	// Traduit un statut INT en image
	function translate_status_into_img($status, $type, $size = 16)
	{
		$statut_str = '';
		$statut = Sql::get_row_from_query(sprintf("SELECT name, icon FROM " . TABLES__STATUTS . " WHERE statut = '%d' AND type = '%s'", $status, $type));
		if (!empty($statut))
		{
			$statut_str = '<img src="' . ROOT_PATH . 'core/images/icons/' . $statut['icon'] . '_' . $size . 'x' . $size . '.png" alt="' . $statut['name'] . '" title="' . $statut['name'] . '" style="vertical-align:middle;" />';
		}
		return $statut_str;
	}
	// Traduit un statut INT en texte
	function translate_status_into_text($status, $type)
	{
		$statut_str = '';
		$statut = Sql::get_row_from_query(sprintf("SELECT name FROM " . TABLES__STATUTS . " WHERE statut = '%d' AND type = '%s'", $status, $type));
		if(!empty($statut))
		{
			$statut_str = $statut['name'];
		}			
		return $statut_str;
	}
	// Traduit un statut INT en image et texte
	function translate_status_into_img_and_text($status, $type, $size = 16)
	{
		$statut_str = '';
		$statut = Sql::get_row_from_query(sprintf("SELECT name, icon FROM " . TABLES__STATUTS . " 
		WHERE statut = '%d' AND type = '%s'", $status, $type));
		if (!empty($statut))
		{
			$statut_str = '<img src="' . ROOT_PATH . 'core/images/icons/' . $statut['icon'] . '_' . $size . 'x' . $size . '.png" title="' . $statut['name'] . '" align="left" style="margin-right:5px;" /> '.$statut['name'];
		}
		return $statut_str;
	}

	// Stylise un string
	function html_stylize_string($string, $main_class = '', $special_class = '', $special_chars_positions = array())
	{
		if (!empty($special_chars_positions))
		{
			foreach($special_chars_positions as $special_chars_position)
			{
				$special_parts[] = substr($string, $special_chars_position, $special_chars_position + 1);
			}
		}

		print_r_pre($special_parts);

		$string = '<font class="' . $main_class . '">' . $string . '</font>';

		return $string;
	}

	// Construit une pagination
	function build_pagination($total_items, $max_items_per_page)
	{
		$nb_pages = ceil($total_items / $max_items_per_page);

		$pagination = '<div class="pagination-container"><span class="label">Page : </span><ul class="pagination">';
		for ($i = 1; $i <= $nb_pages; $i++)
		{
			$_GET['page'] == $i ? $class = ' class="active"' : $class = '';
			$pagination .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . '"' . $class . '>' . $i . '</a></li>';
		}
		$pagination .= '</ul></div>';

		return $pagination;
	}

	function build_evolved_pagination($current_page, $nb_pages, $link='?page=%d', $around=3, $firstlast=1)
	{
		$pagination = '<p class="pagination-evoluee">';
		$link = preg_replace('`%([^d])`', '%%$1', $link);
		if (!preg_match('`(?<!%)%d`', $link))
		{
			$link .= '%d';
		}
		if ($nb_pages > 1)
		{
			// Lien précédent
			if ($current_page > 1)
			{
				$pagination .= '<a class="prevnext" href="'.sprintf($link, $current_page-1).'" title="Page précédente">&laquo; Précédent</a>';
			}
			else
			{
				$pagination .= '<span class="prevnext disabled">&laquo; Précédent</span>';
			}

			// Lien(s) début
			for ( $i=1 ; $i<=$firstlast ; $i++ )
			{
				$pagination .= ' ';
				$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'">'.$i.'</a>';
			}

			// ... après pages début ?
			if (($current_page-$around) > $firstlast+1)
			{
				$pagination .= ' &hellip;';
			}

			// On boucle autour de la page courante
			$start = ($current_page-$around)>$firstlast ? $current_page-$around : $firstlast+1;
			$end = ($current_page+$around)<=($nb_pages-$firstlast) ? $current_page+$around : $nb_pages-$firstlast;
			for ($i=$start ; $i<=$end ; $i++)
			{
				$pagination .= ' ';
				if ($i==$current_page)
				{
					$pagination .= '<span class="current">'.$i.'</span>';
				}
				else
				{
					$pagination .= '<a href="'.sprintf($link, $i).'">'.$i.'</a>';
				}
			}

			// ... avant page nb_pages ?
			if (($current_page+$around) < $nb_pages-$firstlast)
			{
				$pagination .= ' &hellip;';
			}

			// Lien(s) fin
			$start = $nb_pages-$firstlast+1;
			if ($start <= $firstlast)
			{
				$start = $firstlast+1;
			}
			for ($i=$start ; $i<=$nb_pages ; $i++)
			{
				$pagination .= ' ';
				$pagination .= ($current_page==$i) ? '<span class="current">'.$i.'</span>' : '<a href="'.sprintf($link, $i).'">'.$i.'</a>';
			}

			// Lien suivant
			if ($current_page < $nb_pages)
			{
				$pagination .= ' <a class="prevnext" href="'.sprintf($link, ($current_page+1)).'" title="Page suivante">Suivant &raquo;</a>';
			}
			else
			{
				$pagination .= ' <span class="prevnext disabled">Suivant &raquo;</span>';
			}
	
			$pagination .= '</p>';
		}
		return $pagination;
	}

	##################################################################
	##																##
	## [M002]	STRING METHODS										##
	##																##
	##################################################################
	// Genere un string aleatoire, de longueur length
	function string_random_string($length)
	{
		$string = "";
		$chaine = "AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz0123456789";
		srand((double)microtime()*1000000);
		for ($i=0; $i<$length; $i++)
		{
			$string .= $chaine[rand()%strlen($chaine)];
		}
		return $string;
	}

	// Supprime les accents
	function string_strip_accents($str)
	{
		$remove = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ';
		$replace = 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY';
		$str = strtr(utf8_decode($str),utf8_decode($remove), utf8_decode($replace));
		return utf8_encode($str);
	}

	// Retourne une valeur formatee pour un champ rewrite
	function string_rewrite_value($value)
	{
		$value = $this->string_strip_accents($value);
		$value = strtolower($value);
		$value = str_replace('/', '-', $value);
		$value = preg_replace("#([^.a-zA-Z0-9-_\40])#", "", $value);
		$value = trim($value);
		$value = preg_replace("([\40])", "-", $value);
		$value = preg_replace("(-{2,})", "-", $value);

		return $value;
	}

	// Coupe un string, de la position 0 a length
	function string_cut_string($str, $length)
	{
		$str = strip_tags($str);
		/*
		if(strpos($str, " ") === false) {
			$str = wordwrap($str, 25, "<br />\n", true);
		}
		*/
		if (strlen($str) > $length)
		{
			$str = substr($str, 0, $length);
			$last_space = strrpos($str, " ");
			$str = substr($str, 0, $last_space)."...";
		} 

		return $str;
	}

	// VERSION EVOLUEE DE STRING_CUT_STRING()
	/**
	 * Truncates text.
	 *
	 * Cuts a string to the length of $length and replaces the last characters
	 * with the ending if the text is longer than length.
	 *
	 * @param string  $str 				String to truncate.
	 * @param integer $length 			Length of returned string, including ellipsis.
	 * @param string  $ending 			Ending to be appended to the trimmed string.
	 * @param boolean $exact 			If false, $text will not be cut mid-word
	 * @param boolean $considerHtml 	If true, HTML tags would be handled correctly
	 * @return string 					Trimmed string.
	 */

	function string_truncate_string($str, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
	{
		if ($considerHtml)
		{
			// if the plain text is shorter than the maximum length, return the whole text
			if (strlen(preg_replace('/<.*?>/', '', $str)) <= $length)
			{
				return $str;
			}
			// splits all html-tags to scanable lines
			preg_match_all('/(<.+?>)?([^<>]*)/s', $str, $lines, PREG_SET_ORDER);
			$total_length = strlen($ending);
			$open_tags = array();
			$truncate = '';
			foreach ($lines as $line_matchings)
			{
				// if there is any html-tag in this line, handle it and add it (uncounted) to the output
				if (!empty($line_matchings[1]))
				{
					// if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
					if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1]))
					{
						// do nothing
					// if tag is a closing tag (f.e. </b>)
					}
					else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings))
					{
						// delete tag from $open_tags list
						$pos = array_search($tag_matchings[1], $open_tags);
						if ($pos !== false) {
							unset($open_tags[$pos]);
						}
					// if tag is an opening tag (f.e. <b>)
					}
					else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings))
					{
						// add tag to the beginning of $open_tags list
						array_unshift($open_tags, strtolower($tag_matchings[1]));
					}
					// add html-tag to $truncate'd text
					$truncate .= $line_matchings[1];
				}
				// calculate the length of the plain text part of the line; handle entities as one character
				$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
				if ($total_length+$content_length> $length)
				{
					// the number of characters which are left
					$left = $length - $total_length;
					$entities_length = 0;
					// search for html entities
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE))
					{
						// calculate the real length of all entities in the legal range
						foreach ($entities[0] as $entity)
						{
							if ($entity[1]+1-$entities_length <= $left)
							{
								$left--;
								$entities_length += strlen($entity[0]);
							}
							else
							{
								// no more characters left
								break;
							}
						}
					}
					$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
					// maximum lenght is reached, so get off the loop
					break;
				}
				else
				{
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
				// if the maximum length is reached, get off the loop
				if ($total_length>= $length)
				{
					break;
				}
			}
		}
		else
		{
			if (strlen(strip_tags($str)) <= $length)
			{
				return strip_tags($str);
			}
			else
			{
				$truncate = substr(strip_tags($str), 0, $length - strlen($ending));
			}
		}
		// if the words shouldn't be cut in the middle...
		if (!$exact)
		{
			// ...search the last occurance of a space...
			$spacepos = strrpos($truncate, ' ');
			if (isset($spacepos))
			{
				// ...and cut the text in this position
				$truncate = substr($truncate, 0, $spacepos);
			}
		}
		// add the defined ending to the text
		$truncate .= $ending;
		if ($considerHtml)
		{
			// close all unclosed html-tags
			foreach ($open_tags as $tag)
			{
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}

	// Parse un texte et transforme les URLs en URLs cliquables
	function parse_urls($str, $maxurl_len = 35, $target = "_blank")
	{
		if(preg_match_all('/((ht|f)tps?:\/\/([\w\.]+\.)?[\w-]+(\.[a-zA-Z]{2,4})?[^\s\r\n\(\)"\'<>\,\!]+)/si', $str, $urls))
		{
			$offset1 = ceil(0.65 * $maxurl_len) - 2;
			$offset2 = ceil(0.30 * $maxurl_len) - 1;

			foreach(array_unique($urls[1]) AS $url)
			{
				if ($maxurl_len AND strlen($url) > $maxurl_len)
				{
					$urltext = substr($url, 0, $offset1) . '...' . substr($url, -$offset2);
				}
				else
				{
					$urltext = $url;
				}

				$str = str_replace($url, '<a href="'. $url .'" target="'. $target .'" title="'. $url .'">'. $urltext .'</a>', $str);
			}
		}
		return $str;
	}

	##################################################################
	##																##
	## [M003]	DATE METHODS										##
	##																##
	##################################################################
	// Convertit une date SQL en date FR
	function date_sql2fr($date)
	{
		if (!empty($date))
		{
			$date = explode("-", $date);
			$date_fr = $date[2]."/".$date[1]."/".$date[0];
		}
		else
		{
			$date_fr = "";
		}
		return $date_fr;
	}

	// Convertit une date FR en date SQL
	function date_fr2sql($date)
	{
		if (!empty($date))
		{
			$date = explode("/", $date);
			$date_sql = $date[2]."-".$date[1]."-".$date[0];
		}
		else
		{
			$date_sql = "";
		}
		return $date_sql;
	}

	// Convertit une date SQL en date EN
	function date_sql2en($date)
	{
		if (!empty($date))
		{
			$date = explode("-", $date);
			$date_fr = $date[1]."/".$date[2]."/".$date[0];
		}
		else
		{
			$date_fr = "";
		}
		return $date_fr;
	}

	// Convertit une date EN en date SQL
	function date_en2sql($date)
	{
		if (!empty($date))
		{
			$date = explode("/", $date);
			$date_sql = $date[2]."-".$date[0]."-".$date[1];
		}
		else
		{
			$date_sql = "";
		}
		return $date_sql;
	}
	
	// Convertit une date SQL en timestamp
	function date_sql2timestamp($date)
	{
		$date = explode("-", $date);

		$timestamp = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
		return $timestamp;
	}

	// Convertit une datetime SQL en date FR
	function datetime_sql2fr($datetime)
	{
		if (!empty($datetime))
		{
			$datetime = explode(" ", $datetime);
			$date_fr = self::date_sql2fr($datetime[0]);
			$time_fr = $datetime[1];
			$datetime_fr = array("date" => $date_fr, "time" => $time_fr);
		}
		else
		{
			$datetime_fr = "";
		}
		return $datetime_fr;
	}

	// Convertit une datetime FR en date SQL
	function datetime_fr2sql($datetime)
	{
		if (!empty($datetime))
		{
			$datetime = explode(" ", $datetime);
			$datetime["date"] = explode("/", $datetime[0]);
			$date_sql = self::date_fr2sql($datetime[0]);
			$time_sql = $datetime[1];
			$datetime_sql = $date_sql." ".$time_sql;
		}
		else
		{
			$datetime_sql = "";
		}
		return $datetime_sql;
	}

	// Convertit une datetime SQL en date EN
	function datetime_sql2en($datetime)
	{
		if (!empty($datetime))
		{
			$datetime = explode(" ", $datetime);
			$date_en = self::date_sql2en($datetime[0]);
			$time_en = $datetime[1];
			$datetime_en = array("date" => $date_en, "time" => $time_en);
		}
		else
		{
			$datetime_en = "";
		}
		return $datetime_en;
	}

	// Convertit une datetime EN en date SQL
	function datetime_en2sql($datetime)
	{
		if (!empty($datetime))
		{
			$datetime = explode(" ", $datetime);
			$datetime["date"] = explode("/", $datetime[0]);
			$date_sql = self::date_en2sql($datetime[0]);
			$time_sql = $datetime[1];
			$datetime_sql = $date_sql." ".$time_sql;
		}
		else
		{
			$datetime_sql = "";
		}
		return $datetime_sql;
	}

	// Convertit une date SQL en timestamp
	function datetime_sql2timestamp($datetime)
	{
		$datetime = explode(" ", $datetime);
		$datetime["date"] = explode("-", $datetime[0]);
		$datetime["time"] = explode(":", $datetime[1]);
		
		$timestamp = mktime($datetime["time"][0], $datetime["time"][1], $datetime["time"][2], $datetime["date"][1], $datetime["date"][2], $datetime["date"][0]);
		return $timestamp;
	}

	// Créé un tableau composé de day, month, year à partir d'un datetime SQL
	function datetime_sql2array($datetime)
	{
		$datetime = explode(" ", $datetime);
		isset($datetime[0]) ? $date = explode("-", $datetime[0]) : $date = array("0000", "00", "00");
		isset($datetime[1]) ? $time = explode(":", $datetime[1]) : $time = array("00", "00", "00");
		$array = array("year" => $date[0], "month" => $date[1], "day" => $date[2], "hours" => $time[0], "minutes" => $time[1], "seconds" => $time[2]);
		return $array;
	}

	// Convertit un timestamp en durée
	function datetime_timestamp2duration($timestamp)
	{
		$time_durations = array("a" => 31557600,"mo" => 2629800, "j" => 86400, "h" => 3600, "min" => 60, "s" => 1);
		
		$result = "";

		foreach ($time_durations as $uniteTemps => $nombreSecondesDansUnite)
		{
			$$uniteTemps = floor($timestamp/$nombreSecondesDansUnite);
			$timestamp = $timestamp%$nombreSecondesDansUnite;
			if ($$uniteTemps > 0 || !empty($result))
			{
				$result .= $$uniteTemps."$uniteTemps ";
			}
		}
		return $result;
	}

	##################################################################
	##																##
	## [M004]	NUMBER METHODS										##
	##																##
	##################################################################
	// Calcule un prix ttc a partir d'un prix ht
	function get_taxed_price($value, $tax)
	{
		global $config;
		$taxed = $value + $value * $tax / 100;
		return $taxed;
	}

	// Calcule un prix ht a partir d'un prix ttc
	function get_untaxed_price($value, $tax)
	{
		global $config;
		$untaxed = $value / (1 + ($tax / 100));
		return $untaxed;
	}

	// Calcule le montant de la tva sur un prix ttc
	function get_tva($value, $tax)
	{
		global $config;
		$tva = $value * $tax / 100;
		return $tva;
	}

	// Formate un float en ecriture scientifique en float lisible
	function format_scientifique_number($num, $float_sep = ",", $thouthand_sep = " ")
	{
		$float = null;
		if((int)$num != (float)$num ) $float = 2;
		return number_format($num,$float,$floatsep,$thouthandsep);
	}

	// Calcule le pourcentage
	function compute_percent($value1, $value2, $round = 0, $before_str = '[ ', $after_str = ' ]')
	{
		if($value2 != 0)
		{
			$percent = $value1 * 100 / $value2;
			return $before_str . round($percent) . '%' .$after_str;
		}
	}

	##################################################################
	##																##
	## [M005]	FILE METHODS										##
	##																##
	##################################################################
	// Retourne le nom de fichier
	function file_get_filename($filename)
	{
		return substr($filename, 0, strrpos($filename, "."));
	}

	// Retourne l'extension d'un nom de fichier
	function file_get_extension($filename)
	{
		return substr($filename, strrpos($filename, ".") + 1, strlen($filename));
	}

	// Créé un thumbnail d'un fichier image
	function create_thumbnail($photo, $extension, $pathLarges, $pathThumbs, $thumbWidth)
	{
		$photoSizes = getimagesize($pathLarges.$photo);
		$thumbHeight = round($photoSizes[1] * $thumbWidth / $photoSizes[0]);

		if (strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg')
		{
			$src_img = imagecreatefromjpeg($pathLarges.$photo);
		}
		elseif (strtolower($extension) == 'png')
		{
			$src_img = imagecreatefrompng($pathLarges.$photo);
		}
		elseif (strtolower($extension) == 'gif')
		{
			$src_img = imagecreatefromgif($pathLarges.$photo);
		}

		$dst_img = imagecreatetruecolor($thumbWidth,$thumbHeight);
		imagealphablending($dst_img, false);
		imagesavealpha($dst_img, true);
		imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumbWidth, $thumbHeight, imagesx($src_img), imagesy($src_img));

		if (strtolower($extension) == 'jpg' || strtolower($extension) == 'jpeg')
		{
			imagejpeg($dst_img, $pathThumbs.$photo, 90);
		}
		elseif (strtolower($extension) == 'png')
		{
			imagepng($dst_img, $pathThumbs.$photo, 0);
		}
		elseif (strtolower($extension) == 'gif')
		{
			imagegif($dst_img, $pathThumbs.$photo);
		}
		return true;
	}

	// Re formate un tableau $_FILES de plusieurs entrées
	function fix_files_array($entry)
	{
		if (isset($entry['name']) && is_array($entry['name']))
		{
			$files = array();
			foreach ($entry['name'] as $k => $name)
			{
				if (!empty($name))
				{
					$files[$k] = array(
						'name' => $name,
						'tmp_name' => $entry['tmp_name'][$k],
						'size' => $entry['size'][$k],
						'type' => $entry['type'][$k],
						'error' => $entry['error'][$k]
					);
				}
			}
			return $files;
		}
	}

	// Vérifie si un dossier existe. Si non, creation du dossier.
	function create_dir($path, $mode = 0777, $recursive = true)
	{
		if (is_dir($path))
		{
			return true;
		} 
		else
		{
			if(mkdir($path, $mode, $recursive))
			{
				return true;
			} 
			else
			{
				return false;
			}
		}
	}

	// transforme une arborescence en tableau PHP
	function directory_to_array($directory, $recursive = true, $include_directories_in_list = true, $include_files_in_list = false)
	{
		$array_items = array();
		if ($handle = opendir($directory))
		{
			while (false !== ($file = readdir($handle)))
			{
				if ($file != "." && $file != "..")
				{
					if (is_dir($directory. "/" . $file))
					{
						if($recursive)
						{
							$array_items = array_merge($array_items, $this->directory_to_array($directory. "/" . $file, $recursive));
						}
						if ($include_directories_in_list)
						{
							$file = $directory . "/" . $file;
							$array_items[] = preg_replace("/\/\//si", "/", $file);
						}
					} 
					else
					{
						if ($include_files_in_list)
						{
							$file = $directory . "/" . $file;
							$array_items[] = preg_replace("/\/\//si", "/", $file);
						}
					}
				}
			}
			closedir($handle);
		}
		return $array_items;
	}

	##################################################################
	##																##
	## [M006]	NOTICE METHODS										##
	##																##
	##################################################################
	// Affiche les notices
	function display_notices()
	{
		if(isset($_SESSION['notices']) && !empty($_SESSION['notices']))
		{
			foreach($_SESSION['notices'] as $notice)
			{
				echo $notice;
			}
		}
	}

	// Vide les notices
	function clear_notices()
	{
		unset($_SESSION['notices']);
	}

	##################################################################
	##																##
	## [M007]	XML METHODS											##
	##																##
	##################################################################
	// Construit un fichier de flux RSS
	function build_xml_actualites($file_path)
	{
		global $config;

		// On récupère les actualités
		mysql_query('SET lc_time_names = "en_US"');
		$db_items = Sql::get_objects_from_query("SELECT *, DATE_FORMAT(`date`, '%a, %d %b %Y') AS rfc_date FROM " . TABLES__ACTUALITES . " ORDER BY date DESC", 'Actualite');

		// On créé le fichier XML
		$this->create_dir(ROOT_PATH . 'xml');
		$fp = fopen ($file_path, "w+");
		fputs($fp, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" . "\n");
		fputs($fp, "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">" . "\n");
		fputs($fp, "\t" . "<channel>" . "\n");
		fputs($fp, "\t\t" . "<title>" . stripslashes($config->site_title) . "</title>" . "\n");
		fputs($fp, "\t\t" . "<link>" . $config->site_url . "</link>" . "\n");
		fputs($fp, "\t\t" . "<description>" . stripslashes($config->site_description) . "</description>" . "\n");
		fputs($fp, "\t\t" . "<atom:link rel=\"self\" type=\"application/rss+xml\" href=\"" . $config->site_url . "/xml/actualites.xml\" />" . "\n");

		foreach($db_items as $db_item)
		{
			fputs($fp, "\t\t" . '<item>' . "\n");
			fputs($fp, "\t\t\t" . '<title>' . stripslashes($db_item->titre) . '</title>' . "\n");
			fputs($fp, "\t\t\t" . '<link>' . $config->site_url . '/actualite.php?id=' . $db_item->id . '</link>' . "\n");
			fputs($fp, "\t\t\t" . '<guid isPermaLink="false">' . $config->site_url . '/actualite.php?id=' . $db_item->id . '</guid>' . "\n");
			fputs($fp, "\t\t\t" . '<description>' .htmlentities('<img src="' . $config->site_url . '/uploads/medias/actualites/images/thumbs/' . $db_item->image() . '" alt="" height="80" align="left" />' . stripslashes($db_item->contenu)) . '</description>' . "\n");
			fputs($fp, "\t\t\t" . '<pubDate>' . $db_item->rfc_date . ' 00:00:00 GMT</pubDate>' . "\n");
			fputs($fp, "\t\t" . '</item>' . "\n");
		}

		fputs($fp, "\t" . "</channel>" . "\n");
		fputs($fp, "</rss>" . "\n");
		fclose($fp);
	}

	##################################################################
	##																##
	## [M008]	ARRAYS METHODS										##
	##																##
	##################################################################
	// Trie un tableau multi-dimensionnel en fonction d'une colonne
	public function array_sort_by_column($array, $column)
	{
		if(!empty($array))
		{
			foreach($array as $key => $row)
			{
				if(isset($row[$column]))
				{
					$sort_values[$key] = $row[$column];
				}
			}

			asort($sort_values);
			reset($sort_values);

			while (list ($arr_key, $arr_val) = each ($sort_values))
			{
				$sorted_arr[] = $array[$arr_key];
			}
			unset($array);
			return $sorted_arr;
		}
	}

	##################################################################
	##																##
	## [M009]	SECURITY METHODS									##
	##																##
	##################################################################
	// Décode un bitmask décimal
	function get_permissions_array_from_bitmask($mask = 0)
	{
		if(!is_numeric($mask))
		{
			return array();
		}
		$permissions = array();
		while ($mask > 0)
		{
			for($i = 0, $n = 0; $i <= $mask; $i = 1 * pow(2, $n), $n++)
			{
				$end = $i;
			}
			$permissions[] = $end;
			$mask = $mask - $end;
		}
		sort($permissions);
		return $permissions;
	}

	function _s($var)
	{
		$blacklisted_tags = array('applet', 'base', 'basefont', 'embed', 'frame', 'frameset', 'iframe', 'isindex', 'meta', 'noframes', 'noscript', 'object', 'param', 'script', 'style', 'title');
		foreach($blacklisted_tags as $blacklisted_tag)
		{
			$var = preg_replace('@<' . $blacklisted_tag . '[^>]*?>.*?</' . $blacklisted_tag . '>@si', '', $var);
			$var = preg_replace('@<' . $blacklisted_tag . '[^>]+?/[ ]*>@si', '', $var);
		}
		$var = stripslashes($var);
		return $var;
	}
}
?>