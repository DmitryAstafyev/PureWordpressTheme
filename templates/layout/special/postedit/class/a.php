<?php
namespace Pure\Templates\Layout\Special\PostEdit{
    class A{
        public function get($post_id = false){
            $Editor             = \Pure\Templates\Pages\PostEditor\Initialization::instance()->get('A');
            $innerHTMLEditor    = Initialization::instance()->html(
                'A/one_column_segment_tab',
                array(
                    array('title',      ($post_id === false ? __('Create post', '') : __('Edit post', '')) ),
                    array('content',    ($post_id === false ? $Editor->get() : $Editor->get($post_id)) ),
                )
            );
            $Editor             = NULL;
            $innerHTML              = Initialization::instance()->html(
                'A/layout',
                array(
                    array('editor', $innerHTMLEditor),
                )
            );
            //Attach effects
            \Pure\Components\LockPage\A\Initialization::instance()->attach();
            //Attach global layout
            \Pure\Templates\Layout\Page\Container\Initialization::instance()->attach_resources_of('A');
            return $innerHTML;
        }
    }
}
?>