<?php
namespace Pure\Templates\Groups{
    class H{
        private function validate(&$parameters){
            $parameters = (is_null  ($parameters) === false ? $parameters : new \stdClass());
            $parameters = (is_object($parameters) === true  ? $parameters : new \stdClass());
            $parameters->only_with_avatar = (isset($parameters->only_with_avatar ) === true ? $parameters->only_with_avatar : true );
            $parameters->attribute          = (isset($parameters->attribute         ) === true  ? $parameters->attribute        : new \stdClass()   );
            $parameters->attribute->name    = (isset($parameters->attribute->name   ) === true  ? $parameters->attribute->name  : ''                );
            $parameters->attribute->value   = (isset($parameters->attribute->value  ) === true  ? $parameters->attribute->value : ''                );
        }
        private function members_inner_HTML($group, $members_number, $data_type_element, $only_with_avatar){
            $data               = new \Pure\Components\WordPress\UserData\Data();
            $members_innerHTML  = '';
            $members_count      = 0;
            foreach($group->members as $member){
                if ($only_with_avatar === true){
                    if ($data->has_user_avatar($member->ID, $member->user_email) === true){
                        $members_count ++;
                        $members_innerHTML .= '<a data-type-element="'.$data_type_element.'" href="'.$member->posts_url.'"><img alt="" data-type-element="'.$data_type_element.'" src="'.$member->avatar.'" /></a>';
                    }
                }else{
                    $members_count ++;
                    $members_innerHTML .= '<a data-type-element="'.$data_type_element.'" href="'.$member->posts_url.'"><img alt="" data-type-element="'.$data_type_element.'" src="'.$member->avatar.'" /></a>';
                }
                if ($members_count >= $members_number){
                    break;
                }
            }
            return $members_innerHTML;
        }
        public function top($group, $parameters = NULL){
            return $this->simple($group, $parameters);
        }
        public function simple($group, $parameters = NULL){
            $this->validate($parameters);
            $members_innerHTML  = $this->members_inner_HTML($group, 4, 'Group.Thumbnail.H.Member', $parameters->only_with_avatar);
            $attribute_str      = ($parameters->attribute->name !== '' ? $parameters->attribute->name.'="'.$parameters->attribute->value.'" ' : '');
            $innerHTML =    '<!--BEGIN: Group thumbnail (SIMPLE) -->'.
                            '<div data-type-element="Group.Thumbnail.H.Container" '.$attribute_str.'>'.
                                '<div data-type-element="Group.Thumbnail.H.Members">'.
                                    $members_innerHTML.
                                '</div>'.
                                '<div data-type-element="Group.Thumbnail.H.Info">'.
                                    '<a data-type-element="Group.Thumbnail.H.Name" href="'.$group->url.'">'.$group->name.'</a>'.
                                    '<a data-type-element="Group.Thumbnail.H.Members">'.$group->count.' members</a>'.
                                '</div>'.
                            '</div>'.
                            '<!--END: Group thumbnail (SIMPLE) -->';
            return $innerHTML;
        }
        private function resources_more(){
            \Pure\Resources\Compressor::instance()->CSS(
                \Pure\Templates\Groups\Initialization::instance()->configuration->paths->css.'/'.'H.more.css'
            );
            \Pure\Templates\ProgressBar\Initialization::instance()->get('D');
        }
        public function more($parameters){
            $this->resources_more($parameters);
            $innerHTML =    '<div data-type-element="Groups.Thumbnail.H.More" '.
                                    'data-type-more-group="'.   $parameters['group'].'" '.
                                    'data-type-more-max="'.     $parameters['maxcount'].'" '.
                                    'data-type-more-template="'.$parameters['template'].'" '.
                                    'data-type-more-settings="'.$parameters['more_settings'].'" '.
                                    'data-type-more-progress="D" '.
                                    'data-type-use="Pure.Components.More">'.
                                '<p data-type-element="Groups.Thumbnail.H.More">more</p>'.
                            '</div>'.
                            '<p data-element-type="Groups.Thumbnail.H.More.Info">'.
                                '<span data-element-type="Groups.Thumbnail.H.More.Info" data-type-use="Pure.Components.More.Shown" data-type-more-group="'.$parameters['group'].'">'.$parameters['shown'].'</span> / '.
                                '<span data-element-type="Groups.Thumbnail.H.More.Info">'.$parameters['total'].'</span>'.
                            '</p>'.
                            '<div data-type-element="Groups.Thumbnail.H.Reset"></div>';
            return $innerHTML;
        }
    }
}
?>