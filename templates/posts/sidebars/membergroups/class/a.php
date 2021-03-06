<?php
namespace Pure\Templates\Posts\Sidebars\MemberGroups{
    class A{
        private $settings = false;
        private function getSettings(){
            \Pure\Components\WordPress\Settings\Initialization::instance()->attach();
            $settings       = \Pure\Components\WordPress\Settings\Instance::instance()->settings->counts->properties;
            $settings       = \Pure\Components\WordPress\Settings\Instance::instance()->less($settings);
            $this->settings = $settings;
        }
        private function validate(&$parameters){
            if (is_object($parameters) !== false){
                $result = true;
                $result = ($result === false ? false : (isset($parameters->user_id ) !== false ? true : false));
                $parameters->echo = (isset($parameters->echo) === true  ? $parameters->echo : false);
                return $result;
            }
            return false;
        }
        private function innerHTMLTitle($title){
            $Title      = \Pure\Templates\Titles\Initialization::instance()->get('F');
            $innerHTML  = $Title->get($title, (object)array('echo'=>false));
            $Title      = NULL;
            return ($innerHTML !== false ? $innerHTML : '');
        }
        private function innerHTMLAuthorPosts($user_id){
            $Wrapper    = \Pure\Templates\Positioning\Initialization::instance()->get('B');
            $posts      = new \Pure\Plugins\Thumbnails\Posts\Builder(array(
                'content'           => 'author',
                'targets'	        => $user_id,
                'template'	        => 'DMini',
                'title'		        => '',
                'title_type'        => '',
                'maxcount'	        => (int)$this->settings->items_on_sidebars,
                'only_with_avatar'	=> false,
                'profile'	        => '',
                'days'	            => 3650,
                'from_date'         => '',
                'hidetitle'	        => true,
                'thumbnails'	    => false,
                'slider_template'	=> '',
                'tab_template'	    => '',
                'presentation'	    => 'clear',
                'tabs_columns'	    => 1,
                'more'              => true));
            $innerHTML  = $posts->render();
            if ($innerHTML === '' || $innerHTML === false){
                $innerHTML = '<p data-post-element-type="Pure.Posts.SideBar.A.Info">'.__('Nothing to show', 'pure').'</p>';
            }else{
                $innerHTML  = $Wrapper->get($innerHTML, (object)array('id'=>uniqid(), 'column_width'=>'28em', 'node_type'=>'article', 'space'=>'1em'));
            }
            $Wrapper    = NULL;
            return $innerHTML;
        }
        private function innerHTMLGroupsMembers($groups_IDs){
            $innerHTML = '';
            if ($groups_IDs !== false){
                $posts      = new \Pure\Plugins\Thumbnails\Authors\Builder(array(
                    'content'           => 'users_of_group',
                    'targets'	        => implode(',',$groups_IDs),
                    'template'	        => 'A',
                    'title'		        => '',
                    'title_type'        => '',
                    'maxcount'	        => (int)$this->settings->items_on_sidebars,
                    'only_with_avatar'	=> false,
                    'profile'	        => '',
                    'days'	            => 3650,
                    'from_date'         => '',
                    'hidetitle'	        => true,
                    'thumbnails'	    => false,
                    'slider_template'	=> '',
                    'tab_template'	    => '',
                    'presentation'	    => 'clear',
                    'tabs_columns'	    => 1,
                    'show_content'      =>false,
                    'show_admin_part'   =>false,
                    'show_life'         =>false,
                    'more'              => true));
                $innerHTML  = $posts->render();
            }
            if ($innerHTML === '' || $innerHTML === false){
                $innerHTML = '<p data-post-element-type="Pure.Posts.SideBar.A.Info">'.__('Nothing to show', 'pure').'</p>';
            }
            return $innerHTML;
        }
        public function innerHTML($parameters){
            $innerHTML = '';
            if ($this->validate($parameters) !== false){
                $this->getSettings();
                $WordPress  = new \Pure\Components\WordPress\UserData\Data();
                $user_name  = $WordPress->get_name((int)$parameters->user_id);
                $WordPress  = NULL;
                $Provider   = \Pure\Providers\Groups\Initialization::instance()->get('users');
                $groups     = $Provider->get(
                    array(
                        'from_date'         =>date("Y-m-d H:i:s"),
                        'days'              =>3666,
                        'targets_array'     =>array((int)$parameters->user_id),
                        'shown'             =>0,
                        'maxcount'          =>100,
                        'only_with_avatar'  =>false
                    )
                );
                $groups_IDs = false;
                if ($groups !== false){
                    $groups = $groups->groups;
                    if (count($groups) > 0){
                        $groups_IDs = array();
                        foreach($groups as $group){
                            $groups_IDs[] = (int)$group->id;
                        }
                    }
                }
                $innerHTML .=   '<!--BEGIN: Post.Sidebar.A -->'.
                                '<div data-post-element-type="Pure.Posts.SideBar.A.Container">'.
                                    $this->innerHTMLTitle($user_name.' '.__('writes', 'pure')).
                                    $this->innerHTMLAuthorPosts($parameters->user_id).
                                    $this->innerHTMLTitle(__('Members groups', 'pure')).
                                    $this->innerHTMLGroupsMembers($groups_IDs).
                                '</div>'.
                                '<!--END: Post.Sidebar.A -->';
            }
            if ($parameters->echo !== false){
                echo $innerHTML;
            }
            return $innerHTML;
        }
    }
}
?>