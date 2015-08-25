# Require any additional compass plugins here.
require 'fileutils'
require 'bootstrap-sass'


# Directory Settings
http_path = "/"
css_dir = "assets/css"
sass_dir = "assets/scss"
javascripts_dir = "assets/js"
fonts_dir = "assets/fonts"
http_images_path = "/wp-content/themes/Paradox/assets/images/"
# images_dir = "assets/images"


output_style = :expanded
line_comments = true
preferred_syntax = :scss


# If file is named style.css then move up one directory
on_stylesheet_saved do |file|
  if File.exists?(file) && File.basename(file) == "style.css"
    puts "Moving: #{file}"
    FileUtils.mv(file, File.dirname(file) + "/../../" + File.basename(file))
  end
end


# get the name of your theme folder
WP_THEME_FOLDER = File.basename(File.dirname(__FILE__))
 
module Sass::Script::Functions
  # new function to putput the theme folder for use in style.scss
  def wp_theme_name()
    Sass::Script::String.new(WP_THEME_FOLDER)
  end
end