<?php
defined('ABSPATH') OR exit;

function sb_clean_menu() {
    SB_Admin_Custom::add_submenu_page("SB Clean", "sb_clean", array('SB_Admin_Custom', 'setting_page_callback'));
}
add_action("sb_admin_menu", "sb_clean_menu");

function sb_clean_tab($tabs) {
    $tabs["sb_clean"] = array('title' => "SB Clean", 'section_id' => "sb_clean_section", "type" => "plugin");
    return $tabs;
}
add_filter("sb_admin_tabs", "sb_clean_tab");

function sb_clean_setting_field() {
    SB_Admin_Custom::add_section("sb_clean_section", __("SB Clean options page", "sb-clean"), "sb_clean");
    SB_Admin_Custom::add_setting_field("sb_clean_wpdb", __("Clean WPDB", "sb-clean"), "sb_clean_section", "sb_clean_wpdb_callback", "sb_clean");
    SB_Admin_Custom::add_setting_field("sb_clean_head_meta", __("Clean head meta", "sb-clean"), "sb_clean_section", "sb_clean_head_meta_callback", "sb_clean");
}
add_action("sb_admin_init", "sb_clean_setting_field");

function sb_clean_wpdb_callback() {
    $name = "sb_clean_wpdb";
    $options = get_option("sb_options");
    $value = isset($options["clean"]["wpdb"]) ? $options["clean"]["wpdb"] : 1;
    $description = __("You can turn on or turn off the function to unset database name and password.", "sb-clean");
    SB_Field::switch_button($name, "sb_options[clean][wpdb]", $value, $description);
}

function sb_clean_head_meta_callback() {
    $name = "sb_clean_head_meta";
    $options = get_option("sb_options");
    $value = isset($options["clean"]["head_meta"]) ? $options["clean"]["head_meta"] : 1;
    $description = __("You can turn on or turn off the function to clear WordPress head meta.", "sb-clean");
    SB_Field::switch_button($name, "sb_options[clean][head_meta]", $value, $description);
}

function sb_clean_sanitize($input) {
    $data = $input;
    $data["clean"]["wpdb"] = isset($input["clean"]["wpdb"]) ? $input["clean"]["wpdb"] : 1;
    $data["clean"]["head_meta"] = isset($input["clean"]["head_meta"]) ? $input["clean"]["head_meta"] : 1;
    return $data;
}
add_filter("sb_options_sanitize", "sb_clean_sanitize");