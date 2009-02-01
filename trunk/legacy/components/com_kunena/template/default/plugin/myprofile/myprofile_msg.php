<?php
/**
* @version $Id$
* Kunena Component
* @package Kunena
* @Copyright (C) 2006 - 2007 Best Of Joomla All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.bestofjoomla.com
*
* Based on Joomlaboard Component
* @copyright (C) 2000 - 2004 TSMF / Jan de Graaff / All Rights Reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author TSMF & Jan de Graaff
**/
defined ('_VALID_MOS') or die('Direct Access to this location is not allowed.');
?>
<div class="<?php echo $boardclass; ?>_bt_cvr1">
<div class="<?php echo $boardclass; ?>_bt_cvr2">
<div class="<?php echo $boardclass; ?>_bt_cvr3">
<div class="<?php echo $boardclass; ?>_bt_cvr4">
<div class="<?php echo $boardclass; ?>_bt_cvr5">
<table class = "fb_blocktable " id = "fb_userprfmsg" border = "0" cellspacing = "0" cellpadding = "0" width="100%">
	<thead>
		<tr>
			<th colspan = "6">
				<div class = "fb_title_cover">
					<span class = "fb_title"><?php echo _KUNENA_USERPROFILE_MESSAGES; ?></span>
				</div>

				<img id = "BoxSwitch_fbuserprofile__<?php echo $boardclass ;?>fbuserprofile_tbody" class = "hideshow" src = "<?php echo KUNENA_URLIMAGESPATH . 'shrink.gif' ; ?>" alt = "" />
                        </th>
		</tr>
	</thead>

	<tbody id = "<?php echo $boardclass ;?>fbuserprofile_tbody">
		<tr class = "fb_sth">
			<th class = "th-1 <?php echo $boardclass ;?>sectiontableheader">&nbsp;
			</th>

			<th class = "th-2 <?php echo $boardclass ;?>sectiontableheader"><?php echo _KUNENA_USERPROFILE_TOPICS; ?>
			</th>

			<th class = "th-3 <?php echo $boardclass ;?>sectiontableheader"><?php echo _KUNENA_USERPROFILE_CATEGORIES; ?>
			</th>

			<th class = "th-4 <?php echo $boardclass ;?>sectiontableheader"><?php echo _KUNENA_USERPROFILE_HITS; ?>
			</th>

			<th class = "th-5 <?php echo $boardclass ;?>sectiontableheader"><?php echo _KUNENA_USERPROFILE_DATE; ?>
			</th>

			<th class = "th-6 <?php echo $boardclass ;?>sectiontableheader">&nbsp;
			</th>
		</tr>

		<?php
		// Emotions
		$topic_emoticons = array ();
		$topic_emoticons[0] = KUNENA_URLEMOTIONSPATH . 'default.gif';
		$topic_emoticons[1] = KUNENA_URLEMOTIONSPATH . 'exclam.gif';
		$topic_emoticons[2] = KUNENA_URLEMOTIONSPATH . 'question.gif';
		$topic_emoticons[3] = KUNENA_URLEMOTIONSPATH . 'arrow.gif';
		$topic_emoticons[4] = KUNENA_URLEMOTIONSPATH . 'love.gif';
		$topic_emoticons[5] = KUNENA_URLEMOTIONSPATH . 'grin.gif';
		$topic_emoticons[6] = KUNENA_URLEMOTIONSPATH . 'shock.gif';
		$topic_emoticons[7] = KUNENA_URLEMOTIONSPATH . 'smile.gif';

		//determine visitors allowable threads based on session
		//find group id
		$pageperlistlm      = 15;
		$limit              = intval(trim(mosGetParam($_REQUEST, 'limit', $pageperlistlm)));
		$limitstart         = intval(trim(mosGetParam($_REQUEST, 'limitstart', 0)));

		$query              = "select gid from #__users where id=$my->id";
		$database->setQuery($query);
		$dse_groupid = $database->loadObjectList();
			check_dberror("Unable to load usergroups.");

		if (count($dse_groupid))
		{
			$group_id = $dse_groupid[0]->gid;
		}
		else
		{
			$group_id = 0;
		}

		$query = "SELECT a.* , b.id as category, b.name as catname, c.hits AS 'threadhits' FROM #__fb_messages AS a, " . "\n #__fb_categories AS b, #__fb_messages AS c, #__fb_messages_text AS d" . "\n WHERE a.catid = b.id" . "\n AND a.thread = c.id"
			. "\n AND a.id = d.mesid" . "\n AND a.hold = 0 AND b.published = 1" . "\n AND a.userid=$my->id" . "\n AND (b.pub_access<='$group_id') ";
		$database->setQuery($query);

		$total = count($database->loadObjectList());
			check_dberror("Unable to load messages.");

		if ($total <= $limit)
		{
			$limitstart = 0;
		}

		$query
		= "SELECT a.* , b.id as category, b.name as catname, c.hits AS 'threadhits'" . "\n FROM #__fb_messages AS a, " . "\n #__fb_categories AS b, #__fb_messages AS c, #__fb_messages_text AS d" . "\n WHERE a.catid = b.id" . "\n AND a.thread = c.id"
			. "\n AND a.id = d.mesid" . "\n AND a.hold = 0 AND b.published = 1" . "\n AND a.userid=$my->id" . "\n AND (b.pub_access<='$group_id') " . "\n ORDER BY time DESC" . "\n LIMIT $limitstart, $limit";
		$database->setQuery($query);

		$items   = $database->loadObjectList();
			check_dberror("Unable to load messages.");

		require ("$mosConfig_absolute_path/includes/pageNavigation.php");
		$pageNav = new mosPageNav($total, $limitstart, $limit);

		if (count($items) > 0)
		{
			$tabclass = array
			(
				"sectiontableentry1",
				"sectiontableentry2"
			);

			$k = 0;

			foreach ($items AS $item)
			{
				$k = 1 - $k;

				if (!ISSET($item->created))
				{
					$item->created = "";
				}

				$fbURL    = sefRelToAbs("index.php?option=com_kunena&amp;func=view" . KUNENA_COMPONENT_ITEMID_SUFFIX . "&amp;catid=" . $item->catid . "&amp;id=" . $item->id . "#" . $item->id);

				$fbCatURL = sefRelToAbs("index.php?option=com_kunena" . KUNENA_COMPONENT_ITEMID_SUFFIX . "&amp;func=showcat&amp;catid=" . $item->catid);
		?>

			<tr class = "<?php echo ''.$boardclass.''. $tabclass[$k] . ''; ?>">
				<td class = "td-1"><?php echo "<img src=\"" . $topic_emoticons[$item->topic_emoticon] . "\" alt=\"emo\" />"; ?>
				</td>

				<td class = "td-2">
					<div class = "jr-topic-title">
						<a href = "<?php echo $fbURL; ?>"> <?php echo htmlspecialchars(stripslashes($item->subject)); ?> </a>
					</div>
				</td>

				<td class = "td-3">
					<div class = "jr-topic-cat">
						<a href = "<?php echo $fbCatURL; ?>"> <?php echo $item->catname; ?></a>
					</div>
				</td>

				<td class = "td-4"><?php echo $item->threadhits; ?>
				</td>

				<td class = "td-5">
					<div class = "jr-latest-subject-date">
<?php echo '' . date(_DATETIME, $item->time) . ''; ?>
					</div>
				</td>

				<td class = "td-6">
					<a href = "<?php echo $fbURL; ?>"> <?php echo $fbIcons['latestpost'] ? '<img src="' . KUNENA_URLICONSPATH . '' . $fbIcons['latestpost'] . '" border="0" alt="' . _SHOW_LAST . '" title="' . _SHOW_LAST . '" />'
																	  : '  <img src="' . KUNENA_URLEMOTIONSPATH . 'icon_newest_reply.gif" border="0"   alt="' . _SHOW_LAST . '" />'; ?> </a>
				</td>
			</tr>

		<?php
			}
		}
		else
		{
		?>

			<tr>
				<td colspan = "6" class = "<?php echo $boardclass ;?>profile-bottomnav">
					<br/>

					<b><?php echo _KUNENA_USERPROFILE_NOFORUMPOSTS; ?></b>

					<br/>

					<br/>
				</td>
			</tr>

		<?php
		}
		?>

		<tr>
			<td colspan = "6" class = "<?php echo $boardclass ;?>profile-bottomnav">
<?php echo _KUNENA_USRL_DISPLAY_NR; ?>

<?php
echo $pageNav->writeLimitBox("index.php?option=com_kunena&amp;func=myprofile&amp;do=showmsg" . KUNENA_COMPONENT_ITEMID_SUFFIX . "");
?>

<?php
// TODO: fxstein - Need to perform SEO cleanup
echo $pageNav->writePagesLinks("index.php?option=com_kunena&amp;func=myprofile&amp;do=showmsg" . KUNENA_COMPONENT_ITEMID_SUFFIX);
?>

<br/>
<?php echo $pageNav->writePagesCounter(); ?>
			</td>
		</tr>
	</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
