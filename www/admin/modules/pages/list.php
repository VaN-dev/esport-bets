<?php
function build_table_row($pages, $level = 0)
{
	foreach ($pages as $page)
	{
		$indent = '';
		for ($i = 0; $i < $level; $i++)
		{
			$indent .= '&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		$indent .= '<img src="' . ADMIN_PATH . 'images/pages.indent-1.gif" alt="" class="page-indent" align="absmiddle" />';
		
		echo '<tr>';
		echo '	<td>' . $page->id . '</td>';
		echo '	<td>' . $indent . $page->_s($page->titre) . '</td>';
		echo '  <td>';
		echo $page->menu_affichage == 1 ? 'oui' : 'non';
		echo '</td>';
		echo '  <td><a href="index.php?action=switch_statut&id=' . $page->id . '">' . $page->translate_status_into_img($page->statut, 'pages') . '</a></td>';
		echo '  <td class="actions">';
		echo '		<a href="index.php?action=update&item_id=' . $page->id . '" class="link-action"><img src="' . ROOT_PATH . 'core/images/icons/edit_16x16.png" alt="" class="icon" />Modifier</a> ';
		echo '		<a data-id="' . $page->id . '" class="link-action prompt-deletion"><img src="' . ROOT_PATH . 'core/images/icons/delete_16x16.png" alt="" class="icon" style="margin-left:20px;" />Supprimer</a>';
		echo '  </td>';
		echo '</tr>';
		
		if (isset($page->children) && !empty($page->children))
		{
			build_table_row($page->children, $level + 1);
		}
	}
}
?>

<h2>Liste des pages</h2>

<a href="index.php?action=insert" class="bouton float-right">Ajouter une page</a>

<br class="clear-both" /><br />

<?php
$results = $handler_pages->get_list_for_menu_admin();

if (!empty($results))
{
	?>
	<table class="datatable display">
		<thead>
			<tr>
			<th width="40">#</th>
			<th>Titre</th>
			<th width="70">Menu</th>
			<th width="70">Statut</th>
			<th width="200">Actions</th>
		</tr>
		</thead>
		<tbody>
		<?php
		// $site->print_r_pre($results);
		build_table_row($results, 0);
		?>
		</tbody>
	</table>
	<?php
}
else
{
	echo '<p>Aucune page à afficher.</p>';
}
?>
 