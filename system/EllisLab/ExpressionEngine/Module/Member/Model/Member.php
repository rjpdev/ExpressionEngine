<?php
namespace EllisLab\ExpressionEngine\Module\Member\Model;

use EllisLab\ExpressionEngine\Model\Model;

/**
 * Member
 *
 * A member of the website.  Represents the user functionality
 * provided by the Member module.  This is a single user of
 * the website.
 */
class Member extends Model {
	protected static $_primary_key = 'member_id';
	protected static $_gateway_names = array('MemberGateway','ResetPasswordGateway');
	protected static $_key_map = array(
		'member_id' => 'MemberGateway',
		'group_id' => 'MemberGateway',
		'reset_id' => 'ResetPasswordGateway'
	);

	// Properties
	protected $member_id;
	protected $group_id;
	protected $username;
	protected $screen_name;
	protected $password;
	protected $salt;
	protected $unique_id;
	protected $crypt_key;
	protected $authcode;
	protected $email;
	protected $url;
	protected $location;
	protected $occupation;
	protected $interests;
	protected $bday_d;
	protected $bday_m;
	protected $bday_y;
	protected $aol_im;
	protected $yahoo_im;
	protected $msn_im;
	protected $icq;
	protected $bio;
	protected $signature;
	protected $avatar_filename;
	protected $avatar_width;
	protected $avatar_height;
	protected $photo_filename;
	protected $photo_width;
	protected $photo_height;
	protected $sig_img_filename;
	protected $sig_img_width;
	protected $sig_img_height;
	protected $ignore_list;
	protected $private_messages;
	protected $accept_messages;
	protected $last_view_bulletins;
	protected $last_bulletin_date;
	protected $ip_address;
	protected $join_date;
	protected $last_visit;
	protected $last_activity;
	protected $total_entries;
	protected $total_comments;
	protected $total_forum_topics;
	protected $total_forum_posts;
	protected $last_entry_date;
	protected $last_comment_date;
	protected $last_forum_post_date;
	protected $last_email_date;
	protected $in_authorlist;
	protected $accept_admin_email;
	protected $accept_user_email;
	protected $notify_by_default;
	protected $notify_of_pm;
	protected $display_avatars;
	protected $display_signatures;
	protected $parse_smileys;
	protected $smart_notifications;
	protected $language;
	protected $timezone;
	protected $time_format;
	protected $cp_theme;
	protected $profile_theme;
	protected $forum_theme;
	protected $tracker;
	protected $template_size;
	protected $notepad;
	protected $notepad_size;
	protected $quick_links;
	protected $quick_tabs;
	protected $show_sidebar;
	protected $pmember_id;

	// On ResetPasswordGateway
	protected $reset_id;
	protected $resetcode;
	protected $date;

	public function getMemberGroup()
	{
		return $this->manyToOne('MemberGroup', 'MemberGroup', 'group_id', 'group_id');
	}

	public function setMemberGroup(MemberGroup $group)
	{
		$this->setRelated('MemberGroup', $group);
		$this->group_id = $group->group_id;
		return $this;
	}

	public function getChannelEntries()
	{
		return $this->oneToMany('ChannelEntries', 'ChannelEntry', 'member_id', 'author_id');
	}

	public function setChannelEntries(array $entries)
	{
		$this->setRelated('ChannelEntries', $entries);

		foreach($entries as $entry)
		{
			$entry->author_id = $this->member_id;
		}

		return $this;
	}

	public function clearResetToken()
	{
		$reset_gateway = $this->_factory->makeGateway('ResetPasswordGateway');
		$reset_gateway->reset_id = $this->reset_id;
		$reset_gateway->delete();
	}

}
