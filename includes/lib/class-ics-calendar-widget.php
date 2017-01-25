<?php

if (!defined('ABSPATH')) exit;

class ICS_Calendar_Widget extends WP_Widget
{

    function __construct()
    {
        // Instantiate the parent object
        parent::__construct(false, __('ICS Calendar', 'ics-calendar'));
    }

    function widget($args, $instance)
    {
        echo '<h2>'.$instance['title'].'</h2>';
        $icshelper = new ICS_Calendar_ICS_Helper();
        echo $icshelper->get_ics();
    }


    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = '';
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}